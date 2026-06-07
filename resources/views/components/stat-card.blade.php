@props(['title', 'value', 'icon', 'trend' => '', 'trendUp' => true, 'iconBgColor', 'iconColor', 'href' => null])

<{{ $href ? 'a href=' . $href : 'div' }} class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 transition-all
    hover:shadow-md block @if($href) hover:-translate-y-1 @endif">
    <div class="flex items-center gap-3">
        <!-- Icon Container - Dikecilkan sedikit untuk ruang teks -->
        <div class="{{ $iconBgColor }} w-11 h-11 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $iconColor }}"></i>
        </div>
        
        <!-- Text Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-1 mb-0.5">
                <p class="text-[9px] uppercase font-bold text-gray-400 tracking-wider truncate">{{ $title }}</p>
                @if($trend)
                    <span class="text-[9px] {{ $trendUp ? 'text-green-600' : 'text-red-600' }} font-bold shrink-0">
                        {{ $trend }}
                    </span>
                @endif
            </div>
            <!-- Nominal - Ukuran disesuaikan agar tidak wrap -->
            <h3 class="text-base xl:text-lg font-bold text-gray-900 leading-none truncate" title="{{ $value }}">
                {{ $value }}
            </h3>
        </div>
    </div>
</{{ $href ? 'a' : 'div' }}>