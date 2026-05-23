<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function method()
    {
        $paymentMethods = [
            ['name' => 'BCA Virtual Account', 'logo' => 'icons/Logo-BCA.png'],
            ['name' => 'QRIS', 'logo' => 'icons/logo-qris.png'],
            ['name' => 'BNI Virtual Account', 'logo' => 'icons/bank-bni-logo.png'],
            ['name' => 'Mandiri Virtual Account', 'logo' => 'icons/logo-bank-mandiri.png'],
        ];
        return view('payment.method', compact('paymentMethods'));
    }

    public function instruction()
    {
        return view('payment.instruction');
    }

    public function bookingCode()
    {
        return view('booking.code');
    }

    public function storeBooking(Request $request)
    {
        $code = 'RNT' . strtoupper(substr(md5(time() . rand()), 0, 6));

        $user = auth()->user();
        $product = Product::where('slug', $request->input('product_slug'))->first();

        Transaction::create([
            'code' => $code,
            'user_id' => $user ? $user->ID : null,
            'product_id' => $product ? $product->id : null,
            'customer_name' => $user ? $user->Nama : 'User',
            'customer_email' => $user ? $user->Email : 'guest@gadgetra.com',
            'customer_phone' => $user ? $user->phone : '0812-3456-7890',
            'customer_address' => $user ? $user->alamat : 'Malang, Jawa Timur',
            'product_name' => $request->input('product_name'),
            'product_slug' => $request->input('product_slug'),
            'product_image' => $request->input('product_image'),
            'qty' => (int)$request->input('qty'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'total_price' => (int)$request->input('total_price'),
            'status' => 'Sedang Disewa',
            'remaining_time' => '24 : 00 : 00'
        ]);

        return response()->json(['success' => true]);
    }
}
