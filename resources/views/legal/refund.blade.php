<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Refund - Zeven Marketplace</title>
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
                <h1 class="text-5xl font-black text-emerald-955 mb-4 tracking-tighter uppercase">Kebijakan Refund &
                    Retur</h1>
                <p class="text-sm text-gray-400 font-medium font-mono">Terakhir Diperbarui: 2 Juni 2026</p>
                <p class="text-gray-500 mt-6 text-sm md:text-base leading-relaxed font-medium">
                    Di Zeven, kepuasan belanja dan kenyamanan finansial Anda adalah prioritas utama kami. Kebijakan
                    Refund & Retur ini dirancang secara transparan untuk memproteksi hak Pembeli (Buyer) dan Penjual
                    (Seller) secara adil apabila barang yang diterima tidak sesuai pesanan, cacat, atau rusak selama
                    proses ekspedisi.
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
                        Syarat Mutlak Pengajuan Retur
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Pembeli berhak mengajukan retur (pengembalian barang) atau refund (pengembalian dana)
                            apabila:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Barang yang diterima terbukti rusak fisik, cacat produksi, atau tidak berfungsi sama
                                sekali saat pertama kali dicoba.</li>
                            <li>Spesifikasi, tipe, warna, ukuran, atau model barang yang dikirimkan oleh Seller tidak
                                cocok/berbeda dengan deskripsi pesanan.</li>
                            <li>Syarat Mutlak: Pembeli wajib melampirkan rekaman video unboxing tanpa terputus (no
                                edit/no pause) dari awal segel paket dibuka hingga pengetesan produk untuk mencegah
                                klaim palsu.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 02 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">02</span>
                        Batas Waktu Pengajuan Retur
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Segala bentuk pengajuan retur barang wajib diajukan melalui sistem Zeven (Web atau APK) dalam
                            kurun waktu maksimal 24 jam sejak paket dinyatakan "Diterima" oleh sistem pelacakan jasa
                            pengiriman ekspedisi ketiga.</p>
                        <p>Jika batas waktu 24 jam tersebut telah terlewati dan pembeli telah mengeklik tombol
                            "Selesaikan Pesanan", maka transaksi dinyatakan sukses seutuhnya dan dana secara hukum
                            otomatis dicairkan ke dompet saldo toko Penjual (withdrawable balance).</p>
                    </div>
                </section>

                <!-- Section 03 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">03</span>
                        Sistem Saldo Rekening Penampung (Escrow)
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Zeven menggunakan sistem Rekening Penampung (Escrow) demi keamanan belanja Anda.</p>
                        <p>Saat Anda membayar belanjaan Anda, dana tersebut tidak langsung diberikan ke Penjual,
                            melainkan ditahan dengan aman oleh sistem perantara Zeven. Dana baru akan diteruskan ke
                            saldo toko Penjual setelah Anda mengonfirmasi produk telah diterima dengan baik, atau
                            setelah batas komplain 24 jam berakhir tanpa adanya sengketa retur.</p>
                    </div>
                </section>

                <!-- Section 04 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">04</span>
                        Metode Pengembalian Dana (DompetX)
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Jika komplain refund dinyatakan valid dan disetujui oleh Penjual atau penengah Admin,
                            pengembalian dana akan diproses secara aman:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Dana akan dikembalikan secara otomatis melalui sistem payment gateway DompetX langsung
                                ke sumber metode pembayaran awal yang Anda gunakan saat checkout (E-wallet
                                Dana/OVO/ShopeePay, transfer bank virtual account, dll).</li>
                            <li>Waktu pemrosesan transfer dana berkisar antara 3 hingga 7 hari kerja tergantung
                                kebijakan dari sistem perbankan/e-wallet bersangkutan.</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 05 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">05</span>
                        Ketentuan Ongkos Kirim Retur
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Biaya pengiriman barang dari alamat Pembeli kembali ke gudang alamat Penjual (ongkos kirim
                            retur) murni ditanggung oleh Pembeli, kecuali jika terdapat kesepakatan tertulis yang lain
                            antara Pembeli dan Penjual bahwa biaya tersebut akan diganti atau ditanggung oleh Penjual.
                        </p>
                        <p>Zeven tidak memfasilitasi penggantian biaya ongkos kirim fisik barang untuk keperluan
                            komplain/retur.</p>
                    </div>
                </section>

                <!-- Section 06 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">06</span>
                        Barang yang Tidak Dapat Dikembalikan
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm space-y-2 leading-relaxed">
                        <p>Demi menjaga higienitas dan kepatuhan hukum, barang-barang dengan kategori di bawah ini
                            tidak dapat diajukan retur atau refund dalam keadaan apa pun:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Produk makanan, minuman, kosmetik, atau obat-obatan yang segel kemasannya telah dibuka
                                oleh Pembeli.</li>
                            <li>Barang digital (e.g. kode voucher, akun digital) yang telah dikirimkan sukses ke
                                pembeli.</li>
                            <li>Barang yang rusak murni akibat kesalahan pemakaian/kelalaian Pembeli sendiri (terjatuh,
                                korsleting arus listrik, pecah saat dirakit, dll).</li>
                        </ul>
                    </div>
                </section>

                <!-- Section 07 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">07</span>
                        Mediasi dan Peran Admin
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm leading-relaxed">
                        <p>Apabila terjadi ketidaksepakatan sengketa retur antara Pembeli dan Penjual (misalnya Penjual
                            menolak retur meskipun bukti video unboxing valid), tim Administrator Zeven berhak bertindak
                            sebagai mediator independen. Keputusan akhir yang diambil oleh tim Administrator Zeven
                            didasarkan pada keabsahan bukti-bukti tertulis dan bersifat mutlak serta mengikat kedua
                            belah pihak.</p>
                    </div>
                </section>

                <!-- Section 08 -->
                <section class="space-y-3">
                    <h2
                        class="text-xl font-extrabold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-3">
                        <span
                            class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-mono text-sm">08</span>
                        Layanan Aduan Sengketa
                    </h2>
                    <div class="pl-11 text-gray-500 font-medium font-inter text-sm leading-relaxed space-y-1">
                        <p class="text-gray-800"><span class="text-gray-400">Tim Penengah Layanan:</span> Zeven Dispute
                            Resolutions</p>
                        <p class="text-gray-800"><span class="text-gray-400">Email Bantuan:</span>
                            ubp.event.management@gmail.com</p>
                        <p class="text-gray-800"><span class="text-gray-400">Alamat Korespondensi:</span> Perum Pesona
                            Griya Indah, Jalan sinarbaya indah, Telukjambe Timur, Kab. Karawang, Jawa Barat, Indonesia
                        </p>
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
                        class="text-[10px] font-black text-gray-400 hover:text-emerald-800 transition-all uppercase tracking-[0.2em] border-b-2 border-transparent hover:border-emerald-800 pb-1">Terms</a>
                    <a href="{{ route('legal.refund') }}"
                        class="text-[10px] font-black text-emerald-800 border-b-2 border-emerald-800 pb-1 uppercase tracking-[0.2em]">Refunds</a>
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