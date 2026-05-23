<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'product_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'product_name',
        'product_slug',
        'product_image',
        'qty',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'remaining_time'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'ID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
