@extends('layouts.admin')

@section('header_title', 'Detail Transaksi')

@section('content')
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-amber-600 transition-all">
            <i data-lucide="arrow-left" class="w-4 h-4 text-emerald-600 font-bold"></i>
            Kembali ke Halaman Sebelumnya
        </a>
    </div>

    <div class="space-y-6">
        <!-- Main Info Card -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-8 pb-8 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center">
                        <i data-lucide="receipt" class="w-7 h-7 text-amber-600"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Order #ORD-{{ str_pad($order['id'], 5, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    @php
                        $status = strtolower($order['status']);
                        $badgeClass = match ($status) {
                            'completed', 'success' => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                            'pending' => 'bg-amber-100 text-amber-600 border-amber-200',
                            'cancelled', 'failed' => 'bg-red-100 text-red-600 border-red-200',
                            'processed' => 'bg-blue-100 text-blue-600 border-blue-200',
                            'shipped' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            default => 'bg-gray-100 text-gray-600 border-gray-200'
                        };
                    @endphp
                    <span class="px-4 py-1.5 {{ $badgeClass }} border rounded-full text-xs font-bold uppercase tracking-widest shadow-sm">
                        {{ $order['status'] }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Buyer Info -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Pembeli</h3>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-50">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold overflow-hidden">
                            @if($order['buyer']['profile_image'] ?? null)
                                @php
                                    $buyerImage = $order['buyer']['profile_image'];
                                    $buyerImageUrl = Str::startsWith($buyerImage, 'http') 
                                        ? $buyerImage 
                                        : (Str::startsWith($buyerImage, 'storage/') 
                                            ? env('BACKEND_URL') . '/' . $buyerImage 
                                            : env('BACKEND_URL') . '/storage/' . $buyerImage);
                                @endphp
                                <img src="{{ $buyerImageUrl }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($order['buyer']['name'] ?? 'B', 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $order['buyer']['name'] ?? 'Unknown Buyer' }}</p>
                            <p class="text-xs text-gray-500">{{ $order['buyer']['phone'] ?? 'No Phone' }}</p>
                        </div>
                        @if($order['buyer_id'])
                        <a href="{{ route('admin.users.show', $order['buyer_id']) }}" class="ml-auto p-2 text-gray-400 hover:text-amber-600 hover:bg-white rounded-lg transition-all shadow-sm">
                            <i data-lucide="eye" class="w-4 h-4 text-emerald-600"></i>
                        </a>
                        @endif
                    </div>
                    <div class="mt-4 px-4 py-3 bg-white rounded-2xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-2">Alamat Pengiriman</p>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $order['shipping_address'] ?? 'Tidak ada data alamat.' }}</p>
                    </div>
                </div>

                <!-- Seller Info -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Penjual</h3>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-50">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 font-bold overflow-hidden border border-amber-100">
                             @if($order['seller']['profile_image'] ?? null)
                                @php
                                    $sellerImage = $order['seller']['profile_image'];
                                    $sellerImageUrl = Str::startsWith($sellerImage, 'http') 
                                        ? $sellerImage 
                                        : (Str::startsWith($sellerImage, 'storage/') 
                                            ? env('BACKEND_URL') . '/' . $sellerImage 
                                            : env('BACKEND_URL') . '/storage/' . $sellerImage);
                                @endphp
                                <img src="{{ $sellerImageUrl }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($order['seller']['name'] ?? 'U', 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $order['seller']['name'] ?? 'Unknown Seller' }}</p>
                            <p class="text-xs text-gray-500">{{ $order['seller']['phone'] ?? 'No Phone' }}</p>
                        </div>
                        @if($order['seller_id'])
                        <a href="{{ route('admin.users.show', $order['seller_id']) }}" class="ml-auto p-2 text-gray-400 hover:text-amber-600 hover:bg-white rounded-lg transition-all shadow-sm">
                            <i data-lucide="eye" class="w-4 h-4 text-emerald-600"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Item Pesanan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="py-4 px-8 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk</th>
                            <th class="py-4 px-8 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Harga</th>
                            <th class="py-4 px-8 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jumlah</th>
                            <th class="py-4 px-8 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($order['items'] ?? [] as $item)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="py-4 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden shrink-0">
                                        @if($item['product']['image'] ?? null)
                                            @php
                                                $prodImage = $item['product']['image'];
                                                $prodImageUrl = Str::startsWith($prodImage, 'http') 
                                                    ? $prodImage 
                                                    : (Str::startsWith($prodImage, 'storage/') 
                                                        ? env('BACKEND_URL') . '/' . $prodImage 
                                                        : env('BACKEND_URL') . '/storage/' . $prodImage);
                                            @endphp
                                            <img src="{{ $prodImageUrl }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center"><i data-lucide="package" class="w-4 h-4 text-gray-300"></i></div>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $item['product']['name'] ?? 'Unknown Product' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-8 text-center text-sm text-gray-700">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="py-4 px-8 text-center text-sm font-bold text-gray-900">{{ $item['quantity'] }}x</td>
                            <td class="py-4 px-8 text-right text-sm font-bold text-gray-900">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="p-8 bg-gray-50/50 flex justify-end">
                <div class="w-full md:w-80 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($order['total_price'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($order['shipping_cost'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @if($order['voucher_discount'] ?? 0 > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-red-500">Potongan Voucher</span>
                        <span class="font-bold text-red-600">-Rp {{ number_format($order['voucher_discount'], 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                        <span class="font-bold text-gray-900">Total Pembayaran</span>
                        <span class="text-xl font-extrabold text-amber-600">Rp {{ number_format($order['final_price'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
