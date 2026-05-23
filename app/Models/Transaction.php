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

    public static function createTransaction(array $data, $user = null): self
    {
        $code = 'RNT' . strtoupper(substr(md5(time() . rand()), 0, 6));
        $product = Product::where('slug', $data['product_slug'])->first();

        return self::create([
            'code' => $code,
            'user_id' => $user ? $user->ID : null,
            'product_id' => $product ? $product->id : null,
            'customer_name' => $user ? $user->Nama : 'User',
            'customer_email' => $user ? $user->Email : 'guest@gadgetra.com',
            'customer_phone' => $user ? $user->phone : '0812-3456-7890',
            'customer_address' => $user ? $user->alamat : 'Malang, Jawa Timur',
            'product_name' => $data['product_name'],
            'product_slug' => $data['product_slug'],
            'product_image' => $data['product_image'],
            'qty' => (int)$data['qty'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'total_price' => (int)$data['total_price'],
            'status' => 'Sedang Disewa',
            'remaining_time' => '24 : 00 : 00'
        ]);
    }

    public static function markAsReturned(string $code): bool
    {
        $transaction = self::where('code', $code)->firstOrFail();
        return $transaction->update([
            'status' => 'Selesai',
            'remaining_time' => '00 : 00 : 00'
        ]);
    }
}

