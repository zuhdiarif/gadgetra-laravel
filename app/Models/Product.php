<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'price_per_day',
        'image',
        'badge',
        'rating',
        'specifications',
        'conditions',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'specifications' => 'array',
        'conditions' => 'array',
        'price_per_day' => 'integer',
        'rating' => 'float',
        'is_active' => 'boolean',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            $path = str_starts_with($this->image, 'products/') ? $this->image : 'products/' . $this->image;
            return asset('assets/' . $path);
        }
        return '';
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_per_day >= 1000) {
            return 'Rp ' . number_format($this->price_per_day / 1000, 0) . 'rb';
        }
        return 'Rp ' . number_format($this->price_per_day, 0, ',', '.');
    }

    public static function addProduct(array $data, $photoFile): self
    {
        $filename = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $photoFile->getClientOriginalExtension();
        $photoFile->move(public_path('assets/products'), $filename);

        $specifications = [];
        if (!empty($data['spec_processor'])) {
            $specifications['Processor'] = $data['spec_processor'];
        }
        if (!empty($data['spec_ram'])) {
            $specifications['RAM'] = $data['spec_ram'];
        }
        if (!empty($data['spec_storage'])) {
            $specifications['Penyimpanan'] = $data['spec_storage'];
        }
        if (!empty($data['spec_display'])) {
            $specifications['Layar'] = $data['spec_display'];
        }
        if (!empty($data['spec_battery'])) {
            $specifications['Baterai'] = $data['spec_battery'];
        }

        $conditions = [
            'Fisik' => $data['condition_fisik'] ?? null,
            'Fungsi' => $data['condition_fungsi'] ?? null,
            'Kelengkapan' => $data['condition_kelengkapan'] ?? null
        ];

        return self::create([
            'name' => $data['name'],
            'slug' => \Illuminate\Support\Str::slug($data['name']),
            'description' => $data['description'],
            'category' => $data['category'],
            'price_per_day' => (int)$data['price_per_day'],
            'image' => $filename,
            'badge' => null,
            'rating' => 5.0,
            'specifications' => $specifications,
            'conditions' => $conditions,
            'stock' => (int)$data['stock'],
            'is_active' => true,
        ]);
    }

    public function deleteProduct(): bool
    {
        if ($this->image) {
            $imagePath = public_path('assets/products/' . $this->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        return $this->delete();
    }
}

