<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;

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
        $validated = $request->validate([
            'product_slug' => 'required|string|max:255|exists:products,slug',
            'product_name' => 'required|string|max:255',
            'product_image' => 'required|string|max:255',
            'qty'          => 'required|integer|min:1|max:10',
            'start_date'   => 'required|date|after_or_equal:today',
            'end_date'     => 'required|date|after:start_date',
            'total_price'  => 'required|integer|min:1000|max:100000000',
        ], [
            'product_slug.exists' => 'Produk tidak valid.',
            'qty.min'             => 'Jumlah sewa minimal 1.',
            'qty.max'             => 'Jumlah sewa maksimal 10.',
            'start_date.after_or_equal' => 'Tanggal mulai harus hari ini atau setelahnya.',
            'end_date.after'      => 'Tanggal selesai harus setelah tanggal mulai.',
            'total_price.min'     => 'Total harga tidak valid.',
        ]);

        $product = Product::where('slug', $validated['product_slug'])
            ->where('is_active', true)
            ->firstOrFail();

        $expectedPrice = $product->price_per_day * $validated['qty'];
        $days = (int) ceil(
            (strtotime($validated['end_date']) - strtotime($validated['start_date'])) / 86400
        );
        $expectedPrice = $product->price_per_day * $validated['qty'] * max($days, 1);

        if (abs($validated['total_price'] - $expectedPrice) > 1000) {
            return response()->json([
                'success' => false,
                'message' => 'Total harga tidak sesuai. Silakan ulangi proses booking.'
            ], 422);
        }

        $safeData = [
            'product_slug'  => $product->slug,
            'product_name'  => $product->name,
            'product_image' => basename($product->photo),
            'qty'           => $validated['qty'],
            'start_date'    => $validated['start_date'],
            'end_date'      => $validated['end_date'],
            'total_price'   => $expectedPrice,
        ];

        $transaction = Transaction::createTransaction($safeData, auth()->user());

        return response()->json([
            'success' => true,
            'code'    => $transaction->code,
        ]);
    }
}
