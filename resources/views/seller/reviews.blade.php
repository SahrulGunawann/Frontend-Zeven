@extends('layouts.seller')

@section('header_title', 'Ulasan & Rating')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Analisis & Rating Summary (Left Column Card) -->
            <div class="lg:col-span-1 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <!-- Header -->
                        <div class="mb-6 pb-4 border-b border-gray-100">
                            <h3 class="text-base font-bold text-gray-900 mb-1 flex items-center gap-2">
                                <i data-lucide="bar-chart-3" class="w-5 h-5 text-emerald-600"></i>
                                Analisis & Rating Toko
                            </h3>
                            <p class="text-xs text-gray-400">Ringkasan penilaian dan pembagian bintang</p>
                        </div>

                        <!-- Big Score & Summary Box -->
                        <div class="bg-gradient-to-br from-emerald-50/50 to-teal-50/20 rounded-2xl p-4 border border-emerald-100/50 mb-6 grid grid-cols-3 divide-x divide-emerald-100">
                            <!-- Col 1: Avg Rating -->
                            <div class="pr-3 flex flex-col justify-center">
                                <p class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider mb-1">Rating</p>
                                <div class="flex items-baseline gap-0.5">
                                    <span class="text-2xl font-black text-gray-900">{{ number_format($stats['average'], 1) }}</span>
                                    <span class="text-[10px] text-gray-400 font-semibold">/5</span>
                                </div>
                                <div class="flex items-center gap-0.5 mt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star"
                                            class="w-3 h-3 {{ $i <= round($stats['average']) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <!-- Col 2: Total Reviews -->
                            <div class="px-3 flex flex-col justify-center text-center">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total</p>
                                <span class="text-xl font-black text-gray-800">{{ $stats['total'] }}</span>
                                <p class="text-[9px] text-gray-400 mt-0.5">Ulasan</p>
                            </div>
                            <!-- Col 3: Satisfaction Rate -->
                            <div class="pl-3 flex flex-col justify-center text-right">
                                <p class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider mb-1">Kepuasan</p>
                                <span class="text-xl font-black text-emerald-700">
                                    {{ $stats['total'] > 0 ? round(($stats['five_star'] / $stats['total']) * 100) : 0 }}%
                                </span>
                                <p class="text-[9px] text-gray-400 mt-0.5">Bintang 5 ⭐</p>
                            </div>
                        </div>

                        <!-- Distribution Bars -->
                        <div class="space-y-3.5 mb-6">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Distribusi Bintang</p>
                            @php
                                $ratingCounts = $stats['distribution'] ?? [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                            @endphp
                            @for ($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $ratingCounts[$i];
                                    $pct = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                                    $barColor = match ($i) {
                                        5 => 'bg-emerald-500',
                                        4 => 'bg-green-400',
                                        3 => 'bg-yellow-400',
                                        2 => 'bg-orange-400',
                                        1 => 'bg-red-400',
                                    };
                                @endphp
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1 w-10 shrink-0">
                                        <span class="text-xs font-bold text-gray-600">{{ $i }}</span>
                                        <i data-lucide="star" class="w-3 h-3 text-yellow-400 fill-yellow-400"></i>
                                    </div>
                                    <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                        <div class="{{ $barColor }} h-2 rounded-full transition-all duration-700"
                                            style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-400 w-6 text-right shrink-0">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Tips Card at the bottom -->
                    <div class="p-4 bg-amber-50/60 rounded-2xl border border-amber-100/70 flex gap-3 mt-4 items-start">
                        <div class="p-2 bg-amber-100 rounded-xl text-amber-700 shrink-0">
                            <i data-lucide="lightbulb" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-amber-900 mb-1">Tips Penjual</h4>
                            <p class="text-[10px] text-amber-800/80 leading-relaxed font-medium">
                                Balaslah ulasan pembeli dengan ramah dan responsif. Respon yang cepat membantu meyakinkan calon pelanggan baru untuk membeli di toko Anda!
                            </p>
                        </div>
                    </div>
                </div>

            <!-- Reviews List -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Ulasan Terbaru</h2>
                    @if($stats['total'] > 0)
                        <span
                            class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                            {{ $stats['total'] }} ulasan
                        </span>
                    @endif
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($reviews as $review)
                        @php
                            $initial = strtoupper(substr($review['buyer']['name'] ?? 'P', 0, 1));
                            $avatarColors = [
                                'A' => 'from-red-400 to-pink-500',
                                'B' => 'from-orange-400 to-red-400',
                                'C' => 'from-yellow-400 to-orange-400',
                                'D' => 'from-green-400 to-teal-500',
                                'E' => 'from-teal-400 to-cyan-500',
                                'F' => 'from-cyan-400 to-blue-500',
                                'G' => 'from-blue-400 to-indigo-500',
                                'H' => 'from-indigo-400 to-purple-500',
                                'I' => 'from-purple-400 to-pink-500',
                                'J' => 'from-pink-400 to-rose-500',
                            ];
                            $colorKey = strtoupper(substr($review['buyer']['name'] ?? 'P', 0, 1));
                            $gradientClass = $avatarColors[$colorKey] ?? 'from-orange-400 to-amber-500';
                            $starLabels = ['', 'Sangat Buruk', 'Kurang Bagus', 'Cukup', 'Bagus!', 'Luar Biasa!'];
                            $ratingColorsText = ['', 'text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500', 'text-emerald-600'];
                        @endphp
                        <div class="p-5 hover:bg-gray-50/50 transition-all">
                            <div class="flex gap-4">
                                <!-- Avatar -->
                                <div
                                    class="w-11 h-11 rounded-2xl bg-gradient-to-br {{ $gradientClass }} flex items-center justify-center text-white font-black text-lg shrink-0 shadow-sm">
                                    {{ $initial }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-2 mb-3">
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-sm">
                                                {{ $review['buyer']['name'] ?? 'Pembeli' }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                Membeli: <span
                                                    class="font-semibold text-emerald-700">{{ $review['product']['name'] ?? 'Produk' }}</span>
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-start md:items-end gap-1 shrink-0">
                                            <div class="flex items-center gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i data-lucide="star"
                                                        class="w-3.5 h-3.5 {{ $i <= $review['rating'] ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200 fill-gray-200' }}"></i>
                                                @endfor
                                                <span
                                                    class="text-xs font-bold {{ $ratingColorsText[$review['rating']] ?? 'text-gray-500' }} ml-1">
                                                    {{ $starLabels[$review['rating']] ?? '' }}
                                                </span>
                                            </div>
                                            <span class="text-[10px] text-gray-400">
                                                {{ \Carbon\Carbon::parse($review['created_at'])->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    <p
                                        class="text-sm text-gray-600 leading-relaxed bg-gray-50 px-4 py-3 rounded-xl border border-gray-100 italic">
                                        "{{ $review['review'] }}"
                                    </p>

                                    @if($review['reply'])
                                        <div class="mt-4 ml-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 relative">
                                            <div
                                                class="absolute -top-3 left-4 px-2 bg-white text-[10px] font-bold text-emerald-700 border border-emerald-100 rounded-full">
                                                Balasan Anda
                                            </div>
                                            <p class="text-sm text-gray-700">
                                                {{ $review['reply'] }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-4" x-data="{ showReply: false }">
                                            <button @click="showReply = !showReply"
                                                class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 transition-colors">
                                                <i data-lucide="message-square" class="w-3.5 h-3.5"></i>
                                                Balas Ulasan
                                            </button>

                                            <form x-show="showReply" x-transition
                                                action="{{ route('seller.reviews.reply', $review['id']) }}" method="POST"
                                                class="mt-4 bg-gray-50/50 p-4 rounded-2xl border border-dashed border-emerald-200">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="text-[10px] font-bold text-emerald-700 uppercase mb-1 block">Tulis
                                                        Balasan</label>
                                                    <textarea name="reply" rows="3"
                                                        class="w-full text-sm border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 placeholder:text-gray-400 p-3 shadow-sm resize-none"
                                                        placeholder="Sampaikan rasa terima kasih atau tanggapan Anda..."></textarea>
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" @click="showReply = false"
                                                        class="px-4 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition-colors">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="px-6 py-2 bg-gradient-to-r from-emerald-600 to-emerald-800 text-white text-xs font-black rounded-xl hover:shadow-lg hover:shadow-emerald-100 transition-all active:scale-95">
                                                        Kirim Balasan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-24 px-6">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-3xl flex items-center justify-center mx-auto mb-5 border border-orange-100">
                                <i data-lucide="star" class="w-10 h-10 text-yellow-300"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-2">Belum ada ulasan</h3>
                            <p class="text-sm text-gray-400 max-w-xs mx-auto leading-relaxed">
                                Ulasan dari pembeli akan muncul di sini setelah mereka menyelesaikan pesanan.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if($reviews->hasPages())
                    <div class="mt-6 pt-6 border-t border-gray-50 flex items-center justify-center p-6 bg-gray-50/30">
                        <div class="flex items-center gap-2">
                            @if($reviews->onFirstPage())
                                <span class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-left"
                                        class="w-5 h-5"></i></span>
                            @else
                                <a href="{{ $reviews->previousPageUrl() }}"
                                    class="p-2 text-gray-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all"><i
                                        data-lucide="chevron-left" class="w-5 h-5"></i></a>
                            @endif

                            <div class="flex items-center gap-1">
                                @foreach ($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                                    @if ($page == $reviews->currentPage())
                                        <span
                                            class="w-9 h-9 flex items-center justify-center bg-emerald-700 text-white rounded-lg text-xs font-bold">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 rounded-lg text-xs font-medium transition-all">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>

                            @if($reviews->hasMorePages())
                                <a href="{{ $reviews->nextPageUrl() }}"
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
    </div>
@endsection