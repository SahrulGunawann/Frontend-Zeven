@extends('layouts.seller')

@section('header_title', 'Keuangan & Saldo Toko')

@section('content')
    <div class="space-y-6">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 shadow-sm animate-fade-in">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-600"></i>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 shadow-sm animate-fade-in">
                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 text-red-500"></i>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Saldo Wallet Toko (Bisa Ditarik) -->
        <div class="bg-gradient-to-br from-emerald-800 via-emerald-900 to-teal-950 rounded-3xl p-8 shadow-xl border border-emerald-950 text-white relative overflow-hidden group">
            <!-- Background Decorative Glows -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-teal-500/10 rounded-full blur-3xl group-hover:bg-teal-500/20 transition-all duration-700"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/10 shrink-0">
                        <i data-lucide="wallet" class="w-7 h-7 text-amber-300"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-emerald-300 font-extrabold uppercase tracking-widest">Saldo Toko Yang Bisa Ditarik</p>
                        <h3 class="text-4xl font-extrabold text-white mt-1 tracking-tight">{{ $stats['withdrawable_balance'] }}</h3>
                        <div class="flex items-center gap-2 mt-2 text-xs text-emerald-200">
                            <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Sudah terpotong biaya platform 2% (sebesar {{ $stats['platform_fees'] }}) dan penarikan sebelumnya</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if(isset($stats['withdrawable_balance_raw']) && $stats['withdrawable_balance_raw'] >= 10000)
                        <button onclick="openWithdrawModal()" class="px-8 py-4 bg-gradient-to-r from-amber-400 to-amber-500 text-gray-950 font-black rounded-2xl hover:from-amber-300 hover:to-amber-400 active:scale-95 transition-all shadow-lg shadow-amber-500/10 flex items-center gap-2 border border-amber-300/30">
                            <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                            Tarik Saldo
                        </button>
                    @else
                        <button disabled class="px-8 py-4 bg-white/5 text-gray-400 font-bold rounded-2xl cursor-not-allowed border border-white/5 flex items-center gap-2">
                            <i data-lucide="info" class="w-5 h-5"></i>
                            Min. Tarik Rp 10.000
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3-Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pendapatan Bruto</span>
                    <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                        <i data-lucide="trending-up" class="w-4 h-4"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['total_revenue'] }}</h3>
                <p class="text-xs text-gray-500 font-medium">Dari pesanan berstatus <span class="text-green-600 font-bold">Selesai</span></p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pendapatan Tertunda</span>
                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['pending_revenue'] }}</h3>
                <p class="text-xs text-gray-500 font-medium">Pesanan belum selesai / dikirim</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah Ditarik</span>
                    <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                        <i data-lucide="arrow-down-right" class="w-4 h-4"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['total_withdrawn'] }}</h3>
                <p class="text-xs text-gray-500 font-medium">Berhasil & sedang diajukan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Riwayat Transaksi -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                <div>
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                                <i data-lucide="activity" class="w-5 h-5 text-emerald-600"></i>
                                Riwayat Transaksi Penjualan
                            </h2>
                            <p class="text-xs text-gray-500">Daftar pemasukan kotor dan potongan dari pesanan</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 text-left">
                                    <th class="py-3 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Transaksi</th>
                                    <th class="py-3 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pelanggan</th>
                                    <th class="py-3 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bruto</th>
                                    <th class="py-3 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Netto (98%)</th>
                                    <th class="py-3 px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($transactions as $trx)
                                    <tr class="hover:bg-gray-50/50 transition-all">
                                        <td class="py-4 px-3">
                                            <span class="text-xs font-bold text-gray-800">{{ $trx['order_number'] }}</span>
                                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $trx['date'] }}</p>
                                        </td>
                                        <td class="py-4 px-3 text-xs text-gray-600 font-medium">{{ $trx['customer'] }}</td>
                                        <td class="py-4 px-3 text-xs text-gray-500 font-medium">{{ $trx['amount'] }}</td>
                                        <td class="py-4 px-3 text-xs text-emerald-700 font-bold text-center bg-emerald-50/30 rounded-xl">{{ $trx['net'] }}</td>
                                        <td class="py-4 px-3 text-right">
                                            <span class="px-2 py-0.5 rounded-md border {{ $trx['status'] === 'completed' ? 'bg-green-50 text-green-600 border-green-100' : ($trx['status'] === 'shipped' ? 'bg-purple-50 text-purple-600 border-purple-100' : 'bg-amber-50 text-amber-600 border-amber-100') }} text-[9px] font-bold uppercase tracking-wider">
                                                {{ $trx['status'] === 'completed' ? 'Selesai' : ($trx['status'] === 'shipped' ? 'Dikirim' : 'Proses') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-16 text-center">
                                            <i data-lucide="receipt" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                                            <p class="text-xs text-gray-500">Belum ada riwayat transaksi penjualan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($transactions->hasPages())
                    <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-center animate-fade-in">
                        <div class="flex items-center gap-2">
                            @if($transactions->onFirstPage())
                                <span class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-left" class="w-5 h-5"></i></span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}" class="p-2 text-gray-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all"><i data-lucide="chevron-left" class="w-5 h-5"></i></a>
                            @endif

                            <div class="flex items-center gap-1">
                                @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                    @if ($page == $transactions->currentPage())
                                        <span class="w-9 h-9 flex items-center justify-center bg-emerald-700 text-white rounded-lg text-xs font-bold">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 rounded-lg text-xs font-medium transition-all">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>

                            @if($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}" class="p-2 text-gray-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all"><i data-lucide="chevron-right" class="w-5 h-5"></i></a>
                            @else
                                <span class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-right" class="w-5 h-5"></i></span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right 1 Col: Riwayat Penarikan Saldo -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between h-full">
                <div>
                    <div class="mb-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-1 flex items-center gap-2">
                            <i data-lucide="history" class="w-5 h-5 text-amber-500"></i>
                            Riwayat Penarikan Saldo
                        </h2>
                        <p class="text-xs text-gray-500">Pantau status pencairan uang Anda ke rekening bank</p>
                    </div>

                    <div class="space-y-4 h-[850px] overflow-y-auto pr-1">
                        @forelse($withdrawals ?? [] as $w)
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-900">{{ $w['amount'] }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide {{ $w['status'] === 'completed' ? 'bg-green-100 text-green-700' : ($w['status'] === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                        {{ $w['status'] === 'completed' ? 'Sukses' : ($w['status'] === 'rejected' ? 'Ditolak' : 'Proses') }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-gray-500 space-y-1">
                                    <p class="flex items-center justify-between">
                                        <span>Bank:</span>
                                        <span class="font-bold text-gray-700">{{ $w['bank_name'] }}</span>
                                    </p>
                                    <p class="flex items-center justify-between">
                                        <span>No. Rekening:</span>
                                        <span class="font-mono text-gray-700">{{ $w['account_number'] }}</span>
                                    </p>
                                    <p class="flex items-center justify-between">
                                        <span>Penerima:</span>
                                        <span class="font-bold text-gray-700">{{ $w['account_name'] }}</span>
                                    </p>
                                    @if($w['status'] === 'rejected' && !empty($w['rejected_reason']))
                                        <p class="p-2 bg-red-50 text-red-600 rounded-lg mt-1 font-bold">
                                            Alasan: {{ $w['rejected_reason'] }}
                                        </p>
                                    @endif
                                    @if(!empty($w['proof_image']))
                                        <div class="mt-2">
                                            <a href="{{ $w['proof_image'] }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold {{ $w['status'] === 'rejected' ? 'text-rose-700 hover:text-rose-950 bg-rose-50 border-rose-100' : 'text-emerald-700 hover:text-emerald-950 bg-emerald-50 border-emerald-100' }} transition-colors px-2.5 py-1 rounded-lg border">
                                                <i data-lucide="image" class="w-3.5 h-3.5"></i>
                                                {{ $w['status'] === 'rejected' ? 'Lihat Bukti Penolakan' : 'Lihat Bukti Transfer' }}
                                            </a>
                                        </div>
                                    @endif
                                    @if(!empty($w['admin_note']))
                                        <div class="mt-1.5 p-2.5 bg-gray-100 text-gray-700 text-[10px] rounded-lg font-medium leading-normal border border-gray-200/50">
                                            <strong>Catatan Admin:</strong> {{ $w['admin_note'] }}
                                        </div>
                                    @endif
                                </div>
                                <div class="text-[9px] text-gray-400 text-right mt-1 border-t border-gray-200/50 pt-1">
                                    Diajukan: {{ $w['date'] }}
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center">
                                <i data-lucide="landmark" class="w-10 h-10 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-xs text-gray-500">Belum ada riwayat penarikan saldo.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
    </div>

    <!-- Withdraw Modal -->
    <div id="withdrawModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modalContainer">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h3 class="font-black text-gray-900 text-lg flex items-center gap-2">
                    <i data-lucide="wallet-cards" class="w-5 h-5 text-emerald-600"></i>
                    Tarik Saldo Toko
                </h3>
                <button onclick="closeWithdrawModal()" class="w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 shadow-sm active:scale-90 transition-all">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form action="{{ route('seller.withdraw') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Penarikan (IDR)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-extrabold text-sm">Rp</span>
                        <input type="number" name="amount" min="10000" max="{{ $stats['withdrawable_balance_raw'] }}" required placeholder="Contoh: 50000" class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-black text-lg text-gray-900">
                    </div>
                    <div class="flex items-center justify-between mt-1 text-[10px] text-gray-400 font-medium">
                        <span>Minimal penarikan Rp 10.000</span>
                        <span>Maksimal: <strong class="text-emerald-700">{{ $stats['withdrawable_balance'] }}</strong></span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Bank / E-Wallet</label>
                    <select name="bank_name" required class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-bold bg-white cursor-pointer text-gray-800">
                        <option value="">-- Pilih Rekening --</option>
                        <option value="BCA">BCA (Bank Central Asia)</option>
                        <option value="Mandiri">Bank Mandiri</option>
                        <option value="BRI">BRI (Bank Rakyat Indonesia)</option>
                        <option value="BNI">BNI (Bank Negara Indonesia)</option>
                        <option value="GOPAY">GoPay</option>
                        <option value="OVO">OVO</option>
                        <option value="DANA">DANA</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor Rekening / No. HP</label>
                    <input type="text" name="account_number" required placeholder="Masukkan nomor rekening atau No. HP" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" required placeholder="Masukkan nama pemilik rekening secara detail" class="w-full px-4 py-3.5 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-bold text-gray-800">
                </div>
                <div class="pt-4 flex items-center gap-3">
                    <button type="button" onclick="closeWithdrawModal()" class="flex-1 py-3.5 border border-gray-200 rounded-2xl font-bold text-gray-700 text-sm hover:bg-gray-50 active:scale-95 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-2xl font-black text-sm active:scale-95 transition-all shadow-lg shadow-emerald-500/10">
                        Ajukan Penarikan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openWithdrawModal() {
            const modal = document.getElementById('withdrawModal');
            const container = document.getElementById('modalContainer');
            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeWithdrawModal() {
            const modal = document.getElementById('withdrawModal');
            const container = document.getElementById('modalContainer');
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endpush