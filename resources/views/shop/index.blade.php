@extends('layouts.shop')
@section('title', 'Fresh Fruits & Vegetables Shop')

@section('content')

<!-- Hero Start -->
<div class="container-fluid py-5 mb-5 hero-header">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-md-12 col-lg-7">
                <h4 class="mb-3 text-secondary">100% Fresh &amp; Organic</h4>
                <h1 class="mb-5 display-3 text-primary">Fresh Fruits &amp;<br>Vegetables</h1>
                <div class="position-relative mx-auto">
                    <form method="GET" action="{{ route('shop.index') }}" class="d-flex">
                        <input class="form-control border-2 border-secondary w-75 py-3 px-4 rounded-pill"
                               type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search fresh products…">
                        <button type="submit"
                                class="btn btn-primary border-2 border-secondary py-3 px-4 position-absolute rounded-pill text-white h-100"
                                style="top:0;right:25%;">
                            Search
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-md-12 col-lg-5">
                <div id="carouselHero" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img src="{{ asset('img/hero-img-1.png') }}" class="img-fluid" alt="Fresh Fruits">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ asset('img/hero-img-2.jpg') }}" class="img-fluid rounded" alt="Fresh Vegetables">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselHero" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselHero" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero End -->


<!-- Features Start -->
<div class="container-fluid featurs py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-car-side fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>Free Delivery</h5>
                        <p class="mb-0">On orders over $30</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-user-shield fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>100% Organic</h5>
                        <p class="mb-0">Certified fresh produce</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-exchange-alt fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>Quality Assured</h5>
                        <p class="mb-0">Farm to table promise</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="featurs-item text-center rounded bg-light p-4">
                    <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-phone-alt fa-3x text-white"></i>
                    </div>
                    <div class="featurs-content text-center">
                        <h5>24/7 Support</h5>
                        <p class="mb-0">+91 9316662350</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Features End -->


<!-- Best Sellers Carousel Start -->
@if($featured->count() > 0)
<div class="container-fluid vesitable py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-12 d-flex justify-content-between align-items-end mb-2">
                <div>
                    <div class="bg-secondary rounded-pill mb-2" style="height:4px;width:50px;"></div>
                    <h1 class="mb-0">Our Bestsellers</h1>
                </div>
                <p class="text-muted mb-0 d-none d-md-block">Swipe through our freshest picks</p>
            </div>
            <div class="col-lg-12 position-relative">
                @php
                    $carouselImgs = [
                        'fruite-item-1.jpg','fruite-item-2.jpg','fruite-item-3.jpg',
                        'fruite-item-4.jpg','fruite-item-5.jpg','fruite-item-6.jpg',
                        'vegetable-item-1.jpg','vegetable-item-2.jpg'
                    ];
                @endphp
                <div class="owl-carousel vegetable-carousel justify-content-center">
                    @foreach($featured as $j => $p)
                    <div class="rounded position-relative vesitable-item">
                        <div class="vesitable-img">
                            <img src="{{ $p->image_path ? asset($p->image_path) : asset('img/'.$carouselImgs[$j % count($carouselImgs)]) }}"
                                 class="img-fluid w-100 rounded-top" alt="{{ $p->title }}"
                                 style="height:200px;object-fit:cover;">
                        </div>
                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top:10px;left:10px;font-size:.8rem;">
                            {{ ucfirst($p->category ?? 'Fresh') }}
                        </div>
                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                            <h4 class="mb-1">{{ $p->title }}</h4>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                                <p class="text-dark fs-5 fw-bold mb-0">${{ number_format($p->price, 2) }}</p>
                                @auth
                                    @if(auth()->user()->isCustomer())
                                    <form method="POST" action="{{ route('shop.cart.add', $p) }}">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn border border-secondary rounded-pill px-3 py-2 text-primary" style="font-size:.85rem;">
                                            <i class="fa fa-shopping-bag me-1 text-primary"></i>Add
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ route('shop.show', $p) }}" class="btn border border-secondary rounded-pill px-3 py-2 text-primary" style="font-size:.85rem;">
                                        <i class="fa fa-eye me-1 text-primary"></i>View
                                    </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn border border-secondary rounded-pill px-3 py-2 text-primary" style="font-size:.85rem;">
                                        <i class="fa fa-shopping-bag me-1 text-primary"></i>Add
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Best Sellers Carousel End -->


<!-- Fruits Shop Start -->
<div class="container-fluid fruite py-5 bg-light" id="products">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-12 text-center">
                <div class="bg-secondary rounded-pill mx-auto mb-3" style="height:4px;width:60px;"></div>
                <h1 class="mb-4">Fresh Fruits &amp; Vegetables</h1>
            </div>
            <div class="col-lg-12">

                {{-- Category tabs --}}
                <div class="tab-class text-center mb-4">
                    <div class="row g-4">
                        <div class="col-lg-4 text-start">
                            <h4 class="text-muted">Browse by category</h4>
                        </div>
                        <div class="col-lg-8 text-end">
                            <ul class="nav nav-pills d-inline-flex text-center mb-2 flex-wrap">
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-white rounded-pill {{ !request('category') ? 'active' : '' }}"
                                       href="{{ route('shop.index', request()->except('category','page')) }}">
                                        <span class="text-dark" style="width:110px;">All Products</span>
                                    </a>
                                </li>
                                @foreach($categories as $cat)
                                <li class="nav-item">
                                    <a class="d-flex m-2 py-2 bg-white rounded-pill {{ request('category') === $cat ? 'active' : '' }}"
                                       href="{{ route('shop.index', array_merge(request()->except('page'), ['category' => $cat])) }}">
                                        <span class="text-dark" style="width:110px;">{{ ucfirst($cat) }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Products grid --}}
                @if($products->isEmpty())
                    <div class="text-center py-5">
                        <img src="{{ asset('img/fruite-item-1.jpg') }}" alt="" style="width:120px;border-radius:50%;opacity:.4">
                        <h4 class="mt-3 text-muted">No products found</h4>
                        @if(request('search') || request('category'))
                            <a href="{{ route('shop.index') }}" class="btn border border-secondary rounded-pill px-4 py-2 text-primary mt-2">
                                <i class="fa fa-arrow-left me-1"></i>Browse All
                            </a>
                        @endif
                    </div>
                @else
                <div class="row g-4 justify-content-center">
                    @php
                        $gridImgs = [
                            'fruite-item-1.jpg','fruite-item-2.jpg','fruite-item-3.jpg',
                            'fruite-item-4.jpg','fruite-item-5.jpg','fruite-item-6.jpg',
                            'vegetable-item-1.jpg','vegetable-item-2.jpg','vegetable-item-3.png',
                            'vegetable-item-4.jpg','vegetable-item-5.jpg','vegetable-item-6.jpg'
                        ];
                    @endphp
                    @foreach($products as $i => $product)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="rounded position-relative fruite-item h-100">
                            <div class="fruite-img">
                                <img src="{{ $product->image_path ? asset($product->image_path) : asset('img/'.$gridImgs[$i % count($gridImgs)]) }}"
                                     class="img-fluid w-100 rounded-top" alt="{{ $product->title }}"
                                     style="height:200px;object-fit:cover;">
                            </div>
                            <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top:10px;left:10px;">
                                {{ ucfirst($product->category ?? 'Fresh') }}
                            </div>
                            @if($product->stock < 10)
                                <div class="text-white bg-danger px-3 py-1 rounded position-absolute" style="top:10px;right:10px;font-size:.75rem">
                                    {{ $product->stock === 0 ? 'Sold Out' : 'Low Stock' }}
                                </div>
                            @endif
                            <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                <h4>{{ $product->title }}</h4>
                                <p class="text-muted small mb-2">{{ Str::limit(strip_tags($product->description), 55) }}</p>
                                <div class="d-flex justify-content-between flex-lg-wrap align-items-center">
                                    <p class="text-dark fs-5 fw-bold mb-0">${{ number_format($product->price, 2) }}</p>
                                    @auth
                                        @if(auth()->user()->isCustomer())
                                            @if($product->stock > 0)
                                            <form method="POST" action="{{ route('shop.cart.add', $product) }}">
                                                @csrf
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                    <i class="fa fa-shopping-bag me-2 text-primary"></i>Add to cart
                                                </button>
                                            </form>
                                            @else
                                                <span class="btn border border-secondary rounded-pill px-3 text-muted">Out of Stock</span>
                                            @endif
                                        @else
                                            <a href="{{ route('shop.show', $product) }}" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                <i class="fa fa-eye me-2 text-primary"></i>View
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn border border-secondary rounded-pill px-3 text-primary">
                                            <i class="fa fa-shopping-bag me-2 text-primary"></i>Add to cart
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-5">{{ $products->links() }}</div>
                @endif

            </div>
        </div>
    </div>
</div>
<!-- Fruits Shop End -->


<!-- Banner Start -->
<div class="container-fluid banner bg-secondary my-5">
    <div class="container py-5">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <div class="py-4">
                    <h1 class="display-3 text-white">Fresh &amp; Healthy</h1>
                    <p class="fw-normal display-3 text-dark mb-4">Organic Food</p>
                    <p class="mb-4 text-dark">Get 20% off your first order. Use code <strong>FRESH20</strong> at checkout.</p>
                    <a href="#products" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">Shop Now</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="{{ asset('img/banner-fruits.jpg') }}" class="img-fluid w-100 rounded" alt="Fresh Fruits">
                    <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute"
                         style="width:140px;height:140px;top:0;left:0;">
                        <div class="text-center">
                            <div class="h2 text-primary mb-0">{{ \App\Models\Product::count() }}+</div>
                            <div class="small text-muted">Products</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Banner End -->

@endsection
