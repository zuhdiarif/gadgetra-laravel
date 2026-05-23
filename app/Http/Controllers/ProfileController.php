<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|min:3|max:255',
            'umur' => 'nullable|integer|min:1|max:150',
            'tempat_lahir' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'phone_keluarga' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:1000',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.min' => 'Nama minimal 3 karakter.',
            'umur.integer' => 'Umur harus berupa angka.',
            'umur.min' => 'Umur tidak valid.',
            'umur.max' => 'Umur tidak valid.',
        ]);

        $user->updateProfile($request->all());

        return redirect()->route('profile.show')->with('success', 'profile');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'photo.required' => 'Tidak ada file yang dikirim.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format file tidak diizinkan. Gunakan JPG atau PNG.',
            'photo.max' => 'Ukuran file melebihi batas 5MB.',
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $user->uploadAvatarFile($request->file('photo'));

        return redirect()->route('profile.show')->with('success', 'photo');
    }

    public function getData()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        return response()->json([
            'Nama' => $user->Nama,
            'Email' => $user->Email,
            'umur' => $user->umur,
            'tempat_lahir' => $user->tempat_lahir,
            'phone' => $user->phone,
            'phone_keluarga' => $user->phone_keluarga,
            'alamat' => $user->alamat,
            'avatar' => $user->avatar_url,
        ]);
    }

    public function rentals()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $userTransactions = Transaction::where('customer_email', $user->Email)->get();

        $activeRentals = $userTransactions->filter(function ($t) {
            return $t->status === 'Sedang Disewa' || $t->status === 'Belum dibayar';
        });

        $completedRentals = $userTransactions->filter(function ($t) {
            return $t->status === 'Selesai';
        });

        return view('profile.rentals', compact('user', 'activeRentals', 'completedRentals'));
    }
}
