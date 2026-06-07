<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - Zeven Marketplace</title>
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
            class="flex items-center gap-3 text-emerald-700 font-black hover:text-emerald-950 transition-all group text-xs uppercase tracking-widest">
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
                <h1 class="text-5xl font-black text-emerald-950 mb-4 tracking-tighter uppercase">Kebijakan Privasi</h1>
                <p class="text-sm text-gray-400 font-medium font-mono">Terakhir Diperbarui: 2 Juni 2026</p>
                <p class="text-gray-500 mt-6 text-sm md:text-base leading-relaxed font-medium">
                    Selamat datang di Zeven. Kebijakan Privasi ini dirancang untuk membantu Anda memahami bagaimana
                    platform Zeven (baik melalui website resmi maupun Aplikasi Mobile/APK Android kami) mengumpulkan,
                    menggunakan, menyimpan, dan melindungi informasi pribadi Anda secara aman demi kelancaran ekosistem
                    marketplace sosial e-commerce kami. Kebijakan ini berlaku sepenuhnya untuk seluruh pengguna, baik
                    sebagai Pembeli (Buyer), Penjual (Seller), maupun Administrator.
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
                        Pengumpulan Informasi
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Kami mengumpulkan informasi yang Anda berikan secara langsung saat menggunakan platform kami,
                            termasuk:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Informasi Akun</strong>: Nama lengkap, alamat email, nomor telepon, foto profil,
                                dan kata sandi yang dienkripsi.</li>
                            <li><strong>Informasi Toko (Seller)</strong>: Nama toko, detail rekening bank untuk
                                keperluan penarikan saldo penjualan.</li>
                            <li><strong>Informasi Pengiriman</strong>: Alamat pengiriman lengkap untuk keperluan
                                ekspedisi barang dari Seller ke Buyer.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 02 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">02</span>
                        Izin Perangkat APK Android
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Untuk pengguna Aplikasi Mobile / APK Zeven di Android, kami memerlukan beberapa izin
                            perangkat agar fitur dapat berjalan optimal:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Kamera</strong>: Untuk memotret produk langsung (Seller) dan melampirkan bukti
                                transaksi.</li>
                            <li><strong>Galeri & Penyimpanan</strong>: Untuk mengunggah berkas gambar produk dan bukti
                                pembayaran dari galeri.</li>
                            <li><strong>Jaringan & Internet</strong>: Untuk sinkronisasi data real-time dengan server
                                Zeven.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 03 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">03</span>
                        Transaksi & DompetX
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Seluruh proses pembayaran transaksi belanja dilakukan melalui enkripsi tingkat tinggi oleh
                            <strong>DompetX</strong> selaku partner payment gateway resmi kami.
                        </p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Sistem Zeven tidak pernah menyimpan detail kartu kredit, kartu debit, atau
                                pembayaran sensitif Anda di server database kami.</li>
                            <li>Seluruh pertukaran data transaksi menggunakan tokenisasi aman yang dilindungi langsung
                                oleh sistem enkripsi DompetX.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 04 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">04</span>
                        Google Sign-In
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Platform Zeven mendukung integrasi Google Sign-In untuk mempermudah pendaftaran dan login
                            akun:
                        </p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Kami hanya mengumpulkan informasi dasar yang diizinkan oleh Google, seperti nama
                                lengkap, alamat email, dan URL foto profil Anda.</li>
                            <li>Informasi ini digunakan semata-mata untuk memverifikasi identitas Anda dan membuat akun
                                Zeven Anda secara instan.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 05 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">05</span>
                        Keamanan Data
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Kami menerapkan langkah-langkah teknis dan administratif yang kuat untuk menjaga keamanan
                            data pribadi Anda:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Semua kata sandi akun dienkripsi menggunakan algoritma hashing modern yang aman.</li>
                            <li>Semua transfer data antara web, aplikasi seluler, dan server backend kami dilindungi
                                oleh enkripsi SSL/TLS (HTTPS).</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 06 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">06</span>
                        Penghapusan Akun & Data
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Sesuai ketentuan Google Play Store, kami memberikan hak penuh kepada pengguna untuk
                            meminta penghapusan akun beserta data pribadi terkait secara permanen:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Ajukan permohonan penghapusan akun ke email resmi <a
                                    href="mailto:ubp.event.management@gmail.com"
                                    class="text-emerald-700 hover:underline font-bold">ubp.event.management@gmail.com</a>.
                            </li>
                            <li>Seluruh data pribadi Anda (nama, email, alamat, dan riwayat chat) akan dihapus secara
                                permanen dari database utama kami dalam waktu 3x24 jam.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 07 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">07</span>
                        Perubahan Kebijakan
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm leading-relaxed">
                        <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu untuk menyesuaikan dengan
                            perubahan operasional platform, kepatuhan Play Store, atau regulasi hukum yang berlaku. Jika
                            terdapat perubahan signifikan, kami akan memberitahukan Anda melalui notifikasi aplikasi
                            atau email resmi.</p>
                    </div>
                </section>

                <!-- Section 08 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">08</span>
                        Kontak & Bantuan
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm leading-relaxed space-y-1">
                        <p class="text-gray-800"><span class="text-gray-400">Tim Pengembang:</span> ZevenDev</p>
                        <p class="text-gray-800"><span class="text-gray-400">Email Bantuan:</span>
                            ubp.event.management@gmail.com</p>
                        <p class="text-gray-800"><span class="text-gray-400">Alamat:</span> Perum Pesona Griya Indah,
                            Jalan sinarbaya indah, Telukjambe Timur, Kab. Karawang, Jawa Barat, Indonesia</p>
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
                        class="text-[10px] font-black text-emerald-800 border-b-2 border-emerald-800 pb-1 uppercase tracking-[0.2em]">Privacy
                        Policy</a>
                    <a href="{{ route('legal.terms') }}"
                        class="text-[10px] font-black text-gray-400 hover:text-emerald-800 transition-all uppercase tracking-[0.2em] border-b-2 border-transparent hover:border-emerald-800 pb-1">Terms</a>
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