<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;
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

    private function getTransactions()
    {
        return Transaction::all();
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

        Product::addProduct($request->all(), $request->file('photo'));

        return redirect()->route('admin.products')->with('success', 'Product created successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->deleteProduct();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully.');
    }

    public function returns()
    {
        $transactions = $this->getTransactions();
        return view('admin.products.returns', compact('transactions'));
    }

    public function markReturned($code)
    {
        Transaction::markAsReturned($code);
        return redirect()->back()->with('success', 'Item marked as returned.');
    }
}
