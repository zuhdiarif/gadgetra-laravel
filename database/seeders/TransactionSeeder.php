<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Transaction::truncate();
        Schema::enableForeignKeyConstraints();

        $customers = [
            [
                'name' => 'Budiono Siregar',
                'email' => 'Budi01R@gmail.com',
                'phone' => '0812-3456-7890',
                'address' => 'Malang Kota, Jawa Timur'
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'sitiaminah@gmail.com',
                'phone' => '0821-9876-5432',
                'address' => 'Surabaya, Jawa Timur'
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => 'rianh@gmail.com',
                'phone' => '0813-5555-8888',
                'address' => 'Batu, Jawa Timur'
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'phone' => '0877-4433-2211',
                'address' => 'Sidoarjo, Jawa Timur'
            ],
            [
                'name' => 'Budi Hartono',
                'email' => 'budih@gmail.com',
                'phone' => '0812-1111-2222',
                'address' => 'Jakarta Pusat, DKI Jakarta'
            ],
            [
                'name' => 'Ahmad Yani',
                'email' => 'ahmadyani@gmail.com',
                'phone' => '0812-3333-4444',
                'address' => 'Bandung, Jawa Barat'
            ],
            [
                'name' => 'Susi Susanti',
                'email' => 'susis@gmail.com',
                'phone' => '0812-5555-6666',
                'address' => 'Semarang, Jawa Tengah'
            ],
            [
                'name' => 'Joko Widodo',
                'email' => 'jokowi@gmail.com',
                'phone' => '0812-7777-8888',
                'address' => 'Solo, Jawa Tengah'
            ],
            [
                'name' => 'Megawati',
                'email' => 'megawati@gmail.com',
                'phone' => '0812-9999-0000',
                'address' => 'Jakarta Selatan, DKI Jakarta'
            ],
            [
                'name' => 'Prabowo Subianto',
                'email' => 'prabowo@gmail.com',
                'phone' => '0813-1111-2222',
                'address' => 'Hambalang, Jawa Barat'
            ],
            [
                'name' => 'Gibran Rakabuming',
                'email' => 'gibran@gmail.com',
                'phone' => '0813-3333-4444',
                'address' => 'Surakarta, Jawa Tengah'
            ],
            [
                'name' => 'Anies Baswedan',
                'email' => 'anies@gmail.com',
                'phone' => '0813-5555-6666',
                'address' => 'Jakarta Timur, DKI Jakarta'
            ],
            [
                'name' => 'Ganjar Pranowo',
                'email' => 'ganjar@gmail.com',
                'phone' => '0813-7777-8888',
                'address' => 'Semarang, Jawa Tengah'
            ],
            [
                'name' => 'Mahfud MD',
                'email' => 'mahfud@gmail.com',
                'phone' => '0813-9999-0000',
                'address' => 'Yogyakarta, DIY'
            ],
            [
                'name' => 'Muhaimin Iskandar',
                'email' => 'cakimin@gmail.com',
                'phone' => '0814-1111-2222',
                'address' => 'Sidoarjo, Jawa Timur'
            ]
        ];

        foreach ($customers as $c) {
            User::updateOrCreate(
                ['Email' => $c['email']],
                [
                    'Nama' => $c['name'],
                    'password' => Hash::make('user123'),
                    'umur' => rand(20, 50),
                    'tempat_lahir' => 'Indonesia',
                    'phone' => $c['phone'],
                    'alamat' => $c['address'],
                ]
            );
        }

        $items = [
            [
                'code' => 'TYZ10CH6U',
                'email' => 'Budi01R@gmail.com',
                'product_slug' => 'sony-alpha-iv',
                'qty' => 2,
                'start_date' => date('Y-m-d', strtotime('tuesday this week')),
                'end_date' => date('Y-m-d', strtotime('wednesday this week')),
                'total_price' => 600000,
                'status' => 'Sedang Disewa',
                'remaining_time' => '30 : 42 : 12'
            ],
            [
                'code' => 'KJD93HJ2A',
                'email' => 'sitiaminah@gmail.com',
                'product_slug' => 'macbook-pro-m3',
                'qty' => 1,
                'start_date' => date('Y-m-d', strtotime('thursday this week')),
                'end_date' => date('Y-m-d', strtotime('saturday this week')),
                'total_price' => 500000,
                'status' => 'Belum dibayar',
                'remaining_time' => '48 : 00 : 00'
            ],
            [
                'code' => 'LQM48PL7B',
                'email' => 'rianh@gmail.com',
                'product_slug' => 'iphone-15-pro-max',
                'qty' => 1,
                'start_date' => date('Y-m-d', strtotime('friday this week')),
                'end_date' => date('Y-m-d', strtotime('saturday this week')),
                'total_price' => 150000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'XPW32NZ4C',
                'email' => 'dewi.lestari@gmail.com',
                'product_slug' => 'playstation-5-slim',
                'qty' => 1,
                'start_date' => date('Y-m-d', strtotime('sunday this week')),
                'end_date' => date('Y-m-d', strtotime('monday next week')),
                'total_price' => 170000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000001',
                'email' => 'budih@gmail.com',
                'product_slug' => 'sony-alpha-iv',
                'qty' => 1,
                'start_date' => date('Y-m-d', strtotime('monday this week')),
                'end_date' => date('Y-m-d', strtotime('tuesday this week')),
                'total_price' => 300000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000002',
                'email' => 'ahmadyani@gmail.com',
                'product_slug' => 'macbook-pro-m3',
                'qty' => 1,
                'start_date' => date('Y-m-d', strtotime('wednesday this week')),
                'end_date' => date('Y-m-d', strtotime('friday this week')),
                'total_price' => 500000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000003',
                'email' => 'susis@gmail.com',
                'product_slug' => 'playstation-5-slim',
                'qty' => 1,
                'start_date' => date('Y-m-d', strtotime('saturday this week')),
                'end_date' => date('Y-m-d', strtotime('sunday this week')),
                'total_price' => 85000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000004',
                'email' => 'jokowi@gmail.com',
                'product_slug' => 'iphone-15-pro-max',
                'qty' => 3,
                'start_date' => date('Y-m-d', strtotime('monday last week')),
                'end_date' => date('Y-m-d', strtotime('thursday last week')),
                'total_price' => 1350000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000005',
                'email' => 'megawati@gmail.com',
                'product_slug' => 'macbook-pro-m3',
                'qty' => 2,
                'start_date' => date('Y-m-d', strtotime('tuesday 2 weeks ago')),
                'end_date' => date('Y-m-d', strtotime('friday 2 weeks ago')),
                'total_price' => 1500000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000006',
                'email' => 'prabowo@gmail.com',
                'product_slug' => 'playstation-5-slim',
                'qty' => 4,
                'start_date' => date('Y-m-d', strtotime('thursday 3 weeks ago')),
                'end_date' => date('Y-m-d', strtotime('sunday 3 weeks ago')),
                'total_price' => 1360000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000007',
                'email' => 'gibran@gmail.com',
                'product_slug' => 'sony-alpha-iv',
                'qty' => 4,
                'start_date' => date('Y-m-d', strtotime('-1 month')),
                'end_date' => date('Y-m-d', strtotime('-1 month + 2 days')),
                'total_price' => 2400000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000008',
                'email' => 'anies@gmail.com',
                'product_slug' => 'sony-alpha-iv',
                'qty' => 3,
                'start_date' => date('Y-m-d', strtotime('-2 months')),
                'end_date' => date('Y-m-d', strtotime('-2 months + 3 days')),
                'total_price' => 2700000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000009',
                'email' => 'ganjar@gmail.com',
                'product_slug' => 'macbook-pro-m3',
                'qty' => 6,
                'start_date' => date('Y-m-d', strtotime('-3 months')),
                'end_date' => date('Y-m-d', strtotime('-3 months + 4 days')),
                'total_price' => 6000000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000010',
                'email' => 'mahfud@gmail.com',
                'product_slug' => 'iphone-15-pro-max',
                'qty' => 5,
                'start_date' => date('Y-m-d', strtotime('-4 months')),
                'end_date' => date('Y-m-d', strtotime('-4 months + 3 days')),
                'total_price' => 2250000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ],
            [
                'code' => 'TX1000011',
                'email' => 'cakimin@gmail.com',
                'product_slug' => 'playstation-5-slim',
                'qty' => 8,
                'start_date' => date('Y-m-d', strtotime('-5 months')),
                'end_date' => date('Y-m-d', strtotime('-5 months + 4 days')),
                'total_price' => 2720000,
                'status' => 'Selesai',
                'remaining_time' => '00 : 00 : 00'
            ]
        ];

        foreach ($items as $item) {
            $user = User::where('Email', $item['email'])->first();
            $product = Product::where('slug', $item['product_slug'])->first();

            Transaction::create([
                'code' => $item['code'],
                'user_id' => $user ? $user->ID : null,
                'product_id' => $product ? $product->id : null,
                'customer_name' => $user ? $user->Nama : 'User',
                'customer_email' => $item['email'],
                'customer_phone' => $user ? $user->phone : null,
                'customer_address' => $user ? $user->alamat : null,
                'product_name' => $product ? $product->name : 'Unknown Product',
                'product_slug' => $item['product_slug'],
                'product_image' => $product ? $product->image : null,
                'qty' => $item['qty'],
                'start_date' => $item['start_date'],
                'end_date' => $item['end_date'],
                'total_price' => $item['total_price'],
                'status' => $item['status'],
                'remaining_time' => $item['remaining_time']
            ]);
        }
    }
}
