<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status', 'all');
        $validStatuses = ['all', 'pending', 'processing', 'completed', 'cancelled'];
        if (! in_array($status, $validStatuses)) {
            $status = 'all';
        }

        $baseQuery = Auth::user()->orders()->with('items.product');

        $counts = collect(['pending', 'processing', 'completed', 'cancelled'])
            ->mapWithKeys(fn ($s) => [$s => (clone $baseQuery)->where('status', $s)->count()]);

        $orders = (clone $baseQuery)
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->appends(['status' => $status]);

        return view('shop.orders', compact('orders', 'status', 'counts'));
    }

    public function show(Order $order): View
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load('items.product');
        return view('shop.order-show', compact('order'));
    }

    public function checkout(): View
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.cart')->with('error', 'Your cart is empty.');
        }
        $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        return view('shop.checkout', compact('cart', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.cart')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city'    => 'required|string|max:100',
            'shipping_phone'   => 'required|string|max:20',
            'notes'            => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $cart) {
            $total = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

            $order = Order::create([
                'user_id'          => Auth::id(),
                'status'           => 'pending',
                'total'            => $total,
                'shipping_name'    => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city'    => $request->shipping_city,
                'shipping_phone'   => $request->shipping_phone,
                'notes'            => $request->notes,
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            session()->forget('cart');
        });

        return redirect()->route('shop.orders')->with('success', 'Order placed successfully!');
    }
}
