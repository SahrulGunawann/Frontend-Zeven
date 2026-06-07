@extends('layouts.admin')

@section('header_title', 'Manajemen User')

@section('content')
    <div class="space-y-6">
        @php
            $usersColl = collect($users);
            $totalUsers = $usersColl->count();
            $totalBuyers = $usersColl->where('role', 'buyer')->count();
            $totalSellers = $usersColl->where('role', 'seller')->count();
            $newThisMonth = $usersColl->filter(function($u) {
                try {
                    return \Carbon\Carbon::parse($u['created_at'])->isSameMonth(\Carbon\Carbon::now());
                } catch (\Exception $e) {
                    return false;
                }
            })->count();
        @endphp

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total User</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="user-check" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Pembeli</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $totalBuyers }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="store" class="w-6 h-6 text-amber-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Penjual</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $totalSellers }}</h3>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="user-plus" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Baru Bulan Ini</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $newThisMonth }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 p-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Daftar User</h2>
                    <p class="text-sm text-gray-500">Kelola informasi seluruh akun di sistem</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <div class="relative flex-1 sm:flex-initial">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari user..."
                            class="w-full md:w-64 pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                    <div class="relative w-full sm:w-44" x-data="{ 
                        open: false, 
                        selected: 'Semua Peran', 
                        value: '',
                        options: [
                            { label: 'Semua Peran', value: '' },
                            { label: 'Pembeli', value: 'buyer' },
                            { label: 'Penjual', value: 'seller' }
                        ],
                        select(opt) {
                            this.selected = opt.label;
                            this.value = opt.value;
                            this.open = false;
                            applyRoleFilter(opt.value);
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
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="userTable">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">User
                            </th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak
                            </th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Alamat
                            </th>
                            <th class="text-left py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl
                                Gabung</th>
                            <th class="text-center py-4 px-6 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-all table-row" data-role="{{ $user['role'] }}">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-amber-100 flex-shrink-0 flex items-center justify-center overflow-hidden border border-amber-200 text-amber-600 font-bold text-sm uppercase">
                                            @if($user['profile_image'] ?? null)
                                                <img src="{{ Str::startsWith($user['profile_image'], 'http') ? $user['profile_image'] : env('BACKEND_URL') . '/storage/' . $user['profile_image'] }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                {{ substr($user['name'], 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 search-name">{{ $user['name'] }}</p>
                                            <span
                                                class="text-[10px] px-1.5 py-0.5 {{ $user['role'] === 'seller' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }} rounded uppercase font-bold">{{ $user['role'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-sm text-gray-700 font-medium search-email">{{ $user['email'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $user['phone'] ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-6">
                                    <p class="text-xs text-gray-600 line-clamp-1 max-w-[150px]">
                                        @php
                                            $mainAddr = collect($user['addresses'] ?? [])->where('is_main', true)->first();
                                            $displayAddr = $mainAddr['full_address'] ?? ($user['address'] ?? 'Belum diatur');
                                        @endphp
                                        {{ $displayAddr }}
                                    </p>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($user['created_at'])->format('d M Y') }}
                                </td>

                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.show', $user['id']) }}"
                                            class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                            title="Detail Profile">
                                            <i data-lucide="eye" class="w-4 h-4 text-amber-600"></i>
                                        </a>

                                        <a href="{{ route('admin.chats') }}?user_id={{ $user['id'] }}"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                            title="Chat User">
                                            <i data-lucide="message-square" class="w-4 h-4"></i>
                                        </a>

                                        <form id="delete-form-{{ $user['id'] }}"
                                            action="{{ route('admin.users.delete', $user['id']) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $user['id'] }})"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                title="Hapus User">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 text-sm italic">
                                    Belum ada pengguna yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let currentRole = "";

        function applyRoleFilter(role) {
            currentRole = role;
            filterTable();
        }

        function filterTable() {
            const searchVal = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('userTable');
            const tr = table.getElementsByClassName('table-row');

            for (let i = 0; i < tr.length; i++) {
                const name = tr[i].getElementsByClassName('search-name')[0].textContent.toLowerCase();
                const email = tr[i].getElementsByClassName('search-email')[0].textContent.toLowerCase();
                const userRole = tr[i].getAttribute('data-role').toLowerCase();

                const matchesSearch = name.includes(searchVal) || email.includes(searchVal);
                const matchesRole = currentRole === "" || userRole === currentRole;

                if (matchesSearch && matchesRole) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        // Auto apply role filter from URL query parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const roleParam = urlParams.get('role');
            if (roleParam) {
                const roleFilter = document.getElementById('roleFilter');
                if (roleFilter) {
                    roleFilter.value = roleParam;
                    filterTable();
                }
            }
        });
    </script>
@endsection

@push('scripts')
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Hapus User?',
                text: "Aksi ini tidak dapat dibatalkan dan semua data terkait mungkin akan hilang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-2.5',
                    cancelButton: 'rounded-xl px-6 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            })
        }

        // Auto show flash messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl' }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                customClass: { popup: 'rounded-2xl' }
            });
        @endif
    </script>
@endpush