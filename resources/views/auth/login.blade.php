<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — Project Manager</title>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>

<!-- Spinner Start -->
<div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" role="status"></div>
</div>
<!-- Spinner End -->


<!-- Topbar -->
<div class="container-fluid fixed-top">
    <div class="container topbar bg-primary d-none d-lg-block">
        <div class="d-flex justify-content-between">
            <div class="top-info ps-2">
                <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i><a href="#" class="text-white">Ahmedabad, Gujarat</a></small>
                <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="mailto:vasant.24konar@gmail.com" class="text-white">vasant.24konar@gmail.com</a></small>
                <small><i class="fas fa-phone-alt me-2 text-secondary"></i><a href="tel:+919316662350" class="text-white">+91 9316662350</a></small>
            </div>
        </div>
    </div>
    <div class="container px-0">
        <nav class="navbar navbar-light bg-white navbar-expand-xl">
            <a href="{{ route('login') }}" class="navbar-brand">
                <h1 class="text-primary display-6">Project Manager</h1>
            </a>
        </nav>
    </div>
</div>


<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Sign In</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item active text-white">Sign In</li>
    </ol>
</div>


<!-- Login Form Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <i class="fa fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <div class="bg-light rounded p-5">
                    <h1 class="display-6 mb-4">Welcome <span class="fw-normal">Back!</span></h1>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-item mb-4">
                            <label for="email" class="form-label my-3">Email Address <sup>*</sup></label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   value="{{ old('email') }}" placeholder="you@example.com"
                                   class="form-control @error('email') is-invalid @enderror">
                        </div>

                        <div class="form-item mb-4">
                            <label for="password" class="form-label my-3">Password <sup>*</sup></label>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                   required placeholder="••••••••"
                                   class="form-control @error('password') is-invalid @enderror">
                        </div>

                        <div class="form-check my-3">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-3 text-white text-uppercase w-100 fw-semibold">
                            <i class="fa fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </form>

                    <hr class="my-4">

                    <h5 class="mb-3">Demo Accounts</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary rounded-pill">Admin</span></td>
                                    <td>admin@example.com</td>
                                    <td>Admin@1234</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary rounded-pill">Manager</span></td>
                                    <td>manager@example.com</td>
                                    <td>Manager@1234</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Login Form End -->


<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <h4 class="text-light mb-3">Project Manager</h4>
                <p>Internal product management and approval portal.</p>
            </div>
            <div class="col-lg-4 col-md-6">
                <h4 class="text-light mb-3">Contact</h4>
                <p><i class="fas fa-phone-alt text-primary me-2"></i>+91 9316662350</p>
                <p><i class="fas fa-envelope text-primary me-2"></i>vasant.24konar@gmail.com</p>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid copyright bg-dark py-4">
    <div class="container text-center">
        <span class="text-light"><i class="fas fa-copyright text-light me-2"></i>Project Manager &copy; {{ date('Y') }}, All rights reserved.</span>
    </div>
</div>
<!-- Footer End -->

<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
