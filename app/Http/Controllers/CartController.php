<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::where('customer_id', $user->user_id)->with('cartItems.product')->first();

        return view('cart.index', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['customer_id' => $user->user_id]);

        $cartItem = CartItem::where('cart_id', $cart->cart_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock) {
            return back()->withErrors([
                'quantity' => 'Jumlah pesanan melebihi stok yang tersedia.',
            ])->withInput();
        }

        $user = Auth::user();
        $cart = Cart::firstOrCreate(['customer_id' => $user->user_id]);

        $request->session()->forget('checkout_cart_item_id');
        CartItem::where('cart_id', $cart->cart_id)->delete();
        CartItem::create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product->product_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('checkout.index');
    }

    public function checkoutSelected(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|integer',
        ]);

        $cart = Cart::where('customer_id', Auth::user()->user_id)->firstOrFail();
        $cartItem = $cart->cartItems()->where('cart_item_id', $request->cart_item_id)->first();

        if (!$cartItem) {
            return redirect()->route('cart.index')->with('error', 'Produk yang dipilih tidak ditemukan.');
        }

        $request->session()->put('checkout_cart_item_id', $cartItem->cart_item_id);

        return redirect()->route('checkout.index');
    }

    public function updateQuantity(Request $request, $cart_item_id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::with(['cart', 'product'])->findOrFail($cart_item_id);

        abort_unless(Auth::id() === $cartItem->cart->customer_id, 403);

        if ($request->quantity > $cartItem->product->stock) {
            return redirect()->back()->with('error', 'Jumlah pesanan melebihi stok yang tersedia (' . $cartItem->product->stock . ' unit).');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Jumlah barang berhasil diperbarui!');
    }

    public function checkoutAll(Request $request)
    {
        $cart = Cart::where('customer_id', Auth::user()->user_id)->first();

        if (!$cart || $cart->cartItems()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $request->session()->forget('checkout_cart_item_id');

        return redirect()->route('checkout.index');
    }

    public function removeItem($cart_item_id)
    {
        $cartItem = CartItem::findOrFail($cart_item_id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
