@extends('layouts.shop')
@section('title', 'Shopping Cart')

@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Shopping Cart</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Home</a></li>
        <li class="breadcrumb-item active text-white">Cart</li>
    </ol>
</div>

<!-- Cart Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        @if(empty($cart))
            <div class="text-center py-5">
                <i class="fa fa-shopping-bag fa-5x text-secondary mb-4"></i>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Add some fresh products to get started!</p>
                <a href="{{ route('shop.index') }}" class="btn border border-secondary rounded-pill px-4 py-3 text-primary">
                    <i class="fa fa-store me-2"></i>Continue Shopping
                </a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Handle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $key => $item)
                    <tr>
                        <th scope="row">
                            <div class="d-flex align-items-center">
                                <img src="{{ $item['image_path'] ? asset($item['image_path']) : asset('img/fruite-item-1.jpg') }}"
                                     class="img-fluid me-5 rounded-circle" style="width:80px;height:80px;object-fit:cover;" alt="{{ $item['title'] }}">
                            </div>
                        </th>
                        <td><p class="mb-0 mt-4">{{ $item['title'] }}</p></td>
                        <td><p class="mb-0 mt-4">${{ number_format($item['price'], 2) }}</p></td>
                        <td>
                            <form method="POST" action="{{ route('shop.cart.update', $item['product_id']) }}" id="update-form-{{ $item['product_id'] }}">
                                @csrf @method('PATCH')
                                <div class="input-group quantity mt-4" style="width:100px;">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-minus rounded-circle bg-light border">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="number" name="quantity" class="form-control form-control-sm text-center border-0"
                                           value="{{ $item['quantity'] }}" min="1" max="99" data-form="update-form-{{ $item['product_id'] }}">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-plus rounded-circle bg-light border">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm border-secondary rounded-pill px-3 text-primary mt-2" style="font-size:.78rem">Update</button>
                            </form>
                        </td>
                        <td><p class="mb-0 mt-4">${{ number_format($item['price'] * $item['quantity'], 2) }}</p></td>
                        <td>
                            <form method="POST" action="{{ route('shop.cart.remove', $item['product_id']) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-md rounded-circle bg-light border mt-4">
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 d-flex gap-3 flex-wrap align-items-center">
            <a href="{{ route('shop.index') }}" class="btn border border-secondary rounded-pill px-4 py-3 text-primary">
                <i class="fa fa-arrow-left me-2"></i>Continue Shopping
            </a>
            <form method="POST" action="{{ route('shop.cart.clear') }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn border border-danger rounded-pill px-4 py-3 text-danger"
                        onclick="return confirm('Clear all items?')">
                    <i class="fa fa-trash me-2"></i>Clear Cart
                </button>
            </form>
        </div>

        <div class="row g-4 justify-content-end mt-2">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="p-4">
                        <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Subtotal:</h5>
                            <p class="mb-0">${{ number_format($total, 2) }}</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 me-4">Shipping</h5>
                            <p class="mb-0">{{ $total >= 30 ? 'Free' : 'Flat rate: $3.00' }}</p>
                        </div>
                        @if($total < 30)
                            <p class="mb-0 text-end text-muted small">Free shipping on orders over $30</p>
                        @endif
                    </div>
                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                        <h5 class="mb-0 ps-4 me-4">Total</h5>
                        <p class="mb-0 pe-4">${{ number_format($total + ($total < 30 ? 3 : 0), 2) }}</p>
                    </div>
                    <div class="ps-4 pb-4">
                        <a href="{{ route('shop.checkout') }}" class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<!-- Cart End -->

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.closest('.input-group').querySelector('input[type=number]');
            input.value = Math.min(99, parseInt(input.value || 1) + 1);
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
