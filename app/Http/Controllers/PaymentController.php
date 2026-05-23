<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
