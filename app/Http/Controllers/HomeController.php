<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');

        if ($category) {
            $products = Product::where('is_active', true)
                ->where('category', $category)
                ->get();
        } else {
            $topProductIds = Transaction::selectRaw('product_id, SUM(qty) as total_rented')
                ->whereNotNull('product_id')
                ->groupBy('product_id')
                ->orderByDesc('total_rented')
                ->limit(5)
                ->pluck('product_id');

            if ($topProductIds->isNotEmpty()) {
                $products = Product::where('is_active', true)
                    ->whereIn('id', $topProductIds)
                    ->get()
                    ->sortBy(fn($p) => array_search($p->id, $topProductIds->toArray()))
                    ->values();
            } else {
                $products = Product::where('is_active', true)
                    ->orderByDesc('rating')
                    ->limit(5)
                    ->get();
            }
        }

        $categories = [
            ['name' => 'Smartphone', 'icon' => 'icons/IconSmartphone.png'],
            ['name' => 'Laptop', 'icon' => 'icons/iconlaptop.png'],
            ['name' => 'Kamera', 'icon' => 'icons/iconcamera.png'],
            ['name' => 'Konsol Game', 'icon' => 'icons/iconps5.png'],
        ];

        return view('home.index', compact('products', 'categories', 'category'));
    }
}
