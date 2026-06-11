<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TransactionModelTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_transaction()
    {
        $product = Product::first();
        if (!$product) {
            $this->markTestSkipped('No products in database to test.');
        }

        $user = User::find(1);

        $data = [
            'product_slug'  => $product->slug,
            'product_name'  => $product->name,
            'product_image' => 'sony.png',
            'qty'           => 1,
            'start_date'    => '2026-06-11',
            'end_date'      => '2026-06-13',
            'total_price'   => 900000,
        ];

        $transaction = Transaction::createTransaction($data, $user);

        $expectedStatus = env('MIDTRANS_SERVER_KEY') ? 'Belum dibayar' : 'Sedang Disewa';

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertStringStartsWith('RNT', $transaction->code);
        $this->assertEquals($user->ID, $transaction->user_id);
        $this->assertEquals($product->id, $transaction->product_id);
        $this->assertEquals($user->Nama, $transaction->customer_name);
        $this->assertEquals($user->Email, $transaction->customer_email);
        $this->assertEquals($expectedStatus, $transaction->status);
        $this->assertEquals('24 : 00 : 00', $transaction->remaining_time);
    }

    public function test_mark_as_returned()
    {
        $product = Product::first();
        if (!$product) {
            $this->markTestSkipped('No products in database to test.');
        }

        $data = [
            'product_slug'  => $product->slug,
            'product_name'  => $product->name,
            'product_image' => 'sony.png',
            'qty'           => 1,
            'start_date'    => '2026-06-11',
            'end_date'      => '2026-06-13',
            'total_price'   => 900000,
        ];

        $transaction = Transaction::createTransaction($data);

        $expectedStatus = env('MIDTRANS_SERVER_KEY') ? 'Belum dibayar' : 'Sedang Disewa';

        $this->assertEquals($expectedStatus, $transaction->status);

        Transaction::markAsReturned($transaction->code);

        $freshTransaction = Transaction::where('code', $transaction->code)->first();
        $this->assertEquals('Selesai', $freshTransaction->status);
        $this->assertEquals('00 : 00 : 00', $freshTransaction->remaining_time);
    }
}
