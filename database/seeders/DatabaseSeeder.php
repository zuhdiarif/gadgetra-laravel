<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['Email' => 'admin@gadgetra.com'],
            [
                'Nama' => 'Admin Gadgetra',
                'password' => Hash::make('admin123'),
                'umur' => 28,
                'tempat_lahir' => 'Malang',
                'phone' => '081234567890',
                'phone_keluarga' => '081234567891',
                'alamat' => 'Jl. Kawi No. 1077, Malang',
            ]
        );

        $this->call([
            ProductSeeder::class,
        ]);
    }
}
