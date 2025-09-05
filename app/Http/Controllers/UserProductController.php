<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Storage;

class UserProductController extends Controller
{
    public function index()
    {
        // Ambil produk yang dimiliki user (sudah dibeli) dengan relasi lengkap
        $userProducts = UserProduct::with(['product.category', 'order'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();

        return view('user_products.index', compact('userProducts'));
    }

    public function show($id)
    {
        // Detail produk milik user dengan relasi lengkap
        $userProduct = UserProduct::with(['product.category', 'order'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // PERBAIKAN: Gunakan kolom yang benar dari database
        $debugInfo = [];
        if ($userProduct->product) {
            $product = $userProduct->product;

            $debugInfo = [
                'type' => $product->type,
                'digital_file_path' => $product->digital_file_path, // Gunakan kolom yang benar
                'featured_image' => $product->featured_image,
                'gallery_images' => $product->gallery_images,
                'file_exists' => $product->digital_file_path ? Storage::exists($product->digital_file_path) : false,
                'storage_url_file' => $product->digital_file_path ? Storage::url($product->digital_file_path) : null,
                'featured_image_url' => $product->featured_image ? Storage::url($product->featured_image) : null,
            ];
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

        // Pastikan produk adalah digital dan memiliki file
        if ($product->type !== 'digital' || !$product->digital_file_path) {
            abort(404, 'File tidak ditemukan.');
        }

        // Cek apakah file benar-benar ada di storage
        if (!Storage::exists($product->digital_file_path)) {
            abort(404, 'File rusak atau tidak ditemukan. Hubungi admin.');
        }

        // Log aktivitas download
        \Log::info('Digital product downloaded', [
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'product_name' => $product->name
        ]);

        // Download file
        return Storage::download($product->digital_file_path, $product->name . '.' . pathinfo($product->digital_file_path, PATHINFO_EXTENSION));
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

        // Pastikan produk adalah digital dan memiliki file
        if ($product->type !== 'digital' || !$product->digital_file_path) {
            abort(404, 'File tidak ditemukan.');
        }

        // Cek apakah file benar-benar ada di storage
        if (!Storage::exists($product->digital_file_path)) {
            abort(404, 'File rusak atau tidak ditemukan. Hubungi admin.');
        }

        $filePath = Storage::path($product->digital_file_path);
        $mimeType = Storage::mimeType($product->digital_file_path);

        // Return file response untuk streaming
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($product->digital_file_path) . '"'
        ]);
    }
}
