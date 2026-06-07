@extends('layouts.admin')

@section('header_title', 'Voucher & Promosi')

@section('content')
    <div class="space-y-6">
        {{-- Header & Action --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-1">Daftar Voucher Global</h2>
                <p class="text-sm text-gray-500">Kelola kupon diskon dan promosi di seluruh marketplace</p>
            </div>
            <a href="{{ route('admin.vouchers.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition-all font-medium shadow-sm shadow-amber-200 active:scale-95">
                <i data-lucide="plus" class="w-5 h-5 text-emerald-900 font-bold"></i>
                Buat Voucher Baru
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 p-4 bg-red-50 text-red-600 rounded-xl border border-red-100">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <form id="filterForm" method="GET" action="{{ route('admin.vouchers') }}" class="relative">
                            <input type="hidden" name="status" id="statusHiddenInput" value="{{ $status ?? 'all' }}" />
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="text" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="Cari kode voucher..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white" autocomplete="off" />
                        </form>
                    </div>

                    <div class="relative w-full md:w-60" x-data="{ 
                        open: false, 
                        selected: '{{ [
                            'all' => 'Semua Voucher',
                            'active' => 'Voucher Aktif',
                            'expired' => 'Voucher Expired',
                            'full' => 'Kuota Habis'
                        ][$status ?? 'all'] }}', 
                        value: '{{ $status ?? 'all' }}',
                        options: [
                            { label: 'Semua Voucher', value: 'all', count: {{ $totalCount }} },
                            { label: 'Voucher Aktif', value: 'active', count: {{ $activeCount }} },
                            { label: 'Voucher Expired', value: 'expired', count: {{ $expiredCount }} },
                            { label: 'Kuota Habis', value: 'full', count: {{ $fullCount }} }
                        ],
                        select(opt) {
                            this.selected = opt.label;
                            this.value = opt.value;
                            this.open = false;
                            document.getElementById('statusHiddenInput').value = opt.value;
                            document.getElementById('filterForm').submit();
                        }
                    }" @click.away="open = false">
                        <div @click="open = !open" class="custom-select-trigger shadow-sm border-gray-100 bg-white">
                            <span class="text-sm font-bold text-gray-700" x-text="selected"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                        </div>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                             class="custom-select-menu" 
                             style="display: none;">
                            <template x-for="opt in options" :key="opt.value">
                                <div @click="select(opt)" 
                                     class="custom-select-item" 
                                     :class="value === opt.value ? 'active' : ''">
                                    <span x-text="opt.label"></span>
                                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600" x-show="value === opt.value"></i>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="voucherTable">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Diskon (%)</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Maks. Potongan</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kupon (Terpakai/Total)</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Masa Berlaku</th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-center py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($vouchers as $voucher)
                            <tr class="hover:bg-gray-50 transition-all table-row">
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg font-mono font-bold text-sm search-code">
                                        {{ $voucher['code'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $voucher['discount_percent'] }}%</td>
                                <td class="py-4 px-6 text-sm text-gray-700">Rp {{ number_format($voucher['max_discount'] ?? 0, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-sm text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-1.5 w-16 bg-gray-100 rounded-full overflow-hidden">
                                            @php
                                                $quota = $voucher['quota'] ?? 1;
                                                $used = $voucher['used'] ?? 0;
                                                $progress = ($quota > 0) ? ($used / $quota) * 100 : 0;
                                            @endphp
                                            <div class="bg-amber-500 h-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span>{{ $used }}/{{ $quota }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-gray-500">
                                    @if(!empty($voucher['start_date']) && !empty($voucher['end_date']))
                                        {{ \Carbon\Carbon::parse($voucher['start_date'])->format('d M') }} - {{ \Carbon\Carbon::parse($voucher['end_date'])->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $endDate = !empty($voucher['end_date']) ? \Carbon\Carbon::parse($voucher['end_date']) : null;
                                        $isExpired = $endDate ? $endDate->isPast() : false;
                                        $isFull = ($voucher['used'] ?? 0) >= ($voucher['quota'] ?? 0);
                                    @endphp
                                    @if($isExpired)
                                        <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-[10px] font-bold uppercase">Expired</span>
                                    @elseif($isFull)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-bold uppercase">Full</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-bold uppercase">Active</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <form id="deleteForm-{{ $voucher['id'] }}" action="{{ route('admin.vouchers.delete', $voucher['id']) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="deleteVoucher({{ $voucher['id'] }}, '{{ $voucher['code'] }}')"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                            title="Hapus Voucher">
                                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400 text-sm italic">
                                    Belum ada voucher yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($vouchers->hasPages())
                <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-center p-6 bg-gray-50/30">
                    <div class="flex items-center gap-2">
                        @if($vouchers->onFirstPage())
                            <span class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-left"
                                    class="w-5 h-5"></i></span>
                        @else
                            <a href="{{ $vouchers->previousPageUrl() }}"
                                class="p-2 text-gray-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all"><i
                                    data-lucide="chevron-left" class="w-5 h-5"></i></a>
                        @endif

                        <div class="flex items-center gap-1">
                            @foreach ($vouchers->getUrlRange(1, $vouchers->lastPage()) as $page => $url)
                                @if ($page == $vouchers->currentPage())
                                    <span
                                        class="w-9 h-9 flex items-center justify-center bg-amber-600 text-white rounded-lg text-xs font-bold shadow-sm shadow-amber-100">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:text-amber-700 rounded-lg text-xs font-medium transition-all">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if($vouchers->hasMorePages())
                            <a href="{{ $vouchers->nextPageUrl() }}"
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

            function deleteVoucher(id, code) {
                Swal.fire({
                    title: 'Hapus Voucher ' + code + '?',
                    text: "Apakah Anda benar-benar yakin ingin menghapus voucher ini? Kupon diskon ini tidak akan dapat digunakan kembali.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3 font-bold',
                        cancelButton: 'rounded-xl px-6 py-3 font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm-' + id).submit();
                    }
                })
            }
        </script>
    @endpush
@endsection