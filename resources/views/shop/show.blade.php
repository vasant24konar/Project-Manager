@extends('layouts.shop')
@section('title', $product->title)

@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">{{ $product->title }}</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
        <li class="breadcrumb-item active text-white">{{ $product->title }}</li>
    </ol>
</div>

<!-- Product Detail Start -->
<div class="container-fluid py-5 mt-5">
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-8 col-xl-9">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="border rounded">
                            <img src="{{ $product->image_path ? asset($product->image_path) : asset('img/single-item.jpg') }}"
                                 class="img-fluid rounded w-100" alt="{{ $product->title }}"
                                 style="object-fit:cover;max-height:400px;">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="fw-bold mb-3">{{ $product->title }}</h4>
                        <p class="mb-3">Category: <span class="text-primary">{{ ucfirst($product->category ?? 'Fresh') }}</span></p>
                        <h5 class="fw-bold mb-3">${{ number_format($product->price, 2) }}</h5>
                        <div class="d-flex mb-4">
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <p class="mb-4">{!! $product->description !!}</p>

                        @if($product->stock > 0)
                            <p class="text-success mb-3"><i class="fa fa-check-circle me-2"></i>In Stock ({{ $product->stock }} available)</p>
                        @else
                            <p class="text-danger mb-3"><i class="fa fa-times-circle me-2"></i>Out of Stock</p>
                        @endif

                        @auth
                            @if(auth()->user()->isCustomer() && $product->stock > 0)
                            <form method="POST" action="{{ route('shop.cart.add', $product) }}" class="d-flex gap-3 align-items-center flex-wrap">
                                @csrf
                                <div class="input-group quantity" style="width:100px;">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-minus rounded-circle bg-light border">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="number" name="quantity" class="form-control form-control-sm text-center border-0" value="1" min="1" max="{{ $product->stock }}">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="btn border border-secondary rounded-pill px-4 py-3 text-primary">
                                    <i class="fa fa-shopping-bag me-2 text-primary"></i>Add to Cart
                                </button>
                            </form>
                            @elseif(auth()->user()->canManageProducts())
                            <a href="{{ route('products.edit', $product) }}" class="btn border border-secondary rounded-pill px-4 py-3 text-primary">
                                <i class="fa fa-edit me-2 text-primary"></i>Edit Product
                            </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn border border-secondary rounded-pill px-4 py-3 text-primary">
                                <i class="fa fa-sign-in-alt me-2 text-primary"></i>Login to Buy
                            </a>
                        @endauth

                        <div class="mt-4 pt-4 border-top">
                            <p class="mb-1 text-muted"><small>Available from: {{ $product->date_available->format('F j, Y') }}</small></p>
                            @if($product->creator)
                            <p class="mb-0 text-muted"><small>Added by: {{ $product->creator->name }}</small></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xl-3">
                <div class="bg-light rounded p-4">
                    <h4 class="mb-3">Product Info</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fa fa-leaf text-primary me-2"></i>100% Fresh</li>
                        <li class="mb-2"><i class="fa fa-truck text-primary me-2"></i>Free delivery on $30+</li>
                        <li class="mb-2"><i class="fa fa-shield-alt text-primary me-2"></i>Quality Guaranteed</li>
                        <li class="mb-2"><i class="fa fa-undo text-primary me-2"></i>Easy Returns</li>
                    </ul>
                    <hr>
                    <a href="{{ route('shop.index') }}" class="btn border border-secondary rounded-pill px-4 py-2 text-primary w-100">
                        <i class="fa fa-arrow-left me-2"></i>Back to Shop
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Product Detail End -->

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.input-group').querySelector('input[type=number]');
            input.value = Math.min(parseInt(input.max || 99), parseInt(input.value || 1) + 1);
        });
    });
    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.input-group').querySelector('input[type=number]');
            input.value = Math.max(1, parseInt(input.value || 1) - 1);
        });
    });
</script>
@endpush
