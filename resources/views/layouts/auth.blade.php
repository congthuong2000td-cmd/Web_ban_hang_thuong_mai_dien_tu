<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Xác thực') | CT mart</title>
    @vite(['resources/css/app.css'])
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100" style="background: linear-gradient(135deg, #EEF2F6 0%, #E2E8F0 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none d-inline-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 44" width="40" height="40" fill="none">
                            <rect x="2" y="2" width="40" height="40" rx="10" fill="#0F2C59"/>
                            <path d="M14 18C14 14 17 12 22 12H30" stroke="#FF6B35" stroke-width="3.5" stroke-linecap="round"/>
                            <path d="M30 12L27 32H17L13 18H33" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="19" cy="37" r="2.5" fill="#FF6B35"/>
                            <circle cx="27" cy="37" r="2.5" fill="#FF6B35"/>
                        </svg>
                        <span class="fs-4 fw-black" style="color:#0F2C59;letter-spacing:-0.03em;">CT<span style="color:#FF6B35;font-weight:500;">mart</span></span>
                    </a>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
                    <div class="card-body p-4 p-md-5">
                        @yield('content')
                    </div>
                </div>

                <div class="text-center mt-3 text-muted" style="font-size:0.82rem">
                    © {{ date('Y') }} CT mart. Mua sắm thông minh.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
