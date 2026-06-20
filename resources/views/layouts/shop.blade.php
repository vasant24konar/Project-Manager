<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Project Manager') - Project Manager</title>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        :root { --primary: #86B817; }
        #site-topbar { background: #81C408; }
        #site-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: #fff;
            box-shadow: 0 2px 16px rgba(0,0,0,.10);
            border-bottom: 2px solid rgba(129,196,8,.2);
        }
        .navbar .nav-link { letter-spacing: .3px; }
        .navbar .nav-link.active { color: var(--bs-primary) !important; font-weight: 600; }
        .owl-carousel .owl-nav { display: none !important; visibility: hidden !important; }
        .fruite-item, .vesitable-item { transition: box-shadow .25s, transform .25s; }
        .fruite-item:hover, .vesitable-item:hover { transform: translateY(-3px); }
        .pagination { display: flex !important; flex-wrap: wrap; gap: 4px; list-style: none; padding: 0; margin: 0; justify-content: center; }
        .pagination .page-item .page-link,
        .pagination a {
            border-radius: 50% !important;
            width: 40px; height: 40px;
            display: flex !important; align-items: center; justify-content: center;
            padding: 0 !important; margin: 0 !important;
            font-size: .88rem;
            border: 1px solid rgba(134,184,23,.35) !important;
            color: #86B817 !important;
            background: #fff !important;
            text-decoration: none;
        }
        .pagination .page-item.active .page-link {
            background: #86B817 !important;
            border-color: #86B817 !important;
            color: #fff !important;
            font-weight: 700;
        }
        .pagination .page-item.disabled .page-link,
        .pagination a.disabled {
            color: #bbb !important;
            border-color: #e5e7eb !important;
            pointer-events: none;
        }
        .featurs-icon.btn-square { width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; }
    </style>

    <script>setTimeout(function(){var s=document.getElementById('spinner');if(s)s.style.display='none';},1200);</script>

    @stack('styles')
</head>
<body>

<!-- Spinner -->
<div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>

<!-- Topbar -->
<div id="site-topbar" class="container-fluid py-2 d-none d-lg-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="top-info ps-2">
            <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i><a href="#" class="text-white">Ahmedabad, Gujarat</a></small>
            <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="mailto:vasant.24konar@gmail.com" class="text-white">vasant.24konar@gmail.com</a></small>
            <small><i class="fas fa-phone-alt me-2 text-secondary"></i><a href="tel:+919316662350" class="text-white">+91 9316662350</a></small>
        </div>
        <div class="top-link pe-2 d-flex align-items-center gap-2">
            @auth
                <i class="fa fa-user-circle text-secondary"></i>
                <small class="text-white">{{ auth()->user()->name }}</small>
                <span class="badge bg-secondary rounded-pill" style="font-size:.7rem;">{{ ucfirst(str_replace('_',' ', auth()->user()->role)) }}</span>
            @else
                <a href="{{ route('login') }}" class="text-white d-flex align-items-center gap-1">
                    <i class="fa fa-sign-in-alt text-secondary"></i>
                    <small>Sign In</small>
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Sticky Navbar -->
<div id="site-navbar">
    <div class="container">
        <nav class="navbar navbar-light bg-white navbar-expand-xl py-2">
            <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center">
                <i class="fa fa-leaf text-primary me-2 fa-lg"></i>
                <h1 class="text-primary mb-0" style="font-size:1.8rem;line-height:1;">Project Manager</h1>
            </a>
            <button class="navbar-toggler py-2 px-3 border-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-expanded="false">
                <span class="fa fa-bars text-primary"></span>
            </button>
            <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                    @auth
                        @if(auth()->user()->canManageProducts())
                            <a href="{{ route('dashboard') }}"
                               class="nav-item nav-link fw-semibold {{ request()->routeIs('dashboard') ? 'active text-primary' : '' }}">
                                <i class="fa fa-tachometer-alt me-1 text-secondary d-none d-xl-inline"></i>Dashboard
                            </a>
                            <a href="{{ route('products.index') }}"
                               class="nav-item nav-link fw-semibold {{ request()->routeIs('products.*') ? 'active text-primary' : '' }}">
                                <i class="fa fa-box me-1 text-secondary d-none d-xl-inline"></i>Products
                            </a>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.users.index') }}"
                               class="nav-item nav-link fw-semibold {{ request()->routeIs('admin.*') ? 'active text-primary' : '' }}">
                                <i class="fa fa-users me-1 text-secondary d-none d-xl-inline"></i>Users
                            </a>
                        @endif
                    @endauth
                </div>
                <div class="d-flex align-items-center m-3 me-0 gap-2">
                    @auth
                        <div class="dropdown">
                            <a href="#" class="btn border border-secondary rounded-pill px-3 py-2 text-primary d-flex align-items-center gap-2"
                               data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg"></i>
                                <span class="d-none d-xl-inline small fw-semibold">{{ auth()->user()->name }}</span>
                                <i class="fa fa-chevron-down small"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-1">
                                <li class="px-3 py-2">
                                    <p class="mb-0 fw-semibold text-dark">{{ auth()->user()->name }}</p>
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                @if(auth()->user()->canManageProducts())
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fa fa-tachometer-alt me-2 text-primary"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('products.index') }}"><i class="fa fa-box me-2 text-primary"></i>Products</a></li>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fa fa-users me-2 text-primary"></i>Manage Users</a></li>
                                @endif
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger fw-semibold">
                                            <i class="fa fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary border-2 border-secondary rounded-pill px-4 py-2">
                            <i class="fa fa-sign-in-alt me-2"></i>Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>

<!-- Flash Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible position-fixed shadow" role="alert"
         style="top:90px;right:20px;z-index:9999;min-width:300px">
        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible position-fixed shadow" role="alert"
         style="top:90px;right:20px;z-index:9999;min-width:300px">
        <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@yield('content')

<!-- Footer -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <h4 class="text-light mb-3">Project Manager</h4>
                <p>Internal product management and approval portal for admins and product managers.</p>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="d-flex flex-column text-start footer-item">
                    <h4 class="text-light mb-3">Quick Links</h4>
                    @auth
                        @if(auth()->user()->canManageProducts())
                            <a class="btn-link" href="{{ route('dashboard') }}">Dashboard</a>
                            <a class="btn-link" href="{{ route('products.index') }}">Products</a>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <a class="btn-link" href="{{ route('admin.users.index') }}">Manage Users</a>
                        @endif
                    @else
                        <a class="btn-link" href="{{ route('login') }}">Sign In</a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Contact</h4>
                    <p>Email: <a href="mailto:vasant.24konar@gmail.com" class="text-white-50">vasant.24konar@gmail.com</a></p>
                    <p>Phone: <a href="tel:+919316662350" class="text-white-50">+91 9316662350</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid copyright bg-dark py-4">
    <div class="container text-center">
        <span class="text-light"><i class="fas fa-copyright text-light me-2"></i>Project Manager &copy; {{ date('Y') }}, All rights reserved.</span>
    </div>
</div>

<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>
<script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>

<script>
    setTimeout(() => {
        document.querySelectorAll('.alert-dismissible').forEach(el => {
            try { bootstrap.Alert.getOrCreateInstance(el).close(); } catch(e) {}
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
