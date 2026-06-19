<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->approved()
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Product::distinct()->pluck('category')->sort()->values();

        $featured = Product::where('stock', '>', 0)->latest()->limit(8)->get();

        return view('shop.index', compact('products', 'categories', 'featured'));
    }

    public function show(Product $product): View
    {
        $related = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}
