<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        $user->update([
            'Nama' => $request->nama,
            'umur' => $request->umur ?: null,
            'tempat_lahir' => $request->tempat_lahir ?: null,
            'phone' => $request->phone ?: null,
            'phone_keluarga' => $request->phone_keluarga ?: null,
            'alamat' => $request->alamat ?: null,
        ]);

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

        
        if (!empty($user->avatar)) {
            $oldPath = public_path('uploads/' . basename($user->avatar));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        
        $file = $request->file('photo');
        $filename = 'profile_' . substr(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), 0, 8)
            . '_' . time() . '_' . bin2hex(random_bytes(4))
            . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads'), $filename);

        $user->update(['avatar' => 'uploads/' . $filename]);

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
}
