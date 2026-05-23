<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate();

        $originalProducts = [
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
                'stock' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'MacBook Pro M3',
                'slug' => 'macbook-pro-m3',
                'description' => 'Liquid Retina XDR, 16GB RAM, 512GB SSD, Battery up to 22h.',
                'category' => 'Laptop',
                'price_per_day' => 250000,
                'image' => 'MacBook Pro M3 Space Black.png',
                'badge' => 'POPULER',
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
                'stock' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'PlayStation 5 Slim',
                'slug' => 'playstation-5-slim',
                'description' => '4K Gaming, 1TB SSD, DualSense Wireless Controller Included.',
                'category' => 'Konsol Game',
                'price_per_day' => 85000,
                'image' => 'PlayStation 5 Console.png',
                'badge' => 'PREMIUM',
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
                'stock' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Sony Alpha IV',
                'slug' => 'sony-alpha-iv',
                'description' => '33MP Full Frame, 4K 60p Video, Advanced Real-time Eye AF.',
                'category' => 'Kamera',
                'price_per_day' => 300000,
                'image' => 'Sony Alpha A7 IV Camera.png',
                'badge' => 'REKOMENDASI',
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
                'stock' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($originalProducts as $product) {
            Product::create($product);
        }

        $phoneBrands = ['iPhone', 'Samsung Galaxy', 'Google Pixel', 'Xiaomi', 'Oppo', 'Vivo', 'Realme', 'OnePlus', 'Asus ROG Phone'];
        $phoneModels = ['Pro Max', 'Ultra', 'Pro', 'Neo', 'GT', 'Fold', 'Flip', 'S', 'X'];
        for ($i = 1; $i <= 99; $i++) {
            $brand = $phoneBrands[$i % count($phoneBrands)];
            $model = $phoneModels[($i + 3) % count($phoneModels)];
            $name = "{$brand} S" . (21 + ($i % 5)) . " {$model} " . chr(65 + ($i % 26)) . $i;
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Premium smartphone {$brand} with advanced features, powerful performance, and high refresh rate display.",
                'category' => 'Smartphone',
                'price_per_day' => 80000 + (($i % 8) * 10000),
                'image' => 'iPhone 15 Pro Max Natural Titanium.png',
                'badge' => $i % 10 === 0 ? 'HOT DEAL' : null,
                'rating' => 4.5 + (($i % 6) * 0.1),
                'specifications' => [
                    'Chipset' => 'High-tier Octa-core 3.3 GHz Processor',
                    'Memori' => '256GB / 512GB UFS 4.0, 12GB RAM',
                    'Layar' => '6.7-inch AMOLED, 120Hz HDR10+',
                    'Baterai' => '5000 mAh, 67W Fast Charging'
                ],
                'conditions' => [
                    'Fisik' => 'Mulus (95-98%)',
                    'Fungsi' => '100% Berfungsi Normal',
                    'Kelengkapan' => 'Unit Smartphone, Kabel Charger, Adapter'
                ],
                'stock' => 5 + ($i % 10),
                'is_active' => true
            ]);
        }

        $laptopBrands = ['Asus ROG Zephyrus', 'Asus Zenbook', 'Dell XPS', 'Lenovo ThinkPad', 'HP Spectre', 'Acer Predator', 'Razer Blade', 'Lenovo Legion'];
        $laptopModels = ['Ultra', 'Pro', 'Carbon', 'X', 'Edition', 'Slim', 'Dual Screen'];
        for ($i = 1; $i <= 99; $i++) {
            $brand = $laptopBrands[$i % count($laptopBrands)];
            $model = $laptopModels[($i + 2) % count($laptopModels)];
            $name = "{$brand} {$model} " . (14 + ($i % 3)) . " " . chr(65 + ($i % 26)) . $i;
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "High-end laptop {$brand} designed for intensive productivity, programming, creative work, and gaming.",
                'category' => 'Laptop',
                'price_per_day' => 150000 + (($i % 12) * 10000),
                'image' => 'MacBook Pro M3 Space Black.png',
                'badge' => $i % 8 === 0 ? 'WORKSTATION' : null,
                'rating' => 4.4 + (($i % 7) * 0.1),
                'specifications' => [
                    'Processor' => 'Intel Core i7/i9 14th Gen / AMD Ryzen 9',
                    'RAM' => '16GB / 32GB DDR5 Dual Channel',
                    'Penyimpanan' => '512GB / 1TB NVMe PCIe 4.0 SSD',
                    'Layar' => 'WQXGA OLED 120Hz IPS'
                ],
                'conditions' => [
                    'Fisik' => 'Kondisi Mulus, No Dent',
                    'Fungsi' => 'Layar bersih, keyboard, trackpad 100% normal',
                    'Kelengkapan' => 'Unit Laptop, Charger Adapter, Tas Laptop'
                ],
                'stock' => 3 + ($i % 6),
                'is_active' => true
            ]);
        }

        $cameraBrands = ['Canon EOS', 'Nikon Z', 'Fujifilm X-T', 'Panasonic Lumix', 'Fujifilm GFX', 'Leica Q', 'Sony Alpha'];
        $cameraModels = ['Mark II', 'Mark III', 'Pro', 'Creator Edition', 'Cinema Line'];
        for ($i = 1; $i <= 99; $i++) {
            $brand = $cameraBrands[$i % count($cameraBrands)];
            $model = $cameraModels[($i + 1) % count($cameraModels)];
            $name = "{$brand} " . (7 + ($i % 4)) . " {$model} " . chr(65 + ($i % 26)) . $i;
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Professional camera {$brand} with outstanding resolution, fast autofocus, and exceptional low-light capabilities.",
                'category' => 'Kamera',
                'price_per_day' => 200000 + (($i % 15) * 10000),
                'image' => 'Sony Alpha A7 IV Camera.png',
                'badge' => $i % 12 === 0 ? 'REKOMENDASI' : null,
                'rating' => 4.6 + (($i % 5) * 0.1),
                'specifications' => [
                    'Sensor' => 'Full-Frame / APS-C CMOS Sensor',
                    'Autofokus' => 'Hybrid AF with Eye & Animal Detection tracking',
                    'Video' => '4K 60p 10-Bit internal capture',
                    'ISO' => 'ISO 100 - 51200'
                ],
                'conditions' => [
                    'Fisik' => 'Mulus terawat, sensor bersih bebas jamur',
                    'Fungsi' => 'Mekanikal shutter & elektronik 100% normal',
                    'Kelengkapan' => 'Bodi Kamera, Lensa Prime/Zoom, Baterai, Charger, Tas'
                ],
                'stock' => 2 + ($i % 5),
                'is_active' => true
            ]);
        }

        $consoleBrands = ['Nintendo Switch OLED', 'Xbox Series X', 'Steam Deck OLED', 'ROG Ally Extreme', 'PlayStation 5 Digital', 'Oculus Quest 3'];
        for ($i = 1; $i <= 99; $i++) {
            $brand = $consoleBrands[$i % count($consoleBrands)];
            $name = "{$brand} Console Edition " . chr(65 + ($i % 26)) . $i;
            Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Entertainment game console {$brand} for premium console gaming at home or on-the-go.",
                'category' => 'Konsol Game',
                'price_per_day' => 50000 + (($i % 6) * 10000),
                'image' => 'PlayStation 5 Console.png',
                'badge' => $i % 10 === 0 ? 'BEST VALUE' : null,
                'rating' => 4.7 + (($i % 4) * 0.1),
                'specifications' => [
                    'CPU/GPU' => 'Custom high-performance architecture CPU & GPU',
                    'Penyimpanan' => '512GB / 1TB High Speed Storage',
                    'Output' => 'Support high refresh rate output, HDR'
                ],
                'conditions' => [
                    'Fisik' => 'Kondisi Bagus terawat',
                    'Fungsi' => 'Console & Controller normal lancar',
                    'Kelengkapan' => 'Unit Console, 1x Controller, Kabel HDMI, Kabel Charger'
                ],
                'stock' => 4 + ($i % 8),
                'is_active' => true
            ]);
        }
    }
}
