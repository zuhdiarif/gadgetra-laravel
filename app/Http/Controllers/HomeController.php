<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');
        $query = Product::where('is_active', true);
        if ($category) {
            $query->where('category', $category);
        }
        $products = $query->get();

        $categories = [
            ['name' => 'Smartphone', 'icon' => 'icons/IconSmartphone.png'],
            ['name' => 'Laptop', 'icon' => 'icons/iconlaptop.png'],
            ['name' => 'Kamera', 'icon' => 'icons/iconcamera.png'],
            ['name' => 'Konsol Game', 'icon' => 'icons/iconps5.png'],
        ];

        return view('home.index', compact('products', 'categories'));
    }
}
