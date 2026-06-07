@extends('layouts.admin')

@section('header_title', 'Monitoring Produk')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-stat-card title="Total Produk" value="{{ $stats['total_products'] ?? 0 }}" icon="package"
                iconBgColor="bg-emerald-50" iconColor="text-emerald-600" />
            <x-stat-card title="Habis (Out of Stock)" value="{{ $stats['out_of_stock_products'] ?? 0 }}"
                icon="alert-octagon" iconBgColor="bg-red-100" iconColor="text-red-600" />
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-1">Manajemen Produk</h2>
                <p class="text-sm text-gray-500">Monitor seluruh produk yang ada di marketplace</p>
            </div>

            <div class="mb-6">
                <form id="filterForm" method="GET" action="{{ route('admin.products') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="Cari produk atau penjual..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white" autocomplete="off" />
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="productTable">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Produk</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Penjual</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 uppercase tracking-wider">Harga
                            </th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 uppercase tracking-wider">Stok
                            </th>
                            <th class="text-center py-3 px-4 text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 transition-all table-row">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                                            @if($product['image'] ?? null)
                                                @php
                                                    $prodImage = $product['image'];
                                                    $prodImageUrl = Str::startsWith($prodImage, 'http') 
                                                        ? $prodImage 
                                                        : (Str::startsWith($prodImage, 'storage/') 
                                                            ? env('BACKEND_URL') . '/' . $prodImage 
                                                            : env('BACKEND_URL') . '/storage/' . $prodImage);
                                                @endphp
                                                <img src="{{ $prodImageUrl }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i data-lucide="package" class="w-5 h-5 text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-sm font-bold text-gray-900 search-name">{{ $product['name'] }}</div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-sm text-gray-700 search-seller">
                                    {{ $product['seller']['name'] ?? 'Guest Seller' }}
                                </td>
                                <td class="py-4 px-4 text-sm font-bold text-amber-700">
                                    Rp {{ number_format($product['price'], 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-4 text-sm font-bold">
                                    @if($product['stock'] == 0)
                                        <span class="text-red-600 bg-red-50 px-2 py-1 rounded-lg">Habis</span>
                                    @elseif($product['stock'] <= 5)
                                        <span class="text-red-500 bg-red-50 px-2 py-1 rounded-lg">{{ $product['stock'] }}</span>
                                    @else
                                        <span class="text-gray-700">{{ $product['stock'] }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('admin.products.show', $product['id']) }}"
                                            class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-100 rounded-lg transition-all"
                                            title="Detail Produk">
                                            <i data-lucide="eye" class="w-4 h-4 text-emerald-600"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-500 text-sm italic">
                                    Belum ada produk yang terdaftar.
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
                                class="p-2 text-gray-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all"><i
                                    data-lucide="chevron-left" class="w-5 h-5"></i></a>
                        @endif

                        <div class="flex items-center gap-1">
                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <span
                                        class="w-9 h-9 flex items-center justify-center bg-amber-600 text-white rounded-lg text-xs font-bold shadow-sm shadow-amber-100">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:text-amber-700 rounded-lg text-xs font-medium transition-all">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}"
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