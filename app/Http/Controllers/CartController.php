<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', auth()->user()->ID)
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1|max:10',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $userId = auth()->user()->ID;

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $validated['product_id'])
            ->where('start_date', $validated['start_date'])
            ->where('end_date', $validated['end_date'])
            ->first();

        if ($cartItem) {
            $newQty = min(10, $cartItem->qty + $validated['qty']);
            $cartItem->update(['qty' => $newQty]);
        } else {
            Cart::create([
                'user_id'    => $userId,
                'product_id' => $validated['product_id'],
                'qty'        => $validated['qty'],
                'start_date' => $validated['start_date'],
                'end_date'   => $validated['end_date'],
            ]);
        }

        $cartCount = Cart::where('user_id', $userId)->count();

        return response()->json([
            'success'    => true,
            'message'    => 'Produk berhasil ditambahkan ke keranjang!',
            'cart_count' => $cartCount,
        ]);
    }

    public function updateCart(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', auth()->user()->ID)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'qty'        => 'required|integer|min:1|max:10',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $cartItem->update([
            'qty'        => $validated['qty'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui!',
        ]);
    }

    public function deleteCart($id)
    {
        $cartItem = Cart::where('user_id', auth()->user()->ID)
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->delete();

        $cartCount = Cart::where('user_id', auth()->user()->ID)->count();

        return response()->json([
            'success'    => true,
            'message'    => 'Produk berhasil dihapus dari keranjang!',
            'cart_count' => $cartCount,
        ]);
    }
}
