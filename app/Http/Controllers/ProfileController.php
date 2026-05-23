<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
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

        if ($userTransactions->isEmpty() && $user->Email !== 'admin@gadgetra.com') {
            $codeActive = 'RNT' . strtoupper(substr(md5(time() . 'active'), 0, 6));
            $codeDone = 'RNT' . strtoupper(substr(md5(time() . 'done'), 0, 6));

            $injected = [
                [
                    'code' => $codeActive,
                    'user_id' => $user->ID,
                    'product_id' => Product::where('slug', 'sony-alpha-iv')->first()?->id,
                    'customer_name' => $user->Nama ?? 'User',
                    'customer_email' => $user->Email,
                    'customer_phone' => $user->phone ?? '0812-3456-7890',
                    'customer_address' => $user->alamat ?? 'Malang, Jawa Timur',
                    'product_name' => 'Sony Alpha IV',
                    'product_slug' => 'sony-alpha-iv',
                    'product_image' => 'Sony Alpha A7 IV Camera.png',
                    'qty' => 1,
                    'start_date' => date('Y-m-d', strtotime('-1 day')),
                    'end_date' => date('Y-m-d', strtotime('+2 days')),
                    'total_price' => 300000,
                    'status' => 'Sedang Disewa',
                    'remaining_time' => '48 : 00 : 00'
                ],
                [
                    'code' => $codeDone,
                    'user_id' => $user->ID,
                    'product_id' => Product::where('slug', 'macbook-pro-m3')->first()?->id,
                    'customer_name' => $user->Nama ?? 'User',
                    'customer_email' => $user->Email,
                    'customer_phone' => $user->phone ?? '0812-3456-7890',
                    'customer_address' => $user->alamat ?? 'Malang, Jawa Timur',
                    'product_name' => 'MacBook Pro M3',
                    'product_slug' => 'macbook-pro-m3',
                    'product_image' => 'MacBook Pro M3 Space Black.png',
                    'qty' => 1,
                    'start_date' => date('Y-m-d', strtotime('-7 days')),
                    'end_date' => date('Y-m-d', strtotime('-5 days')),
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ]
            ];

            foreach ($injected as $tData) {
                Transaction::create($tData);
            }

            $userTransactions = Transaction::where('customer_email', $user->Email)->get();
        }

        $activeRentals = $userTransactions->filter(function ($t) {
            return $t->status === 'Sedang Disewa' || $t->status === 'Belum dibayar';
        });

        $completedRentals = $userTransactions->filter(function ($t) {
            return $t->status === 'Selesai';
        });

        return view('profile.rentals', compact('user', 'activeRentals', 'completedRentals'));
    }
}
