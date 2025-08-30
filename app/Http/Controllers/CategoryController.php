<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // ==================== USER METHODS ====================

    /**
     * Display categories for users (public)
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->get();

        return view('user.categories.index', compact('categories'));
    }

    /**
     * Show category with products for users
     */
    public function show(Category $category, Request $request)
    {
        if (!$category->is_active) {
            abort(404, 'Kategori tidak tersedia');
        }

        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
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
                        $query->withCount(['orderItems'])->orderBy('order_items_count', 'desc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->paginate(12);

        return view('user.categories.show', compact('category', 'products'));
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Display categories for admin
     */
    public function adminIndex(Request $request)
    {
        $categories = Category::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->withCount(['products'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show category detail for admin
     */
    public function adminShow(Category $category)
    {
        $category->load(['products' => function ($query) {
            $query->withCount(['orderItems']);
        }]);

        $stats = [
            'total_products' => $category->products->count(),
            'active_products' => $category->products->where('is_active', true)->count(),
            'total_sales' => $category->products->sum(function ($product) {
                return $product->order_items_count ?? 0;
            })
        ];

        return view('admin.categories.show', compact('category', 'stats'));
    }

    /**
     * Show the form for editing category
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id)
            ],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Tidak dapat menghapus kategori yang masih memiliki produk');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Kategori berhasil {$status}",
            'status' => $category->is_active
        ]);
    }

    /**
     * Bulk actions for categories
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id'
        ]);

        $categories = Category::whereIn('id', $request->categories);

        switch ($request->action) {
            case 'activate':
                $categories->update(['is_active' => true]);
                $message = 'Kategori berhasil diaktifkan';
                break;
            case 'deactivate':
                $categories->update(['is_active' => false]);
                $message = 'Kategori berhasil dinonaktifkan';
                break;
            case 'delete':
                // Check if any category has products
                $hasProducts = $categories->whereHas('products')->exists();
                if ($hasProducts) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus kategori yang masih memiliki produk'
                    ]);
                }

                $categories->delete();
                $message = 'Kategori berhasil dihapus';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    // ==================== SHARED METHODS ====================

    /**
     * Get category statistics
     */
    public function getStats()
    {
        $stats = [
            'total' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'inactive' => Category::where('is_active', false)->count(),
            'with_products' => Category::whereHas('products')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get categories for select dropdown
     */
    public function getForSelect()
    {
        $categories = Category::where('is_active', true)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    /**
     * Search categories (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $categories = Category::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'slug', 'icon', 'color']);

        return response()->json($categories);
    }

    /**
     * Reorder categories
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.sort_order' => 'required|integer'
        ]);

        foreach ($request->categories as $categoryData) {
            Category::where('id', $categoryData['id'])
                ->update(['sort_order' => $categoryData['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan kategori berhasil diperbarui'
        ]);
    }
}
