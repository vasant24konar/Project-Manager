<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        return view('shop.cart', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        if ($product->stock < 1) {
            return back()->with('error', 'Product is out of stock.');
        }

        $qty = (int) $request->quantity;
        $cart = session('cart', []);
        $key  = (string) $product->id;

        $cart[$key] = [
            'product_id' => $product->id,
            'title'      => $product->title,
            'price'      => (float) $product->price,
            'image_path' => $product->image_path,
            'quantity'   => ($cart[$key]['quantity'] ?? 0) + $qty,
        ];

        session(['cart' => $cart]);

        return back()->with('success', $product->title . ' added to cart.');
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:99']);

        $cart = session('cart', []);
        $key  = (string) $productId;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = (int) $request->quantity;
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(int $productId): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[(string) $productId]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }
}
