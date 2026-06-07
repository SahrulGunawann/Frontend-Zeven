@extends('layouts.admin')

@section('header_title', 'Kelola Penarikan Saldo')

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

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-stat-card title="Total Dana" value="{{ $stats['escrow_balance'] ?? 'Rp 0' }}" icon="wallet"
                iconBgColor="bg-emerald-50" iconColor="text-emerald-600" />
            <x-stat-card title="Komisi Platform (2%)" value="{{ $stats['platform_revenue'] ?? 'Rp 0' }}" icon="percent"
                iconBgColor="bg-amber-50" iconColor="text-amber-600" />
            <x-stat-card title="Hak Seller (Belum Dicairkan)" value="{{ $stats['sellers_balance'] ?? 'Rp 0' }}" icon="users"
                iconBgColor="bg-blue-50" iconColor="text-blue-600" />
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-1 flex items-center gap-2">
                    <i data-lucide="landmark" class="w-5.5 h-5.5 text-emerald-600"></i>
                    Permintaan Penarikan Saldo Seller
                </h2>
                <p class="text-sm text-gray-500">Verifikasi bank, lakukan transfer manual, lalu setujui atau tolak permintaan di bawah ini.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100 text-left">
                            <th class="py-4 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Penjual (Seller)</th>
                            <th class="py-4 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jumlah Penarikan</th>
                            <th class="py-4 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Informasi Bank</th>
                            <th class="py-4 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="py-4 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal Pengajuan</th>
                            <th class="py-4 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($withdrawals as $w)
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="py-4 px-4">
                                    <div class="font-bold text-gray-900 text-sm">{{ $w['seller_name'] }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">ID Penarikan: #WD-{{ str_pad($w['id'], 5, '0', STR_PAD_LEFT) }}</div>
                                </td>
                                <td class="py-4 px-4 font-black text-sm text-gray-900">{{ $w['amount'] }}</td>
                                <td class="py-4 px-4">
                                    <div class="text-xs font-bold text-gray-800 flex items-center gap-1">
                                        <span class="px-2 py-0.5 bg-gray-100 rounded text-[9px] font-black uppercase text-gray-600">{{ $w['bank_name'] }}</span>
                                        <span class="font-mono bg-gray-50 hover:bg-emerald-50 px-2 py-1 rounded cursor-pointer border border-dashed border-gray-200 hover:border-emerald-300 transition-all select-all flex items-center gap-1 group" 
                                              onclick="copyToClipboard('{{ $w['account_number'] }}')" 
                                              title="Klik untuk menyalin nomor rekening">
                                            <span class="account-number-text">{{ $w['account_number'] }}</span>
                                            <i data-lucide="copy" class="w-3.5 h-3.5 text-gray-400 group-hover:text-emerald-600 transition-colors"></i>
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-gray-500 mt-1.5 font-medium">a/n {{ $w['account_name'] }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-extrabold uppercase tracking-wider {{ $w['status'] === 'completed' ? 'bg-green-50 text-green-600 border border-green-100' : ($w['status'] === 'rejected' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100') }}">
                                        {{ $w['status'] === 'completed' ? 'Selesai' : ($w['status'] === 'rejected' ? 'Ditolak' : 'Pending') }}
                                    </span>
                                    @if($w['status'] === 'rejected' && !empty($w['rejected_reason']))
                                        <p class="text-[10px] text-red-500 italic mt-1.5 font-medium">Alasan: {{ $w['rejected_reason'] }}</p>
                                    @endif
                                    @if(!empty($w['proof_image']))
                                        <div class="mt-2">
                                            <a href="{{ $w['proof_image'] }}" target="_blank" class="inline-flex items-center gap-1 text-[9px] font-bold {{ $w['status'] === 'rejected' ? 'text-rose-700 hover:text-rose-950 bg-rose-50 border-rose-100' : 'text-emerald-700 hover:text-emerald-950 bg-emerald-50 border-emerald-100' }} transition-colors px-2 py-0.5 rounded border">
                                                <i data-lucide="image" class="w-3 h-3"></i>
                                                {{ $w['status'] === 'rejected' ? 'Bukti Penolakan' : 'Bukti Transfer' }}
                                            </a>
                                        </div>
                                    @endif
                                    @if(!empty($w['admin_note']))
                                        <p class="text-[10px] text-gray-500 mt-1 font-medium bg-gray-50 p-2 border border-gray-100 rounded-lg"><strong>Catatan:</strong> {{ $w['admin_note'] }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-xs text-gray-400 whitespace-nowrap">{{ $w['date'] }}</td>
                                <td class="py-4 px-4 text-center">
                                    @if($w['status'] === 'pending')
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Approve Trigger -->
                                            <button onclick="openApproveModal('{{ $w['id'] }}', '{{ $w['seller_name'] }}', '{{ $w['amount'] }}')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95 flex items-center gap-1">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i> Setujui
                                            </button>
                                            <!-- Reject Trigger -->
                                            <button onclick="openRejectModal('{{ $w['id'] }}', '{{ $w['seller_name'] }}', '{{ $w['amount'] }}')" class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-bold transition-all active:scale-95 flex items-center gap-1">
                                                <i data-lucide="x" class="w-3.5 h-3.5"></i> Tolak
                                            </button>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-50 border border-gray-200 text-gray-400 rounded-xl text-xs font-bold">
                                            <i data-lucide="check-square" class="w-3.5 h-3.5"></i> Sudah Direspon
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                            <i data-lucide="landmark" class="w-8 h-8 text-gray-300"></i>
                                        </div>
                                        <h3 class="text-sm font-bold text-gray-900">Belum ada pengajuan</h3>
                                        <p class="text-xs text-gray-500 mt-1">Semua pengajuan penarikan saldo oleh seller akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($withdrawals->hasPages())
                <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-center p-6 bg-gray-50/30">
                    <div class="flex items-center gap-2">
                        @if($withdrawals->onFirstPage())
                            <span class="p-2 text-gray-300 cursor-not-allowed"><i data-lucide="chevron-left"
                                    class="w-5 h-5"></i></span>
                        @else
                            <a href="{{ $withdrawals->previousPageUrl() }}"
                                class="p-2 text-gray-600 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-all"><i
                                    data-lucide="chevron-left" class="w-5 h-5"></i></a>
                        @endif

                        <div class="flex items-center gap-1">
                            @foreach ($withdrawals->getUrlRange(1, $withdrawals->lastPage()) as $page => $url)
                                @if ($page == $withdrawals->currentPage())
                                    <span
                                        class="w-9 h-9 flex items-center justify-center bg-amber-600 text-white rounded-lg text-xs font-bold shadow-sm shadow-amber-100">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="w-9 h-9 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:text-amber-700 rounded-lg text-xs font-medium transition-all">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if($withdrawals->hasMorePages())
                            <a href="{{ $withdrawals->nextPageUrl() }}"
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

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="approveModalContainer">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h3 class="font-black text-gray-900 text-lg flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    Setujui Penarikan Saldo
                </h3>
                <button onclick="closeApproveModal()" class="w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 shadow-sm active:scale-90 transition-all">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form id="approveForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-xs text-emerald-800 font-bold leading-relaxed">
                    Pastikan Anda telah mentransfer dana ke <span id="approveSellerName" class="underline font-black"></span> sebesar <span id="approveAmount" class="underline font-black"></span> sebelum menyetujui.
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Bukti Transfer <span class="text-red-500">*Wajib</span></label>
                    <input type="file" name="proof_image" required accept="image/*" class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-bold text-gray-800 text-xs bg-gray-50">
                    <p class="text-[10px] text-gray-400 mt-1">Format gambar (JPEG, PNG, JPG, WEBP), maksimal 2MB</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan Admin <span class="text-gray-400">(Opsional)</span></label>
                    <textarea name="admin_note" placeholder="Contoh: Transfer sukses via M-BCA." rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 font-medium text-gray-800 text-sm"></textarea>
                </div>
                <div class="pt-4 flex items-center gap-3">
                    <button type="button" onclick="closeApproveModal()" class="flex-1 py-3.5 border border-gray-200 rounded-2xl font-bold text-gray-700 text-sm hover:bg-gray-50 active:scale-95 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm active:scale-95 transition-all shadow-lg shadow-emerald-500/10">
                        Ya, Approve & Selesai
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modalContainer">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                <h3 class="font-black text-gray-900 text-lg flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
                    Tolak Penarikan Saldo
                </h3>
                <button onclick="closeRejectModal()" class="w-8 h-8 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-600 shadow-sm active:scale-90 transition-all">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <form id="rejectForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="p-4 bg-red-50 rounded-2xl border border-red-100 text-xs text-red-700 font-bold leading-relaxed">
                    Anda akan menolak pengajuan penarikan saldo <span id="rejectSellerName" class="underline font-black"></span> sebesar <span id="rejectAmount" class="underline font-black"></span>.
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alasan Penolakan <span class="text-red-500">*Wajib</span></label>
                    <textarea name="rejected_reason" required placeholder="Contoh: Nomor rekening bank tidak terdaftar atau nama tidak sesuai." rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 font-medium text-gray-800 text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Bukti Penolakan <span class="text-gray-400">(Opsional)</span></label>
                    <input type="file" name="proof_image" accept="image/*" class="w-full px-4 py-3 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 font-bold text-gray-800 text-xs bg-gray-50">
                    <p class="text-[10px] text-gray-400 mt-1">Format gambar (JPEG, PNG, JPG, WEBP), maksimal 2MB</p>
                </div>

                <div class="pt-4 flex items-center gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-3.5 border border-gray-200 rounded-2xl font-bold text-gray-700 text-sm hover:bg-gray-50 active:scale-95 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black text-sm active:scale-95 transition-all shadow-lg shadow-red-500/10">
                        Ya, Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Copy to Clipboard
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showCopySuccess();
                }).catch(err => {
                    fallbackCopyToClipboard(text);
                });
            } else {
                fallbackCopyToClipboard(text);
            }
        }

        function fallbackCopyToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showCopySuccess();
                } else {
                    console.error('Fallback copy failed');
                }
            } catch (err) {
                console.error('Fallback copy error:', err);
            }
            document.body.removeChild(textArea);
        }

        function showCopySuccess() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'No. Rekening berhasil disalin!',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                customClass: { popup: 'rounded-xl shadow-md border border-gray-50' }
            });
        }

        // Approve Modal
        function openApproveModal(id, sellerName, amount) {
            const modal = document.getElementById('approveModal');
            const container = document.getElementById('approveModalContainer');
            const form = document.getElementById('approveForm');
            
            document.getElementById('approveSellerName').innerText = sellerName;
            document.getElementById('approveAmount').innerText = amount;
            form.action = `/admin/withdrawals/${id}/approve`;

            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeApproveModal() {
            const modal = document.getElementById('approveModal');
            const container = document.getElementById('approveModalContainer');
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Reject Modal
        function openRejectModal(id, sellerName, amount) {
            const modal = document.getElementById('rejectModal');
            const container = document.getElementById('modalContainer');
            const form = document.getElementById('rejectForm');
            
            document.getElementById('rejectSellerName').innerText = sellerName;
            document.getElementById('rejectAmount').innerText = amount;
            form.action = `/admin/withdrawals/${id}/reject`;

            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            const container = document.getElementById('modalContainer');
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
@endpush
