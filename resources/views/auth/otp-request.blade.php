<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with OTP — FruGo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>

<!-- Spinner -->
<div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>

<!-- Topbar -->
<div class="container-fluid bg-primary py-2 d-none d-lg-block">
    <div class="container d-flex justify-content-between align-items-center">
        <small class="text-white"><i class="fas fa-envelope me-2 text-secondary"></i>vasant.24konar@gmail.com</small>
        <a href="{{ route('shop.index') }}" class="text-white"><small>← Browse Shop</small></a>
    </div>
</div>
<!-- Sticky Nav -->
<div style="position:sticky;top:0;z-index:1030;background:#fff;box-shadow:0 2px 16px rgba(0,0,0,.10);border-bottom:2px solid rgba(129,196,8,.2);">
    <div class="container">
        <nav class="navbar navbar-light bg-white navbar-expand-xl py-2">
            <a href="{{ route('shop.index') }}" class="navbar-brand d-flex align-items-center">
                <i class="fa fa-leaf text-primary me-2 fa-lg"></i>
                <h1 class="text-primary mb-0" style="font-size:1.8rem;line-height:1;">FruGo</h1>
            </a>
            <div class="d-flex m-3 me-0 gap-2">
                <a href="{{ route('login') }}" class="btn border border-secondary rounded-pill px-3 py-2 text-primary">
                    <i class="fa fa-lock me-1"></i>Password Login
                </a>
            </div>
        </nav>
    </div>
</div>

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Passwordless Login</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Home</a></li>
        <li class="breadcrumb-item active text-white">Login with OTP</li>
    </ol>
</div>

<!-- OTP Request Form -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">

                @if(session('info'))
                    <div class="alert alert-info mb-4 rounded-3">
                        <i class="fa fa-info-circle me-2"></i>{{ session('info') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mb-4 rounded-3">
                        <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <div class="bg-light rounded-3 p-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary mb-3"
                             style="width:64px;height:64px;">
                            <i class="fa fa-envelope fa-2x text-white"></i>
                        </div>
                        <h2 class="mb-1">Login with OTP</h2>
                        <p class="text-muted small">Enter your email and we'll send a 6-digit code to sign you in instantly.</p>
                    </div>

                    <form method="POST" action="{{ route('otp.request') }}">
                        @csrf

                        <div class="form-item mb-4">
                            <label for="email" class="form-label my-3 fw-semibold">
                                <i class="fa fa-envelope me-2 text-primary"></i>Email Address <sup class="text-danger">*</sup>
                            </label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   value="{{ old('email') }}" placeholder="you@example.com"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-3 text-white w-100 fw-semibold">
                            <i class="fa fa-paper-plane me-2"></i>Send OTP to My Email
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-2">Prefer a password?</p>
                        <a href="{{ route('login') }}" class="btn border border-secondary rounded-pill px-4 py-2 text-primary w-100">
                            <i class="fa fa-lock me-2 text-primary"></i>Sign In with Password
                        </a>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="fa fa-shield-alt text-primary me-1"></i>
                        New users will have an account created automatically.
                    </small>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4"><h4 class="text-light mb-3">FruGo</h4><p>Fresh fruits &amp; vegetables delivered daily.</p></div>
            <div class="col-lg-4"><h4 class="text-light mb-3">Contact</h4>
                <p><i class="fas fa-phone-alt text-primary me-2"></i>+91 9316662350</p>
                <p><i class="fas fa-envelope text-primary me-2"></i>vasant.24konar@gmail.com</p>
            </div>
            <div class="col-lg-4"><h4 class="text-light mb-3">Quick Links</h4><a class="btn-link" href="{{ route('shop.index') }}">Shop</a></div>
        </div>
    </div>
</div>
<div class="container-fluid copyright bg-dark py-4">
    <div class="container text-center">
        <span class="text-light"><i class="fas fa-copyright text-light me-2"></i>FruGo &copy; {{ date('Y') }}, All rights reserved.</span>
    </div>
</div>

<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
