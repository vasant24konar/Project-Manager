@extends('layouts.shop')
@section('title', 'Checkout')

@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Checkout</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('shop.cart') }}">Cart</a></li>
        <li class="breadcrumb-item active text-white">Checkout</li>
    </ol>
</div>

<!-- Checkout Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Billing Details</h1>
        <form method="POST" action="{{ route('shop.checkout.store') }}">
            @csrf
            <div class="row g-5">
                <div class="col-md-12 col-lg-6 col-xl-7">

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            @foreach($errors->all() as $error)
                                <div><i class="fa fa-exclamation-circle me-1"></i>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-item">
                        <label class="form-label my-3">Full Name <sup>*</sup></label>
                        <input type="text" name="shipping_name" class="form-control"
                               value="{{ old('shipping_name', auth()->user()->name) }}" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Address <sup>*</sup></label>
                        <input type="text" name="shipping_address" class="form-control"
                               value="{{ old('shipping_address') }}" placeholder="House Number, Street Name" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Town / City <sup>*</sup></label>
                        <input type="text" name="shipping_city" class="form-control"
                               value="{{ old('shipping_city') }}" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Phone <sup>*</sup></label>
                        <input type="tel" name="shipping_phone" class="form-control"
                               value="{{ old('shipping_phone') }}" required>
                    </div>
                    <div class="form-item">
                        <textarea name="notes" class="form-control mt-3" spellcheck="false" cols="30" rows="5"
                                  placeholder="Order Notes (Optional)">{{ old('notes') }}</textarea>
                    </div>

                </div>

                <div class="col-md-12 col-lg-6 col-xl-5">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Products</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $item)
                                <tr>
                                    <th scope="row">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item['image_path'] ? asset($item['image_path']) : asset('img/fruite-item-1.jpg') }}"
                                                 class="img-fluid me-3 rounded-circle" style="width:60px;height:60px;object-fit:cover;" alt="{{ $item['title'] }}">
                                        </div>
                                    </th>
                                    <td><p class="mb-0 mt-3">{{ $item['title'] }}</p></td>
                                    <td><p class="mb-0 mt-3">${{ number_format($item['price'], 2) }}</p></td>
                                    <td><p class="mb-0 mt-3">{{ $item['quantity'] }}</p></td>
                                    <td><p class="mb-0 mt-3">${{ number_format($item['price'] * $item['quantity'], 2) }}</p></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-light rounded p-4 mt-4">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Subtotal</h5>
                            <p class="mb-0">${{ number_format($total, 2) }}</p>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">Shipping</h5>
                            <p class="mb-0">{{ $total >= 30 ? 'Free' : '$3.00' }}</p>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 fw-bold">Total</h5>
                            <p class="mb-0 fw-bold">${{ number_format($total + ($total < 30 ? 3 : 0), 2) }}</p>
                        </div>
                        <button type="submit" class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase w-100">
                            <i class="fa fa-check me-2"></i>Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Checkout End -->

@endsection
