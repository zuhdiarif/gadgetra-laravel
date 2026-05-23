<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function method()
    {
        $paymentMethods = [
            ['name' => 'BCA Virtual Account', 'logo' => 'icons/Logo-BCA.png'],
            ['name' => 'QRIS', 'logo' => 'icons/logo-qris.png'],
            ['name' => 'BNI Virtual Account', 'logo' => 'icons/bank-bni-logo.png'],
            ['name' => 'Mandiri Virtual Account', 'logo' => 'icons/logo-bank-mandiri.png'],
        ];
        return view('payment.method', compact('paymentMethods'));
    }

    public function instruction()
    {
        return view('payment.instruction');
    }

    public function bookingCode()
    {
        return view('booking.code');
    }

    public function storeBooking(Request $request)
    {
        $transactions = session()->get('admin_transactions', [
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
                'start_date' => '2026-11-22',
                'end_date' => '2026-11-23',
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
                'start_date' => '2026-11-24',
                'end_date' => '2026-11-26',
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
                'start_date' => '2026-11-20',
                'end_date' => '2026-11-21',
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
                'start_date' => '2026-11-18',
                'end_date' => '2026-11-20',
                'total_price' => 170000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ]
        ]);

        $code = 'RNT' . strtoupper(substr(md5(time() . rand()), 0, 6));

        $user = auth()->user();

        $newTransaction = [
            'code' => $code,
            'customer_name' => $user->Nama ?? 'User',
            'customer_email' => $user->Email,
            'customer_phone' => $user->phone ?? '0812-3456-7890',
            'customer_address' => $user->alamat ?? 'Malang, Jawa Timur',
            'product_name' => $request->input('product_name'),
            'product_slug' => $request->input('product_slug'),
            'product_image' => $request->input('product_image'),
            'qty' => (int)$request->input('qty'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'total_price' => (int)$request->input('total_price'),
            'status' => 'Sedang Disewa',
            'remaining_time' => '24 : 00 : 00'
        ];

        $transactions[] = $newTransaction;
        session()->put('admin_transactions', $transactions);

        return response()->json(['success' => true]);
    }
}
