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
}
