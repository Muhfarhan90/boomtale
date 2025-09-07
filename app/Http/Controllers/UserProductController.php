<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserProductController extends Controller
{
    public function index()
    {
        // Ambil produk yang dimiliki user dengan relasi lengkap
        $userProducts = UserProduct::with(['product.category'])
            ->where('user_id', auth()->id())
            ->whereHas('product', function ($query) {
                $query->where('type', 'digital'); // Pastikan hanya produk digital
            })
            ->orderByDesc('created_at')
            ->get();

        return view('user_products.index', compact('userProducts'));
    }

    public function show($id)
    {
        $userProduct = UserProduct::with(['product.category', 'order'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $product = $userProduct->product;

        // Debug informasi file
        $debugInfo = [
            'id' => $product->id,
            'name' => $product->name,
            'type' => $product->type,
            'digital_file_path' => $product->digital_file_path,
            'file_exists' => false,
            'file_size' => 0,
            'file_size_formatted' => '0 B', // TAMBAHKAN INI
            'mime_type' => null,
            'extension' => null,
            'can_stream' => false,
            'storage_path' => null,
            'full_path' => null, // UNTUK DEBUG
        ];

        if ($product->digital_file_path) {
            $debugInfo['file_exists'] = Storage::exists($product->digital_file_path);
            $debugInfo['full_path'] = Storage::path($product->digital_file_path); // DEBUG

            if ($debugInfo['file_exists']) {
                $debugInfo['file_size'] = Storage::size($product->digital_file_path);
                $debugInfo['file_size_formatted'] = $this->formatFileSize($debugInfo['file_size']); // FORMAT DI SINI
                $debugInfo['mime_type'] = Storage::mimeType($product->digital_file_path);
                $debugInfo['extension'] = strtolower(pathinfo($product->digital_file_path, PATHINFO_EXTENSION));
                $debugInfo['storage_path'] = Storage::path($product->digital_file_path);
                $debugInfo['can_stream'] = in_array($debugInfo['extension'], ['mp4', 'webm', 'ogg', 'pdf']);
            }
        }

        return view('user_products.show', compact('userProduct', 'debugInfo'));
    }

    /**
     * Download digital file yang sudah dibeli user
     */
    public function download($id)
    {
        $userProduct = UserProduct::with('product')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $product = $userProduct->product;

        // Validasi produk digital
        if ($product->type !== 'digital' || !$product->digital_file_path) {
            return back()->with('error', 'File tidak tersedia untuk didownload.');
        }

        // Cek keberadaan file
        if (!Storage::exists($product->digital_file_path)) {
            return back()->with('error', 'File tidak ditemukan. Silakan hubungi admin.');
        }

        try {
            // Log download activity
            \Log::info('Digital product downloaded', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'product_name' => $product->name,
                'file_path' => $product->digital_file_path
            ]);

            $filePath = Storage::path($product->digital_file_path);
            $originalExtension = pathinfo($product->digital_file_path, PATHINFO_EXTENSION);
            $fileName = $this->sanitizeFileName($product->name) . '.' . $originalExtension;

            // PERBAIKAN ANTI-IDM: Gunakan stream response
            return response()->stream(function () use ($filePath) {
                $handle = fopen($filePath, 'rb');
                while (!feof($handle)) {
                    echo fread($handle, 8192);
                    ob_flush();
                    flush();
                }
                fclose($handle);
            }, 200, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Content-Length' => filesize($filePath),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Robots-Tag' => 'noindex',
            ]);
        } catch (\Exception $e) {
            \Log::error('Download failed', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal mendownload file. Silakan coba lagi.');
        }
    }

    /**
     * Stream video/ebook untuk ditampilkan di browser
     */
    public function stream($id)
    {
        $userProduct = UserProduct::with('product')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $product = $userProduct->product;

        if ($product->type !== 'digital' || !$product->digital_file_path || !Storage::exists($product->digital_file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $filePath = Storage::path($product->digital_file_path);
        $extension = strtolower(pathinfo($product->digital_file_path, PATHINFO_EXTENSION));

        // UNTUK PDF - TAMBAH HEADERS UNTUK IFRAME
        if ($extension === 'pdf') {
            $fileName = $this->sanitizeFileName($product->name) . '.pdf';
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'X-Frame-Options' => 'SAMEORIGIN', // IZINKAN IFRAME DARI DOMAIN YANG SAMA
                'Content-Security-Policy' => "frame-ancestors 'self'", // CSP untuk iframe
            ]);
        }

        // Video dan lainnya tetap sama
        if (in_array($extension, ['mp4', 'webm', 'ogg', 'avi', 'mov'])) {
            $mimeType = Storage::mimeType($product->digital_file_path);
            $fileSize = Storage::size($product->digital_file_path);

            if (request()->hasHeader('Range')) {
                return $this->handleRangeRequest($filePath, $fileSize, $mimeType);
            }

            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Length' => $fileSize,
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'public, max-age=3600',
                'Content-Disposition' => 'inline'
            ]);
        }

        return response()->file($filePath);
    }


    public function pdfStream($id)
    {
        // ... (validasi tetap sama) ...
        $userProduct = UserProduct::with('product')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $product = $userProduct->product;
        $extension = strtolower(pathinfo($product->digital_file_path, PATHINFO_EXTENSION));

        if ($extension !== 'pdf' || !Storage::exists($product->digital_file_path)) {
            abort(404, 'PDF tidak ditemukan.');
        }

        $filePath = Storage::path($product->digital_file_path);

        // Strategi Anti-IDM: Stream file byte-by-byte dengan header minimal.
        // IDM cenderung tidak bisa mendeteksi file stream tanpa Content-Disposition: attachment.
        return new StreamedResponse(function () use ($filePath) {
            $handle = fopen($filePath, 'rb');
            if ($handle === false) {
                return;
            }
            while (!feof($handle)) {
                echo fread($handle, 8192); // Baca 8KB
                ob_flush();
                flush();
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]);
    }

    private function formatFileSize($bytes, $precision = 2)
    {
        if ($bytes == 0) return '0 B';

        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Handle range requests untuk video streaming
     */
    private function handleRangeRequest($filePath, $fileSize, $mimeType)
    {
        $request = request();
        $range = $request->header('Range');

        // Parse range header
        if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
            $start = intval($matches[1]);
            $end = $matches[2] ? intval($matches[2]) : $fileSize - 1;
            $length = $end - $start + 1;

            $headers = [
                'Content-Type' => $mimeType,
                'Content-Length' => $length,
                'Accept-Ranges' => 'bytes',
                'Content-Range' => "bytes $start-$end/$fileSize",
            ];

            $handle = fopen($filePath, 'rb');
            fseek($handle, $start);
            $data = fread($handle, $length);
            fclose($handle);

            return Response::make($data, 206, $headers);
        }

        return Response::file($filePath);
    }

    /**
     * Sanitize filename untuk download
     */
    private function sanitizeFileName($fileName)
    {
        // Remove special characters
        $fileName = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $fileName);
        // Replace spaces with underscores
        $fileName = str_replace(' ', '_', $fileName);
        // Remove multiple underscores
        $fileName = preg_replace('/_+/', '_', $fileName);

        return trim($fileName, '_');
    }
}
