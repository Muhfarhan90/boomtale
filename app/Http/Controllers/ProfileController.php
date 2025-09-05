<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'phone_number' => [
                'nullable',
                'string',
                'regex:/^(\+62|62|0)[0-9]{9,13}$/' // PERBAIKAN: Pastikan ada delimiter penutup
            ],
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.min' => 'Nama minimal 2 karakter',
            'name.max' => 'Nama maksimal 255 karakter',
            'phone_number.regex' => 'Format nomor telepon tidak valid. Contoh: 08123456789 atau +6281234567890'
        ]);

        Auth::user()->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('user.profile.index')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();

        // Log user deletion
        \Log::info('User account deleted', [
            'user_id' => $user->id,
            'email' => $user->email,
            'deleted_at' => now()
        ]);

        // Delete user and logout
        $user->delete();
        Auth::logout();

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dihapus'
        ]);
    }
}
