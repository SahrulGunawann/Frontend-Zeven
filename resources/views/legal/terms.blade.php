<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - Zeven Marketplace</title>
    <link rel="icon" type="image/png" href="/assets/img/logo_zeven.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
    <!-- Header Full Width -->
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
    <main class="flex-1 px-10 py-16 bg-white min-h-[60vh] w-full">
        <div class="w-full">
            <!-- Header Section -->
            <div class="border-b border-gray-100 pb-8 mb-12">
                <h1 class="text-5xl font-black text-emerald-955 mb-4 tracking-tighter uppercase">Syarat & Ketentuan</h1>
                <p class="text-sm text-gray-400 font-medium font-mono">Terakhir Diperbarui: 2 Juni 2026</p>
                <p class="text-gray-500 mt-6 text-sm md:text-base leading-relaxed font-medium">
                    Selamat datang di Zeven. Syarat & Ketentuan ini mengatur penggunaan situs web resmi dan Aplikasi
                    Mobile (APK Android) Zeven sebagai platform marketplace sosial e-commerce. Dengan mengakses atau
                    menggunakan platform kami, Anda setuju untuk terikat oleh ketentuan hukum yang berlaku di bawah ini.
                    Jika Anda tidak menyetujui ketentuan ini, harap segera menghentikan penggunaan platform Zeven.
                </p>
            </div>

            <!-- Sections Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-20 gap-y-12">
                <!-- Section 01 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">01</span>
                        Lisensi Aplikasi & Akun Pengguna
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Zeven memberikan lisensi terbatas, non-eksklusif, dan tidak dapat dipindahtangankan kepada
                            Anda untuk mengunduh, menginstal, dan menggunakan Aplikasi Mobile (APK) serta situs web kami
                            hanya untuk tujuan transaksi perdagangan yang sah.</p>
                        <p>Pengguna bertanggung jawab penuh atas kerahasiaan akun, nama pengguna, kata sandi, serta
                            seluruh aktivitas transaksi yang terjadi di bawah akun pribadi Anda.</p>
                    </div>
                </section>

                <!-- Section 02 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">02</span>
                        Ketentuan Penjualan & Produk (Seller)
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Sebagai Penjual (Seller) di platform Zeven, Anda wajib mematuhi aturan berikut:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Wajib memberikan foto, deskripsi, harga, dan jumlah stok produk secara jujur, akurat,
                                dan riil sesuai kondisi fisik barang.</li>
                            <li>Dana akan dikembalikan secara otomatis melalui sistem payment gateway DompetX langsung
                                ke sumber metode pembayaran awal yang Anda gunakan saat checkout (E-wallet
                                Dana/OVO/ShopeePay, transfer bank virtual account, dll).</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 03 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">03</span>
                        Transaksi, Biaya Platform, & Komisi
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Seluruh transaksi pembayaran diproses secara aman melalui payment gateway resmi DompetX.
                        </p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Setiap transaksi penjualan yang berhasil diselesaikan oleh Toko (Seller) akan dikenakan
                                Potongan Komisi Biaya Layanan Platform sebesar 2% secara otomatis dari total bruto
                                penjualan sebelum dana masuk ke saldo yang dapat ditarik (withdrawable balance).</li>
                            <li>Zeven berhak menahan penarikan saldo apabila terdeteksi adanya indikasi kecurangan,
                                sengketa transaksi, atau pelanggaran Syarat & Ketentuan ini.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 04 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">04</span>
                        Batasan Tanggung Jawab
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Zeven bertindak sebagai penyedia platform perantara e-commerce (Web & APK) yang menghubungkan
                            Pembeli dan Penjual.</p>
                        <p>Kami tidak bertanggung jawab atas kualitas produk, keaslian produk, cacat tersembunyi, atau
                            Metode Pengembalian Dana (DompetX)
 yang murni disebabkan oleh kelalaian Penjual maupun pihak ekspedisi
                            logistik ketiga.</p>
                    </div>
                </section>

                <!-- Section 05 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">05</span>
                        Penutupan & Penangguhan Akun
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Zeven berhak untuk menangguhkan (suspend) atau menutup akun Anda secara permanen tanpa
                            pemberitahuan terlebih dahulu jika:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Anda terbukti melanggar ketentuan hukum yang berlaku di Indonesia atau Syarat &
                                Ketentuan platform kami.</li>
                            <li>Terdeteksi melakukan spamming, penipuan transaksi belanja, kloning produk secara ilegal,
                                atau tindakan sabotase sistem digital Zeven.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 06 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">06</span>
                        Hak Kekayaan Intelektual
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Seluruh kekayaan intelektual yang terdapat pada platform Zeven (termasuk kode program web &
                            APK, desain antarmuka, logo ZEVEN., grafis, merek dagang, dan database sistem) adalah milik
                            sah pengembang (ZevenDev).</p>
                        <p>Dilarang keras menyalin, memodifikasi, mendistribusikan, atau mendekompilasi (reverse
                            engineer) APK maupun kode situs web kami tanpa izin tertulis dari kami.</p>
                    </div>
                </section>

                <!-- Section 07 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">07</span>
                        Hukum yang Mengatur
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm leading-relaxed">
                        <p>Syarat & Ketentuan ini diatur dan ditafsirkan sepenuhnya berdasarkan hukum yang berlaku di
                            Negara Kesatuan Republik Indonesia. Setiap perselisihan yang timbul dari penggunaan platform
                            ini akan diselesaikan secara musyawarah mufakat, atau melalui jalur yurisdiksi Pengadilan
                            Negeri Indonesia yang berwenang.</p>
                    </div>
                </section>

                <!-- Section 08 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">08</span>
                        Kontak Hukum Resmi
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm leading-relaxed space-y-1">
                        <p class="text-gray-800"><span class="text-gray-400">Tim Hukum Platform:</span> ZevenDev Legal
                            Team</p>
                        <p class="text-gray-800"><span class="text-gray-400">Email Korespondensi:</span>
                            ubp.event.management@gmail.com</p>
                        <p class="text-gray-800"><span class="text-gray-400">Alamat Resmi:</span> Perum Pesona Griya
                            Indah, Jalan sinarbaya indah, Telukjambe Timur, Kab. Karawang, Jawa Barat, Indonesia</p>
                    </div>
                </section>
            </div>
        </div>
    </main>

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
                        class="text-[10px] font-black text-emerald-800 border-b-2 border-emerald-800 pb-1 uppercase tracking-[0.2em]">Terms</a>
                    <a href="{{ route('legal.refund') }}"
                        class="text-[10px] font-black text-gray-400 hover:text-emerald-800 transition-all uppercase tracking-[0.2em] border-b-2 border-transparent hover:border-emerald-800 pb-1">Refunds</a>
                    <a href="{{ route('legal.contact') }}"
                        class="text-[10px] font-black text-gray-400 hover:text-emerald-800 transition-all uppercase tracking-[0.2em] border-b-2 border-transparent hover:border-emerald-800 pb-1">Contact
                        Us</a>
                </div>
            </div>
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>

</html>