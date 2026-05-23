<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $key = 'login-attempts:' . Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik."
            ], 429);
        }

        $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password harus diisi.',
        ]);

        RateLimiter::hit($key, 120);

        if (Auth::attempt(['Email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            RateLimiter::clear($key);

            $user = Auth::user();

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect' => route('home'),
                'user' => [
                    'nama' => $user->Nama,
                    'email' => $user->Email,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil.',
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login');
    }
}
