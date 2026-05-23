<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function getSimulatedTransactions()
    {
        return [
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
        ];
    }

    private function getTransactions()
    {
        if (!session()->has('admin_transactions')) {
            session()->put('admin_transactions', $this->getSimulatedTransactions());
        }
        return collect(session()->get('admin_transactions'));
    }

    public function dashboard()
    {
        $products = Product::all();
        $transactions = $this->getTransactions();

        $totalProducts = $products->count();
        $totalRented = $transactions->where('status', 'Sedang Disewa')->sum('qty');
        $activeCustomers = $transactions->whereIn('status', ['Sedang Disewa', 'Belum dibayar'])->pluck('customer_name')->unique()->count();
        $availableStock = $products->sum('stock') - $totalRented;
        $totalEarnings = $transactions->where('status', 'Selesai')->sum('total_price') + $transactions->where('status', 'Sedang Disewa')->sum('total_price');
        $projectedEarnings = $transactions->sum('total_price');

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalRented',
            'activeCustomers',
            'availableStock',
            'totalEarnings',
            'projectedEarnings'
        ));
    }

    public function transactions(Request $request)
    {
        $transactions = $this->getTransactions();
        
        $search = $request->input('q');
        if (!empty($search)) {
            $transactions = $transactions->filter(function ($item) use ($search) {
                return Str::contains(strtolower($item['customer_name']), strtolower($search)) || 
                       Str::contains(strtolower($item['product_name']), strtolower($search)) || 
                       Str::contains(strtolower($item['code']), strtolower($search));
            });
        }

        $status = $request->input('status');
        if (!empty($status) && $status !== 'Semua') {
            $transactions = $transactions->where('status', $status);
        }

        $filterType = $request->input('filter_type');
        if ($filterType === 'baru') {
            $transactions = $transactions->sortByDesc('start_date');
        } elseif ($filterType === 'lama') {
            $transactions = $transactions->sortBy('start_date');
        }

        return view('admin.transactions.index', compact('transactions', 'search', 'status', 'filterType'));
    }

    public function transactionDetail($code)
    {
        $transactions = $this->getTransactions();
        $transaction = $transactions->where('code', $code)->first();

        if (!$transaction) {
            abort(404);
        }

        return view('admin.transactions.show', compact('transaction'));
    }

    public function products(Request $request)
    {
        $products = Product::all();
        $search = $request->input('q');
        if (!empty($search)) {
            $products = Product::where('name', 'like', '%' . $search . '%')
                ->orWhere('category', 'like', '%' . $search . '%')
                ->get();
        }

        return view('admin.products.index', compact('products', 'search'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'category' => 'required',
            'price_per_day' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition_fisik' => 'required',
            'condition_fungsi' => 'required',
            'condition_kelengkapan' => 'required',
            'spec_processor' => 'nullable',
            'spec_ram' => 'nullable',
            'spec_storage' => 'nullable',
            'spec_display' => 'nullable',
            'spec_battery' => 'nullable',
            'description' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('photo');
        $filename = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('assets/products'), $filename);

        $specifications = [];
        if ($request->spec_processor) $specifications['Processor'] = $request->spec_processor;
        if ($request->spec_ram) $specifications['RAM'] = $request->spec_ram;
        if ($request->spec_storage) $specifications['Penyimpanan'] = $request->spec_storage;
        if ($request->spec_display) $specifications['Layar'] = $request->spec_display;
        if ($request->spec_battery) $specifications['Baterai'] = $request->spec_battery;

        $conditions = [
            'Fisik' => $request->condition_fisik,
            'Fungsi' => $request->condition_fungsi,
            'Kelengkapan' => $request->condition_kelengkapan
        ];

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'category' => $request->category,
            'price_per_day' => (int)$request->price_per_day,
            'image' => $filename,
            'badge' => null,
            'rating' => 5.0,
            'specifications' => $specifications,
            'conditions' => $conditions,
            'stock' => (int)$request->stock,
            'is_active' => true,
        ]);

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            $imagePath = public_path('assets/products/' . $product->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    public function returns()
    {
        $transactions = $this->getTransactions();
        return view('admin.products.returns', compact('transactions'));
    }

    public function markReturned($code)
    {
        $list = session()->get('admin_transactions', $this->getSimulatedTransactions());
        foreach ($list as &$item) {
            if ($item['code'] === $code) {
                $item['status'] = 'Selesai';
                $item['remaining_time'] = '00 : 00 : 00';
            }
        }
        session()->put('admin_transactions', $list);

        return redirect()->back()->with('success', 'Item marked as returned.');
    }
}
