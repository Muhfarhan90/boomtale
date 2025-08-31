<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // ==================== USER METHODS ====================

    /**
     * Display products for users (public)
     */
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();

        $products = Product::where('is_active', true)
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%") // Menggunakan 'name'
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->sort, function ($query, $sort) {
                switch ($sort) {
                    case 'price_low':
                        $query->orderBy('price', 'asc');
                        break;
                    case 'price_high':
                        $query->orderBy('price', 'desc');
                        break;
                    case 'popular':
                        $query->withCount(['userProducts'])->orderBy('user_products_count', 'desc'); // Menggunakan userProducts
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->with('category')
            ->paginate(12);

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show product detail for users
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404, 'Produk tidak tersedia');
        }

        // Load relationships
        $product->load(['category', 'reviews.user']);

        $userOwnsProduct = false;
        $userHasReviewed = false;
        $userReview = null;

        if (auth()->check()) {
            // Check if user owns the product
            $userOwnsProduct = auth()->user()->userProducts()
                ->where('product_id', $product->id)
                ->exists();

            // Check if user has reviewed this product
            $userHasReviewed = auth()->user()->reviews()
                ->where('product_id', $product->id)
                ->exists();

            if ($userHasReviewed) {
                $userReview = auth()->user()->reviews()
                    ->where('product_id', $product->id)
                    ->first();
            }
        }

        // Get paginated reviews
        $reviews = $product->reviews()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'reviews_page');

        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        // Calculate review statistics
        $reviewStats = [
            'average' => round($product->reviews()->avg('rating') ?? 0, 1),
            'total' => $product->reviews()->count(),
            'distribution' => [
                5 => $product->reviews()->where('rating', 5)->count(),
                4 => $product->reviews()->where('rating', 4)->count(),
                3 => $product->reviews()->where('rating', 3)->count(),
                2 => $product->reviews()->where('rating', 2)->count(),
                1 => $product->reviews()->where('rating', 1)->count(),
            ]
        ];

        return view('products.show', compact(
            'product',
            'userOwnsProduct',
            'userHasReviewed',
            'userReview',
            'reviews',
            'relatedProducts',
            'reviewStats'
        ));
    }

    /**
     * Securely download the digital file for a product.
     */
    public function download(Product $product)
    {
        // 1. Pastikan produknya digital
        if (!$product->isDigital() || !$product->digital_file_path) {
            abort(404, 'File tidak ditemukan.');
        }

        // 2. Cek apakah user memiliki produk ini
        $userOwnsProduct = auth()->user()->userProducts()
            ->where('product_id', $product->id)
            ->exists();

        if (!$userOwnsProduct) {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh file ini.');
        }

        // 3. Cek apakah file benar-benar ada di storage
        if (!Storage::exists($product->digital_file_path)) {
            abort(404, 'File rusak atau tidak ditemukan. Hubungi admin.');
        }

        // 4. Jika semua valid, stream download ke user
        return Storage::download($product->digital_file_path);
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Display products for admin
     */
    public function adminIndex(Request $request)
    {
        $products = Product::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%") // Menggunakan 'name'
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('is_active', $status); // Cukup $status karena value-nya 0 atau 1
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->with(['category'])
            ->withCount('userProducts') // Menghitung penjualan
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::all();
        $digitalCount = Product::where('type', 'digital')->count();
        $physicalCount = Product::where('type', 'physical')->count();
        $featuredCount = Product::where('is_featured', true)->count();
        return view('admin.products.index', compact('products', 'categories', 'digitalCount', 'physicalCount', 'featuredCount'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string',
            'type' => 'required|in:digital,physical',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072 ',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'digital_file' => 'nullable|required_if:type,digital|file|mimes:zip,pdf,epub,mp4|max:307200',
            'stock' => 'nullable|required_if:type,physical|integer|min:0',
        ]);

        $data = $request->except(['featured_image', 'gallery_images', 'digital_file']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('products/featured', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $file) {
                $galleryPaths[] = $file->store('products/gallery', 'public');
            }
            $data['gallery_images'] = $galleryPaths;
        }

        if ($request->hasFile('digital_file')) {
            $data['digital_file_path'] = $request->file('digital_file')->store('digital_files');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show product detail for admin
     */
    public function adminShow(Product $product)
    {
        $product->load(['category', 'userProducts.user']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing product
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'required|string',
            'type' => 'required|in:digital,physical',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072 ',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'digital_file' => 'nullable|required_if:type,digital|file|mimes:zip,pdf,epub,mp4|max:307200',
            'stock' => 'nullable|required_if:type,physical|integer|min:0',
        ]);

        $data = $request->except(['featured_image', 'gallery_images', 'digital_file']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        // Handle Featured Image Update
        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('products/featured', 'public');
        }

        // Handle Gallery Images Update (menambahkan, bukan mengganti total)
        if ($request->hasFile('gallery_images')) {
            $newGalleryPaths = [];
            foreach ($request->file('gallery_images') as $file) {
                $newGalleryPaths[] = $file->store('products/gallery', 'public');
            }
            // Gabungkan galeri lama dengan yang baru
            $data['gallery_images'] = array_merge($product->gallery_images ?? [], $newGalleryPaths);
        }

        // Handle Digital File Update
        if ($request->hasFile('digital_file')) {
            // Delete old file if it exists
            if ($product->digital_file_path) {
                Storage::delete($product->digital_file_path); // Menggunakan disk default (private)
            }
            $data['digital_file_path'] = $request->file('digital_file')->store('digital_files');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Check if product has been purchased
        if ($product->userProducts()->exists()) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Tidak dapat menghapus produk yang sudah pernah dibeli.');
        }

        // Delete Featured Image
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }

        // Delete Gallery Images
        if ($product->gallery_images) {
            foreach ($product->gallery_images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        // Delete Digital File
        if ($product->digital_file_path) {
            Storage::delete($product->digital_file_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return response()->json(['success' => true, 'message' => "Produk berhasil {$status}"]);
    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'products' => 'required|array',
            'products.*' => 'exists:products,id'
        ]);

        $productIds = $request->products;

        switch ($request->action) {
            case 'activate':
                Product::whereIn('id', $productIds)->update(['is_active' => true]);
                $message = 'Produk yang dipilih berhasil diaktifkan.';
                break;
            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['is_active' => false]);
                $message = 'Produk yang dipilih berhasil dinonaktifkan.';
                break;
            case 'delete':
                $products = Product::whereIn('id', $productIds)->get();
                $undeletableCount = 0;

                foreach ($products as $product) {
                    if ($product->userProducts()->exists()) {
                        $undeletableCount++;
                        continue; // Lewati produk yang sudah dibeli
                    }
                    // Hapus semua file terkait
                    if ($product->featured_image) Storage::disk('public')->delete($product->featured_image);
                    if ($product->gallery_images) Storage::disk('public')->delete($product->gallery_images);
                    if ($product->digital_file_path) Storage::delete($product->digital_file_path);
                    $product->delete();
                }

                $message = (count($products) - $undeletableCount) . ' produk berhasil dihapus.';
                if ($undeletableCount > 0) {
                    $message .= " $undeletableCount produk tidak dapat dihapus karena sudah pernah dibeli.";
                }
                break;
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
