@extends('layouts.admin')

@section('header_title', 'Monitoring Transaksi')

    @section('content')
        <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-stat-card title="Total Transaksi" value="{{ count($unfilteredOrders) }}" icon="receipt" iconBgColor="bg-gray-100"
                iconColor="text-gray-600" />
            <x-stat-card title="Volume" value="Rp{{ number_format(collect($unfilteredOrders)->sum('final_price')/1000, 0) }}k"
                icon="trending-up" iconBgColor="bg-amber-50" iconColor="text-amber-600" />
            <x-stat-card title="Pending" value="{{ collect($unfilteredOrders)->where('status', 'pending')->count() }}" icon="clock"
                iconBgColor="bg-emerald-50" iconColor="text-emerald-600" />
            <x-stat-card title="Proses" value="{{ collect($unfilteredOrders)->where('status', 'processed')->count() }}" icon="refresh-cw"
                iconBgColor="bg-blue-50" iconColor="text-blue-600" />
            <x-stat-card title="Dikirim" value="{{ collect($unfilteredOrders)->where('status', 'shipped')->count() }}" icon="truck"
                iconBgColor="bg-indigo-50" iconColor="text-indigo-600" />
            <x-stat-card title="Selesai" value="{{ collect($unfilteredOrders)->where('status', 'completed')->count() }}" icon="check-circle"
                iconBgColor="bg-green-50" iconColor="text-green-600" />
        </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Monitoring Transaksi</h2>
                        <p class="text-sm text-gray-500">Kelola dan pantau semua aliran dana marketplace</p>
                    </div>
                    <form id="filterForm" method="GET" action="{{ route('admin.transactions') }}">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="text" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="Cari ID, Buyer, atau Seller..."
                                class="w-full md:w-64 pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white" autocomplete="off">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full" id="transactionTable">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Order
                                    ID</th>
                                <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Alur
                                    Transaksi</th>
                                <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Total
                                    Pembayaran</th>
                                <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal
                                </th>
                                <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                                </th>
                                <th class="text-center py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                @php
                                    $status = strtolower($order['status']);
                                    $badgeClass = match ($status) {
                                        'completed', 'success' => 'bg-emerald-100 text-emerald-600',
                                        'pending' => 'bg-amber-100 text-amber-600',
                                        'cancelled', 'failed' => 'bg-red-100 text-red-600',
                                        default => 'bg-gray-100 text-gray-600'
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-all table-row">
                                    <td class="py-4 px-6">
                                        <span
                                            class="text-sm font-bold text-gray-900 search-id">#ORD-{{ str_pad($order['id'], 5, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2">
                                            <div class="text-left">
                                                <p class="text-xs text-gray-400 font-medium uppercase tracking-tighter">Buyer</p>
                                                <p class="text-sm font-bold text-gray-900 search-buyer">
                                                    {{ $order['buyer']['name'] ?? 'Unknown' }}</p>
                                            </div>
                                            <i data-lucide="arrow-right" class="w-3 h-3 text-gray-300"></i>
                                            <div class="text-left">
                                                <p class="text-xs text-gray-400 font-medium uppercase tracking-tighter">Seller</p>
                                                <p class="text-sm font-bold text-gray-700 search-seller">
                                                    {{ $order['seller']['name'] ?? 'Unknown' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <p class="text-sm font-bold text-amber-700">Rp
                                            {{ number_format($order['final_price'], 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-gray-400">Termasuk ongkir</p>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 {{ $badgeClass }} rounded-full text-[10px] font-bold uppercase">
                                            {{ $order['status'] }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center justify-center">
                                            <a href="{{ route('admin.transactions.show', $order['id']) }}"
                                                class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                                title="Detail Transaksi">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-500 text-sm italic">
                                        Belum ada data transaksi yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-center p-6 bg-gray-50/30">
                        <div class="flex items-center gap-2">
                            @if($orders->onFirstPage())
                                <span class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-left"
                                        class="w-5 h-5"></i></span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}"
                                    class="p-2 text-gray-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all"><i
                                        data-lucide="chevron-left" class="w-5 h-5"></i></a>
                            @endif

                            <div class="flex items-center gap-1">
                                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                    @if ($page == $orders->currentPage())
                                        <span
                                            class="w-9 h-9 flex items-center justify-center bg-amber-600 text-white rounded-lg text-xs font-bold shadow-sm shadow-amber-100">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:text-amber-700 rounded-lg text-xs font-medium transition-all">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>

                            @if($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}"
                                    class="p-2 text-gray-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all"><i
                                        data-lucide="chevron-right" class="w-5 h-5"></i></a>
                            @else
                                <span class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-right"
                                        class="w-5 h-5"></i></span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @push('scripts')
            <script>
                // Live Search with Debounce
                let searchTimer;
                const searchInput = document.getElementById('searchInput');
                const filterForm = document.getElementById('filterForm');

                if (searchInput) {
                    searchInput.addEventListener('input', function () {
                        clearTimeout(searchTimer);
                        searchTimer = setTimeout(() => {
                            filterForm.submit();
                        }, 500); // Tunggu 500ms
                    });

                    // Pastikan kursor tetap di akhir teks setelah reload
                    if (searchInput.value) {
                        searchInput.focus();
                        searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
                    }
                }
            </script>
        @endpush
    @endsection