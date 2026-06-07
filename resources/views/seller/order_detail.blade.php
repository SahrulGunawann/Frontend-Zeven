@extends('layouts.seller')

@section('header_title', 'Detail Pesanan')

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

        <!-- Header & Status -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('seller.orders') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-emerald-700 hover:border-emerald-100 transition-all">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-amber-500"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">#ORD-{{ str_pad($order['id'], 5, '0', STR_PAD_LEFT) }}</h2>
                    <p class="text-sm text-gray-500">Dipesan pada {{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $statusConfig = [
                        'pending' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-amber-50 text-amber-600 border-amber-100', 'btn' => null, 'next' => null],
                        'processed' => ['label' => 'Perlu Dikirim', 'class' => 'bg-blue-50 text-blue-600 border-blue-100', 'btn' => 'Kirim Pesanan', 'next' => 'shipped'],
                        'shipped' => ['label' => 'Dalam Pengiriman', 'class' => 'bg-purple-50 text-purple-600 border-purple-100', 'btn' => null, 'next' => null],
                        'completed' => ['label' => 'Selesai', 'class' => 'bg-green-50 text-green-600 border-green-100', 'btn' => null],
                    ];
                    $currentStatus = $statusConfig[$order['status']] ?? ['label' => $order['status'], 'class' => 'bg-gray-50 text-gray-500 border-gray-100', 'btn' => null];
                @endphp
                <span class="px-4 py-1.5 rounded-full border {{ $currentStatus['class'] }} text-xs font-bold uppercase tracking-wider">
                    {{ $currentStatus['label'] }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Order Items & Delivery Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="package" class="w-5 h-5 text-emerald-600"></i>
                            Daftar Produk
                        </h3>
                    </div>
                    <div class="p-6">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                    <th class="pb-4">Produk</th>
                                    <th class="pb-4 text-center">Harga</th>
                                    <th class="pb-4 text-center">Jumlah</th>
                                    <th class="pb-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($order['items'] as $item)
                                    <tr>
                                        <td class="py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-16 h-16 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100">
                                                    @if(isset($item['product']['image']))
                                                        @php
                                                            $prodImage = $item['product']['image'];
                                                            $prodImageUrl = Str::startsWith($prodImage, 'http') 
                                                                ? $prodImage 
                                                                : (Str::startsWith($prodImage, 'storage/') 
                                                                    ? env('BACKEND_URL') . '/' . $prodImage 
                                                                    : env('BACKEND_URL') . '/storage/' . $prodImage);
                                                        @endphp
                                                        <img src="{{ $prodImageUrl }}" alt="{{ $item['product']['name'] }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center">
                                                            <i data-lucide="image" class="w-6 h-6 text-gray-200"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $item['product']['name'] ?? 'Produk Telah Dihapus' }}</h4>
                                                    <p class="text-xs text-gray-500 mt-1">ID Produk: {{ $item['product_id'] ? 'PRD-'.$item['product_id'] : 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 text-center text-sm text-gray-700 font-medium">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="py-4 text-center text-sm text-gray-700 font-bold">x{{ $item['quantity'] }}</td>
                                        <td class="py-4 text-right text-sm font-bold text-gray-900">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50/50 p-6">
                        <div class="flex flex-col items-end gap-2">
                            <div class="flex items-center justify-between w-full md:w-64">
                                <span class="text-sm text-gray-500">Total Harga</span>
                                <span class="text-sm font-bold text-gray-900">Rp {{ number_format($order['total_price'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between w-full md:w-64">
                                <span class="text-sm text-gray-500">Diskon</span>
                                <span class="text-sm font-bold text-red-500">- Rp {{ number_format($order['discount_amount'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-2 pt-4 border-t border-gray-200 flex items-center justify-between w-full md:w-64">
                                <span class="text-base font-bold text-gray-900">Final Price</span>
                                <span class="text-xl font-bold text-emerald-700">Rp {{ number_format($order['final_price'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-5 h-5 text-blue-500"></i>
                            Informasi Pengiriman
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $order['buyer']['name'] ?? 'Pembeli Umum' }}</h4>
                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                    {{ $order['shipping_address'] ?? 'Alamat tidak tersedia' }}
                                </p>
                                <div class="mt-4 flex items-center gap-4 text-xs">
                                    <span class="flex items-center gap-1 text-gray-500">
                                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                        {{ $order['buyer']['phone'] ?? '-' }}
                                    </span>
                                    <span class="flex items-center gap-1 text-gray-500">
                                        <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                        {{ $order['buyer']['email'] ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Buyer Info & Action -->
            <div class="space-y-6">
                <!-- Buyer Profile Summary -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-emerald-100 overflow-hidden">
                        @if(($order['buyer']['profile_image'] ?? $order['buyer']['avatar'] ?? null))
                            @php
                                $buyerAvatar = $order['buyer']['profile_image'] ?? $order['buyer']['avatar'];
                                $buyerAvatarUrl = Str::startsWith($buyerAvatar, 'http') 
                                    ? $buyerAvatar 
                                    : (Str::startsWith($buyerAvatar, 'storage/') 
                                        ? env('BACKEND_URL') . '/' . $buyerAvatar 
                                        : env('BACKEND_URL') . '/storage/' . $buyerAvatar);
                            @endphp
                            <img src="{{ $buyerAvatarUrl }}" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="user" class="w-10 h-10 text-emerald-600"></i>
                        @endif
                    </div>
                    <h3 class="font-bold text-gray-900">{{ $order['buyer']['name'] ?? 'Pembeli Umum' }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Pembeli Setia Anda</p>
                    <div class="mt-6 flex items-center justify-center gap-3">
                        <a href="{{ route('seller.chats') }}?user_id={{ $order['buyer_id'] }}" class="flex-1 py-2.5 bg-emerald-700 text-white rounded-xl text-xs font-bold hover:bg-emerald-800 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-100">
                            <i data-lucide="message-square" class="w-4 h-4 text-amber-400"></i>
                            Hubungi Pembeli
                        </a>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4 text-sm">Informasi Pembayaran</h3>
                    <div class="space-y-4">
                        @php
                            $rawMethod = $order['transaction']['payment_method'] ?? $order['payment_method'] ?? '';
                        @endphp
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Status Pembayaran</span>
                            @php
                                $isPaid = ($order['transaction']['payment_status'] ?? '') === 'paid';
                            @endphp
                            <span class="px-2 py-0.5 {{ $isPaid ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100' }} rounded-md font-bold border">
                                {{ $isPaid ? 'Sudah Bayar' : 'Belum Bayar' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">ID Referensi</span>
                            <span class="font-bold text-gray-900 font-mono">
                                @php
                                    $displayRef = '-';
                                    if(!empty($rawMethod)) {
                                        // Ambil bagian ZVN sebelum tanda pipa |
                                        $parts = explode('|', $rawMethod);
                                        $displayRef = str_starts_with($parts[0], 'ZVN') ? '#' . $parts[0] : '-';
                                    }
                                @endphp
                                {{ $displayRef }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                @php
                    $canProcess = true;
                    // Jika pembayaran online dan belum lunas, seller tidak boleh klik proses manual
                    if (str_contains(strtolower($order['payment_method'] ?? ''), 'dompetx') && !$isPaid) {
                        $canProcess = false;
                    }
                @endphp

                @if($currentStatus['btn'] && $canProcess)
                    <form action="{{ route('seller.orders.update_status', $order['id']) }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="status" value="{{ $currentStatus['next'] }}">
                        <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl font-bold flex items-center justify-center gap-2 hover:bg-gray-800 transition-all shadow-xl shadow-gray-200 group">
                            <i data-lucide="play" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                            {{ $currentStatus['btn'] }}
                        </button>
                    </form>
                @elseif($currentStatus['btn'] && !$canProcess)
                    <div class="w-full py-4 bg-gray-100 text-gray-400 rounded-2xl font-bold flex items-center justify-center gap-2 cursor-not-allowed border border-gray-200">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                        Menunggu Pembayaran
                    </div>
                @endif
                <button class="w-full py-3 border border-gray-200 text-gray-600 rounded-2xl text-sm font-bold flex items-center justify-center gap-2 hover:bg-gray-50 transition-all">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Cetak Label Pengiriman
                </button>
            </div>
        </div>
    </div>
@endsection
