<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!$request->user() || !$request->user()->isAdmin()) {
                abort(403, 'Unauthorized.');
            }
            return $next($request);
        });
    }

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

        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('sunday this week'));

        $dailyEarnings = [0, 0, 0, 0, 0, 0, 0];
        $dailyRentals = [0, 0, 0, 0, 0, 0, 0];

        foreach ($transactions as $t) {
            $tDate = date('Y-m-d', strtotime($t['start_date']));
            if ($tDate >= $startOfWeek && $tDate <= $endOfWeek) {
                $dayOfWeek = (int)date('N', strtotime($t['start_date']));
                $dailyEarnings[$dayOfWeek - 1] += $t['total_price'];
                $dailyRentals[$dayOfWeek - 1] += $t['qty'];
            }
        }

        $currentMonthStart = date('Y-m-01');
        $currentMonthEnd = date('Y-m-t');

        $weeklyEarnings = [0, 0, 0, 0];
        $weeklyRentals = [0, 0, 0, 0];

        foreach ($transactions as $t) {
            $tDate = date('Y-m-d', strtotime($t['start_date']));
            if ($tDate >= $currentMonthStart && $tDate <= $currentMonthEnd) {
                $dayOfMonth = (int)date('j', strtotime($t['start_date']));
                if ($dayOfMonth <= 7) {
                    $weekIdx = 0;
                } elseif ($dayOfMonth <= 14) {
                    $weekIdx = 1;
                } elseif ($dayOfMonth <= 21) {
                    $weekIdx = 2;
                } else {
                    $weekIdx = 3;
                }
                $weeklyEarnings[$weekIdx] += $t['total_price'];
                $weeklyRentals[$weekIdx] += $t['qty'];
            }
        }

        $monthsList = [];
        $monthKeys = [];
        $monthNamesIndonesian = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        for ($i = 5; $i >= 0; $i--) {
            $time = strtotime("-{$i} months");
            $monthKey = date('Y-m', $time);
            $monthNum = date('m', $time);
            $monthsList[] = $monthNamesIndonesian[$monthNum];
            $monthKeys[] = $monthKey;
        }

        $monthlyEarnings = array_fill(0, 6, 0);
        $monthlyRentals = array_fill(0, 6, 0);

        foreach ($transactions as $t) {
            $tMonthKey = date('Y-m', strtotime($t['start_date']));
            $index = array_search($tMonthKey, $monthKeys);
            if ($index !== false) {
                $monthlyEarnings[$index] += $t['total_price'];
                $monthlyRentals[$index] += $t['qty'];
            }
        }

        $chartData = [
            'hari' => [
                'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                'earnings' => $dailyEarnings,
                'rentals' => $dailyRentals,
            ],
            'minggu' => [
                'labels' => ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                'earnings' => $weeklyEarnings,
                'rentals' => $weeklyRentals,
            ],
            'bulan' => [
                'labels' => $monthsList,
                'earnings' => $monthlyEarnings,
                'rentals' => $monthlyRentals,
            ]
        ];

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalRented',
            'activeCustomers',
            'availableStock',
            'totalEarnings',
            'projectedEarnings',
            'chartData'
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
