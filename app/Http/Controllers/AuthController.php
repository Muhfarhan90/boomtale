<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ]);
            }

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang di Admin Dashboard!');
            } else {
                return redirect()->intended(route('user.home'))
                    ->with('success', 'Selamat datang kembali!');
            }
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan salah.'],
        ]);
    }

    /**
     * Show register form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('user.home')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di Boomtale.');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.home')
            ->with('success', 'Anda berhasil logout.');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleAuthGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user yang sudah terhubung dengan google_id ini
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // Jika sudah ada, langsung login
                Auth::login($user);
                return redirect('/');
            }

            // Jika tidak ada, cari berdasarkan email
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Jika user dengan email tersebut sudah ada (misalnya daftar manual)
                // Update datanya untuk menautkan google_id dan avatar
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::login($existingUser);
                return redirect('/');
            }

            // Jika tidak ada sama sekali, buat user baru
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => encrypt('my-google')
            ]);

            Auth::login($newUser);
            return redirect('/');
        } catch (Exception $e) {
            // Anda bisa menambahkan logging di sini untuk melacak error
            // \Log::error($e->getMessage());
            return redirect('/login')->with('error', 'Something went wrong!');
        }
    }
}
