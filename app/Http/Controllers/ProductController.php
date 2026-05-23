<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function detail(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $reviews = [
            [
                'name' => 'Budi Santoso',
                'rating' => 5,
                'text' => 'Kamera sangat bersih, autofocus deteksi matanya super cepat. Paling suka karena baterainya dikasih dua, jadi aman sewa seharian penuh. Recommended seller!',
            ],
            [
                'name' => 'Clara Angelica',
                'rating' => 4,
                'text' => 'Sewa buat kebutuhan bikin video cinematic portofolio. Warna 10-bit S-Cinetone-nya cantik banget! Proses sewa cepat dan gak ribet.',
            ],
        ];

        $recentRentals = [
            ['name' => 'MacBook Pro 16"', 'image' => 'products/MacBook Pro M3 Space Black.png', 'status' => 'returned', 'label' => 'Returned • Oct 12'],
            ['name' => 'DJI Mavic 3 Pro', 'image' => 'products/DJI Mavic 3 Pro.png', 'status' => 'returned', 'label' => 'Returned • Sep 28'],
        ];

        return view('products.detail', compact('product', 'reviews', 'recentRentals'));
    }
}
