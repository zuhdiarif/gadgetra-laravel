<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro Max',
                'slug' => 'iphone-15-pro-max',
                'description' => 'A17 Pro Chip, Triple Camera 48MP, 256GB, Titanium Frame.',
                'category' => 'Smartphone',
                'price_per_day' => 150000,
                'image' => 'iPhone 15 Pro Max Natural Titanium.png',
                'badge' => 'HOT DEAL',
                'rating' => 4.9,
                'specifications' => [
                    'Chipset' => 'Apple A17 Pro (3 nm)',
                    'Memori' => '256GB NVMe, 8GB RAM',
                    'Kamera Utama' => '48 MP (wide) + 12 MP (periscope telephoto) + 12 MP (ultrawide)',
                    'Layar' => '6.7 inches LTPO Super Retina XDR OLED, 120Hz',
                    'Baterai' => '4441 mAh, 25W fast charging',
                ],
                'conditions' => [
                    'Fisik' => 'Sangat Mulus (98%)',
                    'Fungsi' => '100% Normal',
                    'Kelengkapan' => 'Unit iPhone, Kabel USB-C ke USB-C, Casing pelindung',
                ],
                'stock' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'MacBook Pro M3',
                'slug' => 'macbook-pro-m3',
                'description' => 'Liquid Retina XDR, 16GB RAM, 512GB SSD, Battery up to 22h.',
                'category' => 'Laptop',
                'price_per_day' => 250000,
                'image' => 'MacBook Pro M3 Space Black.png',
                'badge' => null,
                'rating' => 4.8,
                'specifications' => [
                    'Processor' => 'Apple M3 chip (8-core CPU, 10-core GPU)',
                    'RAM' => '16GB Unified Memory',
                    'Penyimpanan' => '512GB SSD',
                    'Layar' => '14.2-inch Liquid Retina XDR display (3024 x 1964)',
                    'Baterai' => 'Hingga 22 jam penggunaan',
                ],
                'conditions' => [
                    'Fisik' => 'Mulus, no dent',
                    'Fungsi' => '100% Normal, Keyboard & Trackpad lancar',
                    'Kelengkapan' => 'Unit MacBook, Apple USB-C Power Adapter, Magsafe 3 Cable, Tas laptop',
                ],
                'stock' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'PlayStation 5 Slim',
                'slug' => 'playstation-5-slim',
                'description' => '4K Gaming, 1TB SSD, DualSense Wireless Controller Included.',
                'category' => 'PS5',
                'price_per_day' => 85000,
                'image' => 'PlayStation 5 Console.png',
                'badge' => null,
                'rating' => 5.0,
                'specifications' => [
                    'CPU' => 'x86-64-AMD Ryzen Zen 2 (8 Cores / 16 Threads)',
                    'GPU' => 'AMD Radeon RDNA 2-based graphics engine',
                    'Penyimpanan' => '1TB Custom SSD',
                    'Output' => 'Support 4K 120Hz TVs, VRR',
                ],
                'conditions' => [
                    'Fisik' => 'Mulus (95%)',
                    'Fungsi' => '100% Normal, tidak gampang overheat',
                    'Kelengkapan' => 'Unit PS5 Slim, 1x DualSense Controller, Kabel HDMI, Kabel Power, 2 Game gratis (di dalam console)',
                ],
                'stock' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Sony Alpha IV',
                'slug' => 'sony-alpha-iv',
                'description' => '33MP Full Frame, 4K 60p Video, Advanced Real-time Eye AF.',
                'category' => 'Kamera',
                'price_per_day' => 300000,
                'image' => 'Sony Alpha A7 IV Camera.png',
                'badge' => null,
                'rating' => 4.7,
                'specifications' => [
                    'Sensor' => '33MP Full-Frame Exmor R CMOS',
                    'Prosesor' => 'BIONZ XR Image Processor',
                    'Perekaman Video' => 'Up to 4K 60p 10-Bit, S-Cinetone',
                    'Autofokus' => '759-Point Fast Hybrid AF, Real-time Eye AF',
                    'ISO Range' => 'ISO 100 - 51200 (Expanded: 50 - 204800)',
                ],
                'conditions' => [
                    'Fisik' => 'Sangat Mulus (99% seperti baru)',
                    'Sensor & Lensa' => 'Bersih bebas jamur/fog',
                    'Fungsi' => '100% Normal',
                    'Kelengkapan' => 'Bodi Kamera Sony A7 IV, Lensa Kit 28-70mm, 2x Baterai Original, Charger, Strap, SD Card 64GB High Speed, Tas Kamera',
                ],
                'stock' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
