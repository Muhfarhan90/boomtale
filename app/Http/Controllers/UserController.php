<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $breadcrumbs = [
            ['title' => 'Pengguna', 'url' => null]
        ];

        return view('admin.users.index', compact('users', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $breadcrumbs = [
            ['title' => 'Pengguna', 'url' => route('admin.users.index')],
            ['title' => 'Tambah User', 'url' => null]
        ];

        return view('admin.users.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'is_active' => 'boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['orders.orderItems.product', 'reviews.product']);

        $breadcrumbs = [
            ['title' => 'Pengguna', 'url' => route('admin.users.index')],
            ['title' => $user->name, 'url' => null]
        ];

        return view('admin.users.show', compact('user', 'breadcrumbs'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $breadcrumbs = [
            ['title' => 'Pengguna', 'url' => route('admin.users.index')],
            ['title' => 'Edit ' . $user->name, 'url' => null]
        ];

        return view('admin.users.edit', compact('user', 'breadcrumbs'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active'),
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Check if user has orders
        if ($user->orders()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak dapat dihapus karena memiliki riwayat pesanan.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Display admins only
     */
    public function admins()
    {
        $users = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $breadcrumbs = [
            ['title' => 'Admin', 'url' => null]
        ];

        return view('admin.users.admins', compact('users', 'breadcrumbs'));
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "User berhasil {$status}.");
    }
}
