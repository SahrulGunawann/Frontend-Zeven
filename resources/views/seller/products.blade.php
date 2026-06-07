@extends('layouts.seller')

@section('header_title', 'Manajemen Produk')

@section('content')
    <div class="space-y-6">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-3 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 shadow-sm">
                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">Produk Toko</h2>
                    <p class="text-sm text-gray-500">Kelola inventaris dan pantau stok produk toko Anda</p>
                </div>
                <a href="{{ route('seller.add_product') }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-800 text-white rounded-xl font-bold hover:from-emerald-700 hover:to-emerald-900 transition-all shadow-md active:scale-95">
                    <i data-lucide="plus" class="w-5 h-5 text-amber-400"></i>
                    Tambah Produk Baru
                </a>
            </div>

            <div class="p-6 bg-gray-50/50 border-b border-gray-100">
                <form id="filterForm" method="GET" action="{{ route('seller.products') }}"
                    class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" id="searchInput" name="search" value="{{ $search ?? '' }}"
                            placeholder="Cari nama produk..."
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all bg-white"
                            autocomplete="off">
                    </div>
                    <div class="lg:w-48 relative" x-data="{ 
                        open: false, 
                        selected: '{{ ($status ?? '') === 'active' ? 'Aktif' : (($status ?? '') === 'pending' ? 'Habis' : 'Semua Status') }}', 
                        value: '{{ $status ?? '' }}',
                        options: [
                            { label: 'Semua Status', value: '' },
                            { label: 'Aktif', value: 'active' },
                            { label: 'Habis', value: 'pending' }
                        ],
                        select(opt) {
                            this.selected = opt.label;
                            this.value = opt.value;
                            this.open = false;
                            
                            // Penting: Update hidden input dan submit form
                            const hiddenInput = document.getElementById('statusHiddenInput');
                            if(hiddenInput) {
                                hiddenInput.value = opt.value;
                                document.getElementById('filterForm').submit();
                            }
                        }
                    }" @click.away="open = false">
                        <div @click="open = !open" class="custom-select-trigger shadow-sm border-gray-100">
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
                        <input type="hidden" name="status" id="statusHiddenInput" :value="value">
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/30">
                            <th class="text-left py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Produk</th>
                            <th class="text-left py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Kategori</th>
                            <th class="text-left py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Harga</th>
                            <th class="text-left py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Stok</th>
                            <th class="text-left py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Terjual</th>
                            <th class="text-left py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Status</th>
                            <th class="text-center py-4 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden shrink-0 flex items-center justify-center">
                                            @if($product['image'] ?? null)
                                                <img src="{{ $product['image'] }}" class="w-full h-full object-cover">
                                            @else
                                                <i data-lucide="package" class="w-5 h-5 text-gray-400"></i>
                                            @endif
                                        </div>
                                        <span class="text-sm font-bold text-gray-900 line-clamp-1">{{ $product['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold uppercase tracking-tighter">
                                        {{ $product['category'] ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm font-bold text-emerald-700">{{ $product['price'] }}</td>
                                <td
                                    class="py-4 px-6 text-sm font-bold {{ $product['stock'] <= 5 ? 'text-red-500' : 'text-gray-700' }}">
                                    {{ $product['stock'] }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-medium">{{ $product['sales'] }}</td>
                                <td class="py-4 px-6">
                                    <span
                                        class="px-2.5 py-1 {{ $product['status'] === 'active' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-700' }} rounded-full text-[10px] font-bold uppercase tracking-tighter">
                                        {{ $product['status'] === 'active' ? 'Aktif' : 'Habis' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('seller.products.edit', $product['id']) }}"
                                            class="p-2 text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <button onclick="confirmDelete('{{ $product['id'] }}', '{{ $product['name'] }}')"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                        <form id="delete-form-{{ $product['id'] }}"
                                            action="{{ route('seller.products.delete', $product['id']) }}" method="POST"
                                            class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center">
                                    <i data-lucide="package-search" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
                                    <h3 class="text-sm font-bold text-gray-900">Belum ada produk</h3>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-center p-6 bg-gray-50/30">
                    <div class="flex items-center gap-2">
                        @if($products->onFirstPage())
                            <span class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-left"
                                    class="w-5 h-5"></i></span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}"
                                class="p-2 text-gray-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all"><i
                                    data-lucide="chevron-left" class="w-5 h-5"></i></a>
                        @endif

                        <div class="flex items-center gap-1">
                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <span
                                        class="w-9 h-9 flex items-center justify-center bg-emerald-700 text-white rounded-lg text-xs font-bold">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 rounded-lg text-xs font-medium transition-all">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}"
                                class="p-2 text-gray-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all"><i
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
@endsection

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

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Apakah Anda yakin ingin menghapus '" + name + "'?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669', /* Emerald 600 */
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush