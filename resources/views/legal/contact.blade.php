<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - Zeven</title>
    <link rel="icon" type="image/png" href="/assets/img/logo_zeven.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 leading-relaxed min-h-screen flex flex-col overflow-x-hidden">
    <header
        class="bg-white border-b border-gray-100 py-6 px-10 flex items-center justify-between sticky top-0 z-50 shadow-sm">
        @php
            $backUrl = '/';
            if (session('user.role') == 'admin')
                $backUrl = route('admin.dashboard');
            elseif (session('user.role') == 'seller')
                $backUrl = route('seller.dashboard');
        @endphp
        <a href="{{ $backUrl }}"
            class="flex items-center gap-3 text-emerald-700 font-black hover:text-emerald-955 transition-all group text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" class="w-5 h-5 transition-transform group-hover:-translate-x-1"></i>
            <span>Kembali ke Halaman Sebelumnya</span>
        </a>
        <span class="text-emerald-950 font-black text-2xl tracking-tighter uppercase italic">ZEVEN<span
                class="text-amber-500">.</span></span>
    </header>

    <!-- Content Area -->
    <div class="flex-1 px-10 py-16 bg-white min-h-[60vh]">
        <div class="w-full">
            <h1 class="text-5xl font-black text-emerald-950 mb-16 tracking-tighter uppercase">Kontak Kami</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-12 rounded-[2.5rem] border border-gray-100">
                    <h2 class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-6">Saluran Bantuan
                    </h2>
                    <p class="text-2xl lg:text-3xl font-black text-emerald-950 mb-4 tracking-tight font-inter">
                        ubp.event.management@gmail.com</p>
                    <p class="text-gray-500 font-medium leading-relaxed font-inter text-sm">Tim kami siap membantu
                        kendala operasional Anda setiap hari kerja.</p>
                </div>

                <div class="bg-emerald-900 p-12 rounded-[2.5rem] text-white shadow-2xl shadow-emerald-900/40">
                    <h2 class="text-xl font-extrabold text-amber-400 uppercase tracking-wider mb-6">Alamat Resmi</h2>
                    <p class="text-base lg:text-lg font-bold mb-8 leading-relaxed font-inter italic">
                        Perum Pesona Griya Indah, Jalan sinarbaya indah, Telukjambe Timur, Kab. Karawang,
                        Jawa Barat, 41361
                    </p>

                </div>
            </div>
        </div>
    </div>

    <!-- Truly Full Width Footer -->
    <footer class="w-full bg-gray-50 border-t border-gray-100 py-12 mt-auto">
        <div class="w-full px-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-4">
                    <span class="text-emerald-950 font-black text-2xl tracking-tighter uppercase italic">ZEVEN<span
                            class="text-amber-500">.</span></span>
                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>
                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em] font-inter">&copy;
                        {{ date('Y') }} ZEVEN MARKETPLACE</span>
                </div>

                <div class="flex flex-wrap justify-center gap-x-10 gap-y-4">
                    <a href="{{ route('legal.privacy') }}"
                        class="text-[10px] font-black text-gray-400 hover:text-emerald-800 transition-all uppercase tracking-[0.2em] border-b-2 border-transparent hover:border-emerald-800 pb-1">Privacy
                        Policy</a>
                    <a href="{{ route('legal.terms') }}"
                        class="text-[10px] font-black text-gray-400 hover:text-emerald-800 transition-all uppercase tracking-[0.2em] border-b-2 border-transparent hover:border-emerald-800 pb-1">Terms</a>
                    <a href="{{ route('legal.refund') }}"
                        class="text-[10px] font-black text-gray-400 hover:text-emerald-800 transition-all uppercase tracking-[0.2em] border-b-2 border-transparent hover:border-emerald-800 pb-1">Refunds</a>
                    <a href="{{ route('legal.contact') }}"
                        class="text-[10px] font-black text-emerald-800 border-b-2 border-emerald-800 pb-1 uppercase tracking-[0.2em]">Contact
                        Us</a>
                </div>
            </div>
        </div>
    </footer>
    <script>lucide.createIcons();</script>
</body>

</html>