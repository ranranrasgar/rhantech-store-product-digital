@extends('layouts.admin')
@section('title', 'Users Management')

@section('content')
<div class="p-lg md:p-xl flex-1 max-w-7xl mx-auto w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-lg gap-md">
        <div>
            <h2 class="font-headline-sm font-bold text-on-surface">User Management</h2>
            <p class="font-body-md text-on-surface-variant">Kelola akun pengguna, hak akses, dan kepemilikan toko.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:bg-primary/90 transition-colors flex items-center gap-2 self-start sm:self-auto shadow-sm" wire:navigate>
            <span class="material-symbols-outlined text-[1.25rem]">add</span> Add New User
        </a>
    </div>

    @if(session('success'))
        <div class="bg-surface-container-highest text-on-surface p-4 rounded-xl mb-lg border border-outline-variant flex items-center gap-2">
            <span class="material-symbols-outlined text-green-500 text-[20px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-lg border border-error flex items-center gap-2">
            <span class="material-symbols-outlined text-error text-[20px]">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="bg-surface rounded-xl border border-outline-variant p-4 mb-6 shadow-xs">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="flex-1 relative flex items-center">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] leading-none">search</span>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, no. HP, atau nama toko..." class="w-full pl-10 pr-4 py-2 text-xs md:text-sm bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
            </div>

            <select name="role" class="px-3.5 py-2 text-xs md:text-sm bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
            </select>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-primary text-white text-xs md:text-sm font-bold rounded-xl shadow-xs hover:opacity-90 transition-opacity flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span> Cari
                </button>
                @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 border border-outline-variant text-xs md:text-sm font-semibold rounded-xl text-on-surface-variant hover:bg-surface-container-highest transition-colors">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-surface-container-lowest border-b border-outline-variant text-on-surface-variant font-label-md">
                        <th class="py-3.5 px-4 font-bold">User & Toko</th>
                        <th class="py-3.5 px-4 font-bold">Kontak</th>
                        <th class="py-3.5 px-4 font-bold text-center">Role</th>
                        <th class="py-3.5 px-4 font-bold">Joined</th>
                        <th class="py-3.5 px-4 font-bold text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($users as $user)
                        <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                            <!-- User Name & Store Info -->
                            <td class="py-3.5 px-4 align-top">
                                <div class="font-bold text-on-surface text-sm flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>

                                <!-- Info Toko di Bawah Nama User -->
                                <div class="mt-1.5 pl-9">
                                    @if($user->store)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-surface-container border border-outline-variant text-xs">
                                            <span class="material-symbols-outlined text-[14px] text-primary">storefront</span>
                                            <span class="font-semibold text-on-surface">{{ $user->store->name }}</span>
                                            <span class="text-[10px] text-on-surface-variant/60">•</span>
                                            <a href="{{ route('store.show', $user->store->slug) }}" target="_blank" class="text-[11px] text-primary hover:underline flex items-center gap-0.5" title="Lihat Etalase Toko">
                                                Lihat Toko <span class="material-symbols-outlined text-[11px]">open_in_new</span>
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-on-surface-variant/70 italic flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px] opacity-60">storefront</span> Belum memiliki toko
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Email & Phone -->
                            <td class="py-3.5 px-4 align-top">
                                <div class="text-xs font-semibold text-on-surface">{{ $user->email }}</div>
                                @if($user->phone)
                                    <div class="text-[11px] text-on-surface-variant font-mono mt-0.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">call</span>
                                        {{ $user->phone }}
                                    </div>
                                @endif
                            </td>

                            <!-- Role -->
                            <td class="py-3.5 px-4 align-top text-center">
                                @if($user->role === 'admin')
                                    <span class="px-2.5 py-0.5 bg-purple-100 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 text-xs font-bold rounded-full uppercase">
                                        {{ $user->role }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-secondary-container text-on-secondary-container text-xs font-bold rounded-full uppercase">
                                        {{ $user->role }}
                                    </span>
                                @endif
                            </td>

                            <!-- Joined Date -->
                            <td class="py-3.5 px-4 align-top text-xs text-on-surface-variant">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 align-top text-right pr-6">
                                <div class="flex justify-end items-center gap-1.5">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-1.5 bg-surface-variant text-on-surface-variant rounded-lg hover:bg-secondary-container hover:text-on-secondary-container transition-colors" title="Edit User" wire:navigate>
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-error/10 text-error rounded-lg hover:bg-error hover:text-white transition-colors cursor-pointer" title="Delete User">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-50 block">group_off</span>
                                <p class="font-semibold">Tidak ada user yang ditemukan.</p>
                                <p class="text-xs text-on-surface-variant mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-t border-outline-variant">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
