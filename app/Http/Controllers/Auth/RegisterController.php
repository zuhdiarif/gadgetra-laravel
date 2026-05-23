<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $key = 'register-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak percobaan registrasi. Coba lagi dalam {$seconds} detik."
            ], 429);
        }

        RateLimiter::hit($key, 300);

        $request->validate([
            'email' => 'required|email|max:50|unique:user,Email',
            'password' => 'required|min:8|max:128',
            'confirm_password' => 'required|same:password',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 50 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 128 karakter.',
            'confirm_password.required' => 'Konfirmasi password harus diisi.',
            'confirm_password.same' => 'Konfirmasi password tidak cocok.',
        ]);

        
        $password = $request->password;
        $strengthScore = 0;
        if (preg_match('/[A-Z]/', $password)) $strengthScore++;
        if (preg_match('/[a-z]/', $password)) $strengthScore++;
        if (preg_match('/[0-9]/', $password)) $strengthScore++;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $strengthScore++;

        if ($strengthScore < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dimasukkan tidak valid.',
                'errors' => ['password' => 'Password terlalu lemah. Gunakan kombinasi huruf besar, huruf kecil, angka, atau simbol.']
            ]);
        }

        User::create([
            'Email' => $request->email,
            'password' => Hash::make($password, ['rounds' => 12]),
        ]);

        RateLimiter::clear($key);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan login.'
        ]);
    }
}
