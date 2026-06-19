<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products'  => Product::approved()->count(),
            'total_orders'    => Order::count(),
            'total_revenue'   => (float) Order::where('status', '!=', 'cancelled')->sum('total'),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        $outOfStock    = Product::approved()->where('stock', 0)->latest()->get();
        $lowStock      = Product::approved()->where('stock', '>', 0)->where('stock', '<', 10)->orderBy('stock')->get();
        $pendingOrders = Order::with(['user', 'items'])->whereIn('status', ['pending', 'processing'])->latest()->get();

        $pendingProducts = Auth::user()->isAdmin()
            ? Product::pending()->with('creator')->latest()->get()
            : collect();

        return view('dashboard', compact('stats', 'outOfStock', 'lowStock', 'pendingOrders', 'pendingProducts'));
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate(['status' => 'required|in:pending,processing,completed,cancelled']);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order #' . $order->id . ' status updated to ' . $request->status . '.');
    }
}
