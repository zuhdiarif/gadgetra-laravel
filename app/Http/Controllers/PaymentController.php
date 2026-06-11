<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Cart;

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

    public function bookingCode(Request $request)
    {
        $code = $request->query('code');
        $transactions = [];

        if ($code) {
            $codes = explode(',', $code);
            $transactions = Transaction::whereIn('code', $codes)->get();

            $serverKey = env('MIDTRANS_SERVER_KEY');
            if (!empty($serverKey)) {
                foreach ($transactions as $transaction) {
                    if ($transaction->status === 'Belum dibayar' && !empty($transaction->midtrans_order_id)) {
                        try {
                            $response = \Illuminate\Support\Facades\Http::withHeaders([
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                            ])
                            ->withBasicAuth($serverKey, '')
                            ->get("https://api.sandbox.midtrans.com/v2/{$transaction->midtrans_order_id}/status");

                            if ($response->successful()) {
                                $data = $response->json();
                                $transactionStatus = $data['transaction_status'] ?? '';
                                $fraudStatus = $data['fraud_status'] ?? '';
                                $paymentType = $data['payment_type'] ?? '';

                                $status = 'Belum dibayar';
                                if ($transactionStatus === 'capture') {
                                    if ($paymentType === 'credit_card') {
                                        if ($fraudStatus === 'challenge') {
                                            $status = 'Belum dibayar';
                                        } else {
                                            $status = 'Sedang Disewa';
                                        }
                                    }
                                } elseif ($transactionStatus === 'settlement') {
                                    $status = 'Sedang Disewa';
                                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                                    $status = 'Batal';
                                }

                                Transaction::where('midtrans_order_id', $transaction->midtrans_order_id)
                                    ->update(['status' => $status]);
                            }
                        } catch (\Exception $e) {
                        }
                    }
                }
                $transactions = Transaction::whereIn('code', $codes)->get();
            }
        }

        return view('booking.code', compact('transactions'));
    }

    public function storeBooking(Request $request)
    {
        if ($request->input('is_cart') === 'true' || $request->input('is_cart') === true) {
            $items = json_decode($request->input('items'), true);

            if (empty($items) || !is_array($items)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong atau data tidak valid.'
                ], 422);
            }

            $createdTransactions = [];

            foreach ($items as $item) {
                $product = Product::where('slug', $item['product_slug'])
                    ->where('is_active', true)
                    ->first();

                if (!$product) {
                    continue;
                }

                $startTs = strtotime($item['start_date']);
                $endTs = strtotime($item['end_date']);
                $days = (int) floor(($endTs - $startTs) / 86400) + 1;
                $days = max($days, 1);
                $expectedPrice = ($product->price_per_day * $item['qty'] * $days) + 2000;

                $safeData = [
                    'product_slug'  => $product->slug,
                    'product_name'  => $product->name,
                    'product_image' => basename($product->image),
                    'qty'           => $item['qty'],
                    'start_date'    => $item['start_date'],
                    'end_date'      => $item['end_date'],
                    'total_price'   => $expectedPrice,
                ];

                $transaction = Transaction::createTransaction($safeData, auth()->user());
                $createdTransactions[] = $transaction;

                if (!empty($item['cart_id'])) {
                    Cart::where('user_id', auth()->user()->ID)
                        ->where('id', $item['cart_id'])
                        ->delete();
                }
            }

            $codes = array_map(fn($t) => $t->code, $createdTransactions);
            $totalAmount = array_sum(array_map(fn($t) => $t->total_price, $createdTransactions));

            $primaryTransaction = $createdTransactions[0];
            $res = $this->getMidtransSnapToken($primaryTransaction, $totalAmount, $codes);
            $snapToken = is_array($res) ? $res['token'] : $res;
            $redirectUrl = is_array($res) ? ($res['redirect_url'] ?? null) : null;
            $midtransOrderId = is_array($res) ? $res['order_id'] : $primaryTransaction->code;

            foreach ($createdTransactions as $t) {
                $t->update([
                    'payment_token' => $snapToken,
                    'midtrans_order_id' => $midtransOrderId,
                ]);
            }

            return response()->json([
                'success' => true,
                'codes'   => $codes,
                'code'    => $codes[0] ?? '',
                'snap_token' => $snapToken,
                'redirect_url' => $redirectUrl,
            ]);
        }

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

        $serviceFee = 2000;
        $startTs = strtotime($validated['start_date']);
        $endTs   = strtotime($validated['end_date']);
        $days    = (int) floor(($endTs - $startTs) / 86400) + 1;
        $days    = max($days, 1);

        $expectedPrice = ($product->price_per_day * $validated['qty'] * $days) + $serviceFee;

        if (abs($validated['total_price'] - $expectedPrice) > 5000) {
            return response()->json([
                'success' => false,
                'message' => 'Total harga tidak sesuai. Silakan ulangi proses booking.'
            ], 422);
        }

        $safeData = [
            'product_slug'  => $product->slug,
            'product_name'  => $product->name,
            'product_image' => basename($product->image),
            'qty'           => $validated['qty'],
            'start_date'    => $validated['start_date'],
            'end_date'      => $validated['end_date'],
            'total_price'   => $expectedPrice,
        ];

        $transaction = Transaction::createTransaction($safeData, auth()->user());
        $res = $this->getMidtransSnapToken($transaction);
        $snapToken = is_array($res) ? $res['token'] : $res;
        $redirectUrl = is_array($res) ? ($res['redirect_url'] ?? null) : null;
        $midtransOrderId = is_array($res) ? $res['order_id'] : $transaction->code;

        $transaction->update([
            'payment_token' => $snapToken,
            'midtrans_order_id' => $midtransOrderId,
        ]);

        return response()->json([
            'success' => true,
            'code'    => $transaction->code,
            'snap_token' => $snapToken,
            'redirect_url' => $redirectUrl,
        ]);
    }

    public function notification(Request $request)
    {
        $payload = $request->getContent();
        $notification = json_decode($payload, true);

        if (!$notification) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $midtransOrderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';
        $serverKey = env('MIDTRANS_SERVER_KEY');

        $localSignature = hash("sha512", $midtransOrderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $localSignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transactions = Transaction::where('midtrans_order_id', $midtransOrderId)->get();
        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transactionStatus = $notification['transaction_status'] ?? '';
        $paymentType = $notification['payment_type'] ?? '';
        $fraudStatus = $notification['fraud_status'] ?? '';

        $status = 'Belum dibayar';
        if ($transactionStatus === 'capture') {
            if ($paymentType === 'credit_card') {
                if ($fraudStatus === 'challenge') {
                    $status = 'Belum dibayar';
                } else {
                    $status = 'Sedang Disewa';
                }
            }
        } elseif ($transactionStatus === 'settlement') {
            $status = 'Sedang Disewa';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $status = 'Batal';
        }

        foreach ($transactions as $t) {
            $t->status = $status;
            $t->save();
        }

        return response()->json(['message' => 'Notification processed successfully']);
    }

    private function getMidtransSnapToken($transaction, $overrideAmount = null, $codes = null)
    {
        if ($transaction->payment_token) {
            return [
                'token' => $transaction->payment_token,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/' . $transaction->payment_token,
                'order_id' => $transaction->midtrans_order_id ?? $transaction->code
            ];
        }

        $serverKey = env('MIDTRANS_SERVER_KEY');
        if (empty($serverKey)) {
            return [
                'token' => 'mock-token-' . md5($transaction->code),
                'redirect_url' => null,
                'order_id' => $transaction->code
            ];
        }

        try {
            $midtransOrderId = $transaction->code . '-' . time();
            $amount = $overrideAmount ?? $transaction->total_price;
            $codeQuery = $codes ? implode(',', $codes) : $transaction->code;

            $payload = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int)$amount,
                ],
                'customer_details' => [
                    'first_name' => $transaction->customer_name,
                    'email' => $transaction->customer_email,
                    'phone' => $transaction->customer_phone,
                ],
                'enabled_payments' => ['credit_card', 'gopay', 'shopeepay', 'bca_va', 'bni_va', 'bri_va', 'indomaret', 'alfamart'],
                'callbacks' => [
                    'finish' => route('booking.code', ['code' => $codeQuery]),
                    'unfinish' => route('payment.method'),
                    'error' => route('payment.method')
                ]
            ];

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->withBasicAuth($serverKey, '')
            ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $payload);

            if ($response->successful()) {
                $token = $response->json()['token'] ?? null;
                $redirectUrl = $response->json()['redirect_url'] ?? null;
                if ($token) {
                    return [
                        'token' => $token,
                        'redirect_url' => $redirectUrl,
                        'order_id' => $midtransOrderId
                    ];
                }
            }
        } catch (\Exception $e) {
        }

        return [
            'token' => 'mock-token-' . md5($transaction->code),
            'redirect_url' => null,
            'order_id' => $transaction->code
        ];
    }
}
