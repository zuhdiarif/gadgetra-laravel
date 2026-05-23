<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();
        $categories = [
            ['name' => 'Smartphone', 'icon' => 'icons/IconSmartphone.png'],
            ['name' => 'Laptop', 'icon' => 'icons/iconlaptop.png'],
            ['name' => 'PS5', 'icon' => 'icons/iconps5.png'],
            ['name' => 'Kamera', 'icon' => 'icons/iconcamera.png'],
        ];
        return view('home.index', compact('products', 'categories'));
    }
}
