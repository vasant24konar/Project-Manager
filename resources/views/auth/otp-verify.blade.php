<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP — FruGo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .otp-inputs input {
            width: 50px; height: 56px; font-size: 1.5rem; font-weight: 700;
            text-align: center; border: 2px solid #dee2e6; border-radius: 10px;
            transition: border-color .2s;
        }
        .otp-inputs input:focus { border-color: #86B817; outline: none; box-shadow: 0 0 0 3px rgba(134,184,23,.15); }
    </style>
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
        <nav class="navbar navbar-light bg-white py-2">
            <a href="{{ route('shop.index') }}" class="navbar-brand d-flex align-items-center">
                <i class="fa fa-leaf text-primary me-2 fa-lg"></i>
                <h1 class="text-primary mb-0" style="font-size:1.8rem;line-height:1;">FruGo</h1>
            </a>
        </nav>
    </div>
</div>

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Verify Your OTP</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('otp.request.form') }}">Login with OTP</a></li>
        <li class="breadcrumb-item active text-white">Verify</li>
    </ol>
</div>

<!-- OTP Verify Form -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">

                @if(session('info'))
                    @php $info = session('info'); $devOtp = null;
                    if (str_contains($info, '[DEV] OTP:')) {
                        preg_match('/\[DEV\] OTP: (\d{6})/', $info, $m);
                        $devOtp = $m[1] ?? null;
                        $info   = trim(str_replace('[DEV] OTP: ' . ($devOtp ?? ''), '', $info));
                    } @endphp
                    <div class="alert alert-success mb-3 rounded-3">
                        <i class="fa fa-check-circle me-2"></i>{{ $info }}
                    </div>
                    @if($devOtp)
                    <div class="alert mb-4 rounded-3 text-center" style="background:#fff3cd;border:2px dashed #ffc107;">
                        <small class="d-block text-muted mb-1"><i class="fa fa-flask me-1"></i>Development Mode — OTP not emailed</small>
                        <span class="fw-bold" style="font-size:2rem;letter-spacing:.5rem;color:#86B817;">{{ $devOtp }}</span>
                    </div>
                    @endif
                @endif
                @if($errors->any())
                    <div class="alert alert-danger mb-4 rounded-3">
                        <i class="fa fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                    </div>
                @endif

                <div class="bg-light rounded-3 p-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                             style="width:64px;height:64px;background:#f0f8e8;">
                            <i class="fa fa-key fa-2x text-primary"></i>
                        </div>
                        <h2 class="mb-1">Enter Your OTP</h2>
                        <p class="text-muted small mb-0">
                            We sent a 6-digit code to<br>
                            <strong class="text-dark">{{ $email }}</strong>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="d-flex justify-content-center gap-2 otp-inputs mb-4">
                            <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                        </div>
                        <input type="hidden" name="otp" id="otpHidden">

                        <div id="timerBox" class="text-center mb-3">
                            <small class="text-muted">Code expires in <strong id="timer" class="text-primary">10:00</strong></small>
                        </div>

                        <button type="submit" id="verifyBtn" class="btn btn-primary rounded-pill px-4 py-3 text-white w-100 fw-semibold">
                            <i class="fa fa-check-circle me-2"></i>Verify &amp; Sign In
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted small mb-2">Didn't receive the code?</p>
                        <form method="POST" action="{{ route('otp.resend') }}">
                            @csrf
                            <button type="submit" class="btn border border-secondary rounded-pill px-4 py-2 text-primary w-100">
                                <i class="fa fa-redo me-2 text-primary"></i>Resend OTP
                            </button>
                        </form>
                        <a href="{{ route('otp.request.form') }}" class="btn btn-link text-muted small mt-2">
                            ← Use a different email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-4">
        <div class="row"><div class="col text-center"><span class="text-light"><i class="fas fa-copyright text-light me-2"></i>FruGo &copy; {{ date('Y') }}</span></div></div>
    </div>
</div>

<a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script>
// OTP digit navigation
const digits = document.querySelectorAll('.otp-digit');
const hidden = document.getElementById('otpHidden');
const form   = document.getElementById('otpForm');

digits.forEach((input, idx) => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/\D/, '');
        if (input.value && idx < digits.length - 1) digits[idx + 1].focus();
        updateHidden();
    });
    input.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !input.value && idx > 0) digits[idx - 1].focus();
    });
    input.addEventListener('paste', e => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
        [...text].forEach((ch, i) => { if (digits[i]) digits[i].value = ch; });
        updateHidden();
        if (digits[Math.min(text.length, 5)]) digits[Math.min(text.length, 5)].focus();
    });
});

function updateHidden() {
    hidden.value = [...digits].map(d => d.value).join('');
}

form.addEventListener('submit', e => {
    updateHidden();
    if (hidden.value.length !== 6) { e.preventDefault(); alert('Please enter all 6 digits.'); }
});

// Countdown timer (10 minutes)
let seconds = 600;
const timerEl = document.getElementById('timer');
const countdown = setInterval(() => {
    seconds--;
    if (seconds <= 0) {
        clearInterval(countdown);
        timerEl.textContent = 'Expired';
        timerEl.className = 'text-danger';
        document.getElementById('verifyBtn').disabled = true;
        return;
    }
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    timerEl.textContent = m + ':' + String(s).padStart(2, '0');
    if (seconds < 60) timerEl.className = 'text-danger';
}, 1000);

// Focus first digit
if (digits[0]) digits[0].focus();
</script>
</body>
</html>
