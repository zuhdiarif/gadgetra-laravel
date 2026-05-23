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

    public function rentals()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        if (!session()->has('admin_transactions')) {
            $simulated = [
                [
                    'code' => 'TYZ10CH6U',
                    'customer_name' => 'Budiono Siregar',
                    'customer_email' => 'Budi01R@gmail.com',
                    'customer_phone' => '0812-3456-7890',
                    'customer_address' => 'Malang Kota, Jawa Timur',
                    'product_name' => 'Sony Alpha IV',
                    'product_slug' => 'sony-alpha-iv',
                    'product_image' => 'Sony Alpha A7 IV Camera.png',
                    'qty' => 2,
                    'start_date' => date('Y-m-d', strtotime('tuesday this week')),
                    'end_date' => date('Y-m-d', strtotime('wednesday this week')),
                    'total_price' => 600000,
                    'status' => 'Sedang Disewa',
                    'remaining_time' => '30 : 42 : 12'
                ],
                [
                    'code' => 'KJD93HJ2A',
                    'customer_name' => 'Siti Aminah',
                    'customer_email' => 'sitiaminah@gmail.com',
                    'customer_phone' => '0821-9876-5432',
                    'customer_address' => 'Surabaya, Jawa Timur',
                    'product_name' => 'MacBook Pro M3',
                    'product_slug' => 'macbook-pro-m3',
                    'product_image' => 'MacBook Pro M3 Space Black.png',
                    'qty' => 1,
                    'start_date' => date('Y-m-d', strtotime('thursday this week')),
                    'end_date' => date('Y-m-d', strtotime('saturday this week')),
                    'total_price' => 500000,
                    'status' => 'Belum dibayar',
                    'remaining_time' => '48 : 00 : 00'
                ],
                [
                    'code' => 'LQM48PL7B',
                    'customer_name' => 'Rian Hidayat',
                    'customer_email' => 'rianh@gmail.com',
                    'customer_phone' => '0813-5555-8888',
                    'customer_address' => 'Batu, Jawa Timur',
                    'product_name' => 'iPhone 15 Pro Max',
                    'product_slug' => 'iphone-15-pro-max',
                    'product_image' => 'iPhone 15 Pro Max Natural Titanium.png',
                    'qty' => 1,
                    'start_date' => date('Y-m-d', strtotime('friday this week')),
                    'end_date' => date('Y-m-d', strtotime('saturday this week')),
                    'total_price' => 150000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'XPW32NZ4C',
                    'customer_name' => 'Dewi Lestari',
                    'customer_email' => 'dewi.lestari@gmail.com',
                    'customer_phone' => '0877-4433-2211',
                    'customer_address' => 'Sidoarjo, Jawa Timur',
                    'product_name' => 'PlayStation 5 Slim',
                    'product_slug' => 'playstation-5-slim',
                    'product_image' => 'PlayStation 5 Console.png',
                    'qty' => 1,
                    'start_date' => date('Y-m-d', strtotime('sunday this week')),
                    'end_date' => date('Y-m-d', strtotime('monday next week')),
                    'total_price' => 170000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000001',
                    'customer_name' => 'Budi Hartono',
                    'customer_email' => 'budih@gmail.com',
                    'customer_phone' => '0812-1111-2222',
                    'customer_address' => 'Jakarta Pusat, DKI Jakarta',
                    'product_name' => 'Sony Alpha IV',
                    'product_slug' => 'sony-alpha-iv',
                    'product_image' => 'Sony Alpha A7 IV Camera.png',
                    'qty' => 1,
                    'start_date' => date('Y-m-d', strtotime('monday this week')),
                    'end_date' => date('Y-m-d', strtotime('tuesday this week')),
                    'total_price' => 300000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000002',
                    'customer_name' => 'Ahmad Yani',
                    'customer_email' => 'ahmadyani@gmail.com',
                    'customer_phone' => '0812-3333-4444',
                    'customer_address' => 'Bandung, Jawa Barat',
                    'product_name' => 'MacBook Pro M3',
                    'product_slug' => 'macbook-pro-m3',
                    'product_image' => 'MacBook Pro M3 Space Black.png',
                    'qty' => 1,
                    'start_date' => date('Y-m-d', strtotime('wednesday this week')),
                    'end_date' => date('Y-m-d', strtotime('friday this week')),
                    'total_price' => 500000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000003',
                    'customer_name' => 'Susi Susanti',
                    'customer_email' => 'susis@gmail.com',
                    'customer_phone' => '0812-5555-6666',
                    'customer_address' => 'Semarang, Jawa Tengah',
                    'product_name' => 'PlayStation 5 Slim',
                    'product_slug' => 'playstation-5-slim',
                    'product_image' => 'PlayStation 5 Console.png',
                    'qty' => 1,
                    'start_date' => date('Y-m-d', strtotime('saturday this week')),
                    'end_date' => date('Y-m-d', strtotime('sunday this week')),
                    'total_price' => 85000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000004',
                    'customer_name' => 'Joko Widodo',
                    'customer_email' => 'jokowi@gmail.com',
                    'customer_phone' => '0812-7777-8888',
                    'customer_address' => 'Solo, Jawa Tengah',
                    'product_name' => 'iPhone 15 Pro Max',
                    'product_slug' => 'iphone-15-pro-max',
                    'product_image' => 'iPhone 15 Pro Max Natural Titanium.png',
                    'qty' => 3,
                    'start_date' => date('Y-m-d', strtotime('monday last week')),
                    'end_date' => date('Y-m-d', strtotime('thursday last week')),
                    'total_price' => 1350000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000005',
                    'customer_name' => 'Megawati',
                    'customer_email' => 'megawati@gmail.com',
                    'customer_phone' => '0812-9999-0000',
                    'customer_address' => 'Jakarta Selatan, DKI Jakarta',
                    'product_name' => 'MacBook Pro M3',
                    'product_slug' => 'macbook-pro-m3',
                    'product_image' => 'MacBook Pro M3 Space Black.png',
                    'qty' => 2,
                    'start_date' => date('Y-m-d', strtotime('tuesday 2 weeks ago')),
                    'end_date' => date('Y-m-d', strtotime('friday 2 weeks ago')),
                    'total_price' => 1500000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000006',
                    'customer_name' => 'Prabowo Subianto',
                    'customer_email' => 'prabowo@gmail.com',
                    'customer_phone' => '0813-1111-2222',
                    'customer_address' => 'Hambalang, Jawa Barat',
                    'product_name' => 'PlayStation 5 Slim',
                    'product_slug' => 'playstation-5-slim',
                    'product_image' => 'PlayStation 5 Console.png',
                    'qty' => 4,
                    'start_date' => date('Y-m-d', strtotime('thursday 3 weeks ago')),
                    'end_date' => date('Y-m-d', strtotime('sunday 3 weeks ago')),
                    'total_price' => 1360000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000007',
                    'customer_name' => 'Gibran Rakabuming',
                    'customer_email' => 'gibran@gmail.com',
                    'customer_phone' => '0813-3333-4444',
                    'customer_address' => 'Surakarta, Jawa Tengah',
                    'product_name' => 'Sony Alpha IV',
                    'product_slug' => 'sony-alpha-iv',
                    'product_image' => 'Sony Alpha A7 IV Camera.png',
                    'qty' => 4,
                    'start_date' => date('Y-m-d', strtotime('-1 month')),
                    'end_date' => date('Y-m-d', strtotime('-1 month + 2 days')),
                    'total_price' => 2400000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000008',
                    'customer_name' => 'Anies Baswedan',
                    'customer_email' => 'anies@gmail.com',
                    'customer_phone' => '0813-5555-6666',
                    'customer_address' => 'Jakarta Timur, DKI Jakarta',
                    'product_name' => 'Sony Alpha IV',
                    'product_slug' => 'sony-alpha-iv',
                    'product_image' => 'Sony Alpha A7 IV Camera.png',
                    'qty' => 3,
                    'start_date' => date('Y-m-d', strtotime('-2 months')),
                    'end_date' => date('Y-m-d', strtotime('-2 months + 3 days')),
                    'total_price' => 2700000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000009',
                    'customer_name' => 'Ganjar Pranowo',
                    'customer_email' => 'ganjar@gmail.com',
                    'customer_phone' => '0813-7777-8888',
                    'customer_address' => 'Semarang, Jawa Tengah',
                    'product_name' => 'MacBook Pro M3',
                    'product_slug' => 'macbook-pro-m3',
                    'product_image' => 'MacBook Pro M3 Space Black.png',
                    'qty' => 6,
                    'start_date' => date('Y-m-d', strtotime('-3 months')),
                    'end_date' => date('Y-m-d', strtotime('-3 months + 4 days')),
                    'total_price' => 6000000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000010',
                    'customer_name' => 'Mahfud MD',
                    'customer_email' => 'mahfud@gmail.com',
                    'customer_phone' => '0813-9999-0000',
                    'customer_address' => 'Yogyakarta, DIY',
                    'product_name' => 'iPhone 15 Pro Max',
                    'product_slug' => 'iphone-15-pro-max',
                    'product_image' => 'iPhone 15 Pro Max Natural Titanium.png',
                    'qty' => 5,
                    'start_date' => date('Y-m-d', strtotime('-4 months')),
                    'end_date' => date('Y-m-d', strtotime('-4 months + 3 days')),
                    'total_price' => 2250000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ],
                [
                    'code' => 'TX1000011',
                    'customer_name' => 'Muhaimin Iskandar',
                    'customer_email' => 'cakimin@gmail.com',
                    'customer_phone' => '0814-1111-2222',
                    'customer_address' => 'Sidoarjo, Jawa Timur',
                    'product_name' => 'PlayStation 5 Slim',
                    'product_slug' => 'playstation-5-slim',
                    'product_image' => 'PlayStation 5 Console.png',
                    'qty' => 8,
                    'start_date' => date('Y-m-d', strtotime('-5 months')),
                    'end_date' => date('Y-m-d', strtotime('-5 months + 4 days')),
                    'total_price' => 2720000,
                    'status' => 'Selesai',
                    'remaining_time' => '00 : 00 : 00'
                ]
            ];
            session()->put('admin_transactions', $simulated);
        }

        $allTransactions = collect(session()->get('admin_transactions'));

        $userTransactions = $allTransactions->filter(function ($t) use ($user) {
            return strtolower($t['customer_email']) === strtolower($user->Email);
        });

        if ($userTransactions->isEmpty() && $user->Email !== 'admin@gadgetra.com') {
            $codeActive = 'RNT' . strtoupper(substr(md5(time() . 'active'), 0, 6));
            $codeDone = 'RNT' . strtoupper(substr(md5(time() . 'done'), 0, 6));

            $injected = [
                [
                    'code' => $codeActive,
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

            $allTransactions = $allTransactions->merge($injected);
            session()->put('admin_transactions', $allTransactions->toArray());
            $userTransactions = collect($injected);
        }

        $activeRentals = $userTransactions->filter(function ($t) {
            return $t['status'] === 'Sedang Disewa' || $t['status'] === 'Belum dibayar';
        });

        $completedRentals = $userTransactions->filter(function ($t) {
            return $t['status'] === 'Selesai';
        });

        return view('profile.rentals', compact('user', 'activeRentals', 'completedRentals'));
    }
}
