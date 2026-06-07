<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zeven - Masuk ke Platform</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo_zeven.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --zeven-primary: #114232;
            --zeven-secondary: #C68E17;
            --zeven-bg: #F9F6F0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--zeven-bg) !important;
            margin: 0;
            padding: 0;
        }

        .login-header {
            height: 84px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10%;
            background: white;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo {
            height: 42px;
            width: 42px;
            object-contain: contain;
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--zeven-primary);
        }



        .main-container {
            background-color: var(--zeven-primary);
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 10%;
            position: relative;
            overflow: hidden;
        }

        /* Decorative Circles */
        .decor-circle {
            position: absolute;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            z-index: 1;
        }

        .content-wrapper {
            display: flex;
            width: 100%;
            max-width: 1200px;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        .branding-section {
            flex: 1;
            display: none;
            flex-direction: column;
            align-items: center;
            color: white;
            text-align: center;
        }

        @media (min-width: 1024px) {
            .branding-section {
                display: flex;
            }
        }

        .big-logo {
            width: 280px;
            height: 280px;
            margin-bottom: 24px;
            filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.2));
            animation: float 6s ease-in-out infinite;
        }

        .animation-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .branding-section h2 {
            font-size: 42px;
            font-weight: 900;
            margin: 0;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .branding-section .subtitle {
            font-size: 20px;
            color: var(--zeven-secondary);
            margin-top: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .branding-section .description {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 24px;
            max-width: 480px;
            line-height: 1.6;
            font-weight: 400;
        }

        .store-badge {
            margin-top: 40px;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .store-badge:hover {
            transform: scale(1.05);
        }

        .form-section {
            width: 100%;
            max-width: 500px;
            display: flex;
            justify-content: center;
        }

        .login-card {
            background: white;
            width: 100%;
            padding: 40px;
            border-radius: 4px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 22px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }

        .card-subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 15px;
            position: relative;
        }

        .custom-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 2px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .custom-input:focus {
            border-color: var(--zeven-primary);
        }

        /* Password Toggle Styling */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--zeven-primary);
        }

        .password-toggle i {
            width: 18px;
            height: 18px;
        }

        .login-btn {
            width: 100%;
            background-color: var(--zeven-primary);
            color: white;
            border: none;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 2px;
            transition: 0.3s;
        }

        .login-btn:hover {
            opacity: 0.9;
        }

        .card-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #888;
        }

        .card-footer a {
            color: var(--zeven-secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: #dbdbdb;
            font-size: 12px;
            text-transform: uppercase;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dbdbdb;
        }

        .divider span {
            padding: 0 10px;
        }

        .social-buttons {
            display: flex;
            gap: 10px;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            border: 1px solid #dbdbdb;
            background: white;
            border-radius: 2px;
            font-size: 14px;
            color: #555;
            cursor: pointer;
        }

        .alert-error {
            background: #fff5f5;
            color: #e53e3e;
            padding: 12px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 20px;
            border-left: 4px solid #e53e3e;
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
    <!-- Header Minimalis High-End -->
    <header class="bg-white border-b border-gray-100 h-20 flex items-center px-[5%] md:px-[10%] justify-between">
        <div class="flex items-center gap-6">
            <span class="text-emerald-950 font-black text-2xl tracking-tighter uppercase italic select-none">ZEVEN<span
                    class="text-amber-500">.</span></span>
            <div class="h-6 w-px bg-gray-200 hidden md:block"></div>
            <span class="text-xl font-bold text-gray-800 hidden md:block">Log In</span>
        </div>
        <div class="header-right flex items-center gap-3">
            <span class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Siap Jualan?</span>
            <a href="{{ route('register') }}"
                style="font-family: 'Inter', sans-serif !important; font-weight: 900 !important; letter-spacing: 0.05em;"
                class="text-[12px] text-amber-600 tracking-widest uppercase no-underline hover:text-amber-500 transition-all">
                DAFTAR SEKARANG
            </a>
        </div>
    </header>

    <!-- Main Section -->
    <main class="main-container">
        <!-- Decor -->
        <div class="decor-circle" style="width: 400px; height: 400px; top: -100px; left: -100px;"></div>
        <div class="decor-circle" style="width: 300px; height: 300px; bottom: -50px; right: 10%;"></div>

        <div class="content-wrapper">
            <!-- Sisi Kiri: Branding -->
            <div class="branding-section" style="align-items: flex-start; text-align: left;">
                <div class="animation-float flex items-center gap-5" style="margin-bottom: 30px;">
                    <img src="{{ asset('assets/img/logo_zeven.png') }}" alt="Zeven Logo"
                        style="width: 80px; height: 80px; filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.2));">
                    <div class="flex flex-col">
                        <span class="text-4xl font-black tracking-tighter italic text-white">ZEVEN<span
                                class="text-amber-500">.</span></span>
                        <span
                            class="text-[11px] font-bold tracking-[0.4em] uppercase text-white/50 -mt-1">Marketplace</span>
                    </div>
                </div>
                <h2>Tumbuhkan Bisnis Anda<br>Bersama Platform Kami.</h2>
                <div class="subtitle">Business Portal</div>
                <p class="description">
                    Zeven adalah platform inovatif yang memadukan interaksi sosial dengan pengalaman belanja digital
                    yang interaktif. Kami menghubungkan komunitas dengan peluang bisnis dalam satu ekosistem yang
                    dinamis.
                </p>
                <p class="description" style="margin-top: 15px; font-weight: 600; color: #fff;">
                    Kelola bisnis Anda dengan mudah atau bergabunglah sekarang sebagai Seller resmi Zeven untuk mulai
                    menjangkau lebih banyak pelanggan.
                </p>

                <div class="description"
                    style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; font-size: 14px;">
                    <span
                        style="display: block; margin-bottom: 12px; font-weight: 700; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px;">Ingin
                        Berbelanja?</span>
                    Temukan berbagai produk pilihan dan nikmati pengalaman belanja sosial yang interaktif melalui
                    aplikasi mobile Zeven Marketplace.
                </div>

                <a href="#" class="store-badge">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                        alt="Get it on Google Play" style="height: 50px;">
                </a>
            </div>

            <!-- Sisi Kanan: Form Login -->
            <div class="form-section">
                <div class="login-card">
                    <h3 class="card-title">Selamat Datang</h3>
                    <p class="card-subtitle">Silakan masuk untuk mengelola bisnis Anda</p>

                    @if(session('error'))
                        <div class="alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('login.submit') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input name="email" type="email" placeholder="Email" required class="custom-input">
                        </div>
                        <div class="input-group">
                            <div class="password-wrapper">
                                <input name="password" id="login-password" type="password" placeholder="Password" required class="custom-input">
                                <button type="button" class="password-toggle" 
                                    onmousedown="setPasswordVisibility('login-password', this, true)" 
                                    onmouseup="setPasswordVisibility('login-password', this, false)" 
                                    onmouseleave="setPasswordVisibility('login-password', this, false)"
                                    ontouchstart="setPasswordVisibility('login-password', this, true)" 
                                    ontouchend="setPasswordVisibility('login-password', this, false)">
                                    <i data-lucide="eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="login-btn">Log In</button>
                    </form>

                    <div class="divider">
                        <span>Atau</span>
                    </div>

                    <div class="social-buttons">
                        <button type="button" id="google-login-btn" class="social-btn w-full">
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18">
                            Lanjutkan dengan Google
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Professional -->
    <footer class="p-8 md:p-12 border-t border-gray-100 bg-white mt-auto w-full">
        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6 text-[10px] font-bold text-gray-400 tracking-widest uppercase">
            <div class="flex items-center gap-4">
                <span class="text-emerald-950 font-black text-2xl tracking-tighter uppercase italic">ZEVEN<span
                        class="text-amber-500">.</span></span>
                <div class="h-6 w-px bg-gray-200 hidden md:block"></div>
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">&copy; {{ date('Y') }}
                    ZEVEN MARKETPLACE</span>
            </div>
            <div class="flex flex-wrap justify-center gap-8">
                <a href="{{ route('legal.privacy') }}"
                    class="hover:text-emerald-800 transition-all border-b-2 border-transparent hover:border-emerald-800 pb-0.5 text-gray-400 no-underline">Privacy
                    Policy</a>
                <a href="{{ route('legal.terms') }}"
                    class="hover:text-emerald-800 transition-all border-b-2 border-transparent hover:border-emerald-800 pb-0.5 text-gray-400 no-underline">Terms
                    of Service</a>
                <a href="{{ route('legal.refund') }}"
                    class="hover:text-emerald-800 transition-all border-b-2 border-transparent hover:border-emerald-800 pb-0.5 text-gray-400 no-underline">Refund
                    Policy</a>
                <a href="{{ route('legal.contact') }}"
                    class="hover:text-emerald-800 transition-all border-b-2 border-transparent hover:border-emerald-800 pb-0.5 text-gray-400 no-underline">Contact
                    Us</a>
            </div>
        </div>
    </footer>

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        lucide.createIcons();

        // Password Visibility Hold Logic
        function setPasswordVisibility(inputId, button, isVisible) {
            const input = document.getElementById(inputId);
            if (!input) return;

            if (isVisible) {
                input.type = 'text';
                button.innerHTML = '<i data-lucide="eye-off"></i>';
            } else {
                input.type = 'password';
                button.innerHTML = '<i data-lucide="eye"></i>';
            }
            
            lucide.createIcons();
        }

        // Google Login Handler
        document.getElementById('google-login-btn').addEventListener('click', function () {
            // Trigger loading state if needed
            this.innerHTML = '<span class="animate-spin">⌛</span> Menghubungkan...';
            this.disabled = true;

            // Redirect ke route google login Laravel kamu
            window.location.href = "{{ url('auth/google') }}";
        });
    </script>
</body>

</html>