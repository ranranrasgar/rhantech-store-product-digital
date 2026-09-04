@extends('layouts.admin')
@section('title', 'Pengaturan Sistem & Profil')

@section('content')
<div class="p-4 md:p-8 flex-1 max-w-6xl mx-auto w-full" x-data="{ currentTab: '{{ $activeTab ?? 'profile' }}' }">
    
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 flex items-center gap-3">
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 flex items-center gap-3">
        <span class="material-symbols-outlined text-[20px]">error</span>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-on-surface tracking-tight flex items-center gap-2.5">
                <span class="material-symbols-outlined text-primary text-[28px]">settings</span>
                Pengaturan
            </h1>
            <p class="text-sm text-on-surface-variant mt-1">
                Kelola profil identitas perusahaan, preferensi tampilan tema, status database, dan pemeliharaan sistem.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 bg-surface border border-outline-variant rounded-xl text-xs font-bold text-on-surface hover:bg-surface-container transition-all flex items-center gap-2 shadow-xs">
                <span class="material-symbols-outlined text-[16px]">visibility</span>
                Lihat Website
            </a>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex border-b border-outline-variant mb-8 overflow-x-auto gap-2 scrollbar-none">
        <button @click="currentTab = 'profile'" 
                :class="currentTab === 'profile' ? 'border-primary text-primary font-bold bg-primary/5' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                class="px-5 py-3 border-b-2 text-sm transition-all flex items-center gap-2 whitespace-nowrap rounded-t-xl cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">business</span>
            Profil Perusahaan
        </button>

        <button @click="currentTab = 'theme'" 
                :class="currentTab === 'theme' ? 'border-primary text-primary font-bold bg-primary/5' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                class="px-5 py-3 border-b-2 text-sm transition-all flex items-center gap-2 whitespace-nowrap rounded-t-xl cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">palette</span>
            Tampilan & Tema
        </button>

        <button @click="currentTab = 'database'" 
                :class="currentTab === 'database' ? 'border-primary text-primary font-bold bg-primary/5' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                class="px-5 py-3 border-b-2 text-sm transition-all flex items-center gap-2 whitespace-nowrap rounded-t-xl cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">table_chart</span>
            Struktur Tabel
        </button>

        <button @click="currentTab = 'backup'" 
                :class="currentTab === 'backup' ? 'border-primary text-primary font-bold bg-primary/5' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                class="px-5 py-3 border-b-2 text-sm transition-all flex items-center gap-2 whitespace-nowrap rounded-t-xl cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">backup</span>
            Backup &amp; Restore
        </button>

        <button @click="currentTab = 'system'" 
                :class="currentTab === 'system' ? 'border-primary text-primary font-bold bg-primary/5' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                class="px-5 py-3 border-b-2 text-sm transition-all flex items-center gap-2 whitespace-nowrap rounded-t-xl cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">tune</span>
            Sistem &amp; Cache
        </button>
    </div>

    <!-- ==================== TAB 1: PROFIL PERUSAHAAN ==================== -->
    <div x-show="currentTab === 'profile'" x-transition class="space-y-6">
        <form action="{{ route('admin.company.store') }}" method="POST" enctype="multipart/form-data" class="bg-surface rounded-2xl border border-outline-variant p-6 md:p-8 shadow-xs">
            @csrf
            
            <!-- Section: Info Dasar -->
            <div class="mb-8">
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                    <h3 class="text-base font-bold text-on-surface">Informasi Utama</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Nama Perusahaan / Brand <span class="text-error">*</span></label>
                        <input type="text" name="company_name" required value="{{ old('company_name', $profile->company_name ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="Contoh: Rhantech Digital Solution">
                        @error('company_name')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Tagline / Slogan</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $profile->tagline ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="Contoh: Digital Store & Software Agency">
                        @error('tagline')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Email Kontak <span class="text-error">*</span></label>
                        <input type="email" name="email" required value="{{ old('email', $profile->email ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="admin@rhantech.com">
                        @error('email')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Nomor Telepon <span class="text-error">*</span></label>
                        <input type="text" name="phone" required value="{{ old('phone', $profile->phone ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="+62 812-xxxx-xxxx">
                        @error('phone')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">WhatsApp Official</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $profile->whatsapp ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="628123456789">
                        @error('whatsapp')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Section: Deskripsi & Alamat -->
            <div class="mb-8">
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[20px]">description</span>
                    <h3 class="text-base font-bold text-on-surface">Deskripsi & Lokasi</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Ringkasan Singkat (Short Description)</label>
                        <input type="text" name="short_description" value="{{ old('short_description', $profile->short_description ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="Platform penyedia produk digital dan layanan IT profesional.">
                        @error('short_description')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Tentang Perusahaan (About Us)</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                  placeholder="Tuliskan profil lengkap mengenai visi perusahaan...">{{ old('description', $profile->description ?? '') }}</textarea>
                        @error('description')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Alamat Kantor / Domisili</label>
                        <textarea name="address" rows="2" 
                                  class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                  placeholder="Jl. Raya Utama No. 123, Jakarta...">{{ old('address', $profile->address ?? '') }}</textarea>
                        @error('address')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Section: Media Sosial & Link -->
            <div class="mb-8">
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[20px]">share</span>
                    <h3 class="text-base font-bold text-on-surface">Tautan & Media Sosial</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Website URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">language</span>
                            <input type="url" name="website" value="{{ old('website', $profile->website ?? '') }}" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="https://rhantech.com">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Instagram URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">photo_camera</span>
                            <input type="url" name="instagram" value="{{ old('instagram', $profile->instagram ?? '') }}" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="https://instagram.com/rhantech">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">LinkedIn URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">work</span>
                            <input type="url" name="linkedin" value="{{ old('linkedin', $profile->linkedin ?? '') }}" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="https://linkedin.com/company/rhantech">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">YouTube URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">smart_display</span>
                            <input type="url" name="youtube" value="{{ old('youtube', $profile->youtube ?? '') }}" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="https://youtube.com/@rhantech">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Facebook URL</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">public</span>
                            <input type="url" name="facebook" value="{{ old('facebook', $profile->facebook ?? '') }}" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                   placeholder="https://facebook.com/rhantech">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Brand Assets -->
            <div class="mb-8">
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[20px]">image</span>
                    <h3 class="text-base font-bold text-on-surface">Logo & Favicon</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-5 rounded-2xl bg-surface-container-low border border-outline-variant">
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-3">Logo Utama Perusahaan</label>
                        <div class="flex items-center gap-4 mb-4">
                            @if(isset($profile) && $profile->logo)
                                <img src="{{ asset('storage/' . $profile->logo) }}" class="h-16 max-w-[150px] object-contain p-2 bg-white rounded-xl border border-outline-variant shadow-xs">
                            @else
                                <div class="h-16 w-32 rounded-xl bg-surface-container-high border border-dashed border-outline-variant flex items-center justify-center text-xs text-on-surface-variant">
                                    Belum ada logo
                                </div>
                            @endif
                            <div class="text-xs text-on-surface-variant">
                                <p class="font-bold text-on-surface">Format PNG, JPG, atau SVG</p>
                                <p>Maksimal 2 MB dengan rasio persegi panjang / landscape.</p>
                            </div>
                        </div>
                        <input type="file" name="logo" accept="image/*" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-xs">
                        @error('logo')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div class="p-5 rounded-2xl bg-surface-container-low border border-outline-variant">
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-3">Favicon Browser</label>
                        <div class="flex items-center gap-4 mb-4">
                            @if(isset($profile) && $profile->favicon)
                                <img src="{{ asset('storage/' . $profile->favicon) }}" class="h-12 w-12 object-contain p-2 bg-white rounded-xl border border-outline-variant shadow-xs">
                            @else
                                <div class="h-12 w-12 rounded-xl bg-surface-container-high border border-dashed border-outline-variant flex items-center justify-center text-xs text-on-surface-variant">
                                    Icon
                                </div>
                            @endif
                            <div class="text-xs text-on-surface-variant">
                                <p class="font-bold text-on-surface">Format ICO, PNG 32x32</p>
                                <p>Maksimal 1 MB untuk ikon tab browser.</p>
                            </div>
                        </div>
                        <input type="file" name="favicon" accept="image/*" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-xs">
                        @error('favicon')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-4 border-t border-outline-variant">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:brightness-110 transition-all shadow-md flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Profil Perusahaan
                </button>
            </div>
        </form>
    </div>

    <!-- ==================== TAB 2: TEMA & TAMPILAN ==================== -->
    <div x-show="currentTab === 'theme'" x-transition style="display: none;" class="space-y-6">
        <div class="bg-surface rounded-2xl border border-outline-variant p-6 md:p-8 shadow-xs">
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                <span class="material-symbols-outlined text-primary text-[20px]">palette</span>
                <div>
                    <h3 class="text-base font-bold text-on-surface">Mode Tampilan & Warna</h3>
                    <p class="text-xs text-on-surface-variant">Sesuaikan tema visual sistem antarmuka dan preview tampilan website.</p>
                </div>
            </div>

            <!-- Theme Mode Selector -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Light Mode Card -->
                <div class="p-6 rounded-2xl border-2 border-outline-variant hover:border-primary/60 bg-white text-slate-800 transition-all cursor-pointer shadow-xs relative overflow-hidden"
                     onclick="localStorage.setItem('rhantech-theme', 'light'); document.documentElement.classList.remove('dark');">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">light_mode</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Mode Terang (Light)</h4>
                                <p class="text-xs text-slate-500">Latar bersih putih standar</p>
                            </div>
                        </div>
                    </div>
                    <div class="h-20 bg-slate-50 rounded-xl border border-slate-200 p-3 flex gap-2">
                        <div class="w-1/3 bg-white rounded-lg border border-slate-200 p-2">
                            <div class="h-2 w-8 bg-sky-500 rounded mb-1.5"></div>
                            <div class="h-1.5 w-12 bg-slate-200 rounded"></div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg border border-slate-200 p-2 flex flex-col justify-between">
                            <div class="h-2 w-16 bg-slate-300 rounded"></div>
                            <div class="h-4 w-full bg-slate-100 rounded"></div>
                        </div>
                    </div>
                </div>

                <!-- Dark Mode Card -->
                <div class="p-6 rounded-2xl border-2 border-outline-variant hover:border-primary/60 bg-[#0d1117] text-white transition-all cursor-pointer shadow-xs relative overflow-hidden"
                     onclick="localStorage.setItem('rhantech-theme', 'dark'); document.documentElement.classList.add('dark');">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">dark_mode</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-white">Mode Gelap (Dark)</h4>
                                <p class="text-xs text-slate-400">Tampilan GitHub-style OLED dark</p>
                            </div>
                        </div>
                    </div>
                    <div class="h-20 bg-[#161b22] rounded-xl border border-slate-700 p-3 flex gap-2">
                        <div class="w-1/3 bg-[#0d1117] rounded-lg border border-slate-700 p-2">
                            <div class="h-2 w-8 bg-sky-400 rounded mb-1.5"></div>
                            <div class="h-1.5 w-12 bg-slate-700 rounded"></div>
                        </div>
                        <div class="flex-1 bg-[#0d1117] rounded-lg border border-slate-700 p-2 flex flex-col justify-between">
                            <div class="h-2 w-16 bg-slate-600 rounded"></div>
                            <div class="h-4 w-full bg-slate-800 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Navigation to Other Appearance Pages -->
            <div class="p-5 rounded-2xl bg-surface-container-low border border-outline-variant">
                <h4 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-3">Halaman Pengaturan Tampilan Tambahan</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('admin.banners.index') }}" class="p-3 bg-surface rounded-xl border border-outline-variant hover:border-primary flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">view_carousel</span>
                            <div>
                                <div class="text-xs font-bold text-on-surface">Pengaturan Banner</div>
                                <div class="text-[11px] text-on-surface-variant">Banner hero homepage & slide promosi</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant text-[16px]">chevron_right</span>
                    </a>

                    <a href="{{ route('admin.popup_ads.index') }}" class="p-3 bg-surface rounded-xl border border-outline-variant hover:border-primary flex items-center justify-between transition-all">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">ad_units</span>
                            <div>
                                <div class="text-xs font-bold text-on-surface">Popup Iklan & Pengumuman</div>
                                <div class="text-[11px] text-on-surface-variant">Kelola modal promosi saat website dibuka</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant text-[16px]">chevron_right</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 3: DATABASE & STORAGE ==================== -->
    <div x-show="currentTab === 'database'" x-transition style="display: none;" class="space-y-6">
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 bg-surface rounded-2xl border border-outline-variant shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">database</span>
                </div>
                <div>
                    <div class="text-xs font-bold text-on-surface-variant uppercase">Database</div>
                    <div class="text-lg font-black text-on-surface truncate">{{ $dbStats['name'] ?? 'db_company' }}</div>
                    <div class="text-[11px] text-on-surface-variant">Driver: {{ $dbStats['connection'] ?? 'mysql' }}</div>
                </div>
            </div>

            <div class="p-5 bg-surface rounded-2xl border border-outline-variant shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">table_chart</span>
                </div>
                <div>
                    <div class="text-xs font-bold text-on-surface-variant uppercase">Total Tabel</div>
                    <div class="text-lg font-black text-on-surface">{{ $dbStats['tables_count'] ?? 0 }} Tabel</div>
                    <div class="text-[11px] text-on-surface-variant">{{ number_format($dbStats['total_rows'] ?? 0) }} Total Baris</div>
                </div>
            </div>

            <div class="p-5 bg-surface rounded-2xl border border-outline-variant shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">data_usage</span>
                </div>
                <div>
                    <div class="text-xs font-bold text-on-surface-variant uppercase">Ukuran Data</div>
                    <div class="text-lg font-black text-on-surface">{{ $dbStats['total_size'] ?? '0 MB' }}</div>
                    <div class="text-[11px] text-emerald-500 font-bold">Kondisi Normal</div>
                </div>
            </div>

            <div class="p-5 bg-surface rounded-2xl border border-outline-variant shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">terminal</span>
                </div>
                <div>
                    <div class="text-xs font-bold text-on-surface-variant uppercase">PHP / Laravel</div>
                    <div class="text-lg font-black text-on-surface">v{{ $dbStats['php_version'] ?? PHP_VERSION }}</div>
                    <div class="text-[11px] text-on-surface-variant">Laravel v{{ $dbStats['laravel_version'] ?? '11' }}</div>
                </div>
            </div>
        </div>

        <!-- Database Tables List -->
        <div class="bg-surface rounded-2xl border border-outline-variant overflow-hidden shadow-xs">
            <div class="p-6 border-b border-outline-variant flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-on-surface">Struktur Tabel Database</h3>
                    <p class="text-xs text-on-surface-variant">Daftar tabel dan pemakaian memori database MySQL Anda.</p>
                </div>
                <form action="{{ route('admin.company.optimize_database') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="migrate">
                    <button type="submit" class="px-4 py-2 bg-surface-container border border-outline-variant rounded-xl text-xs font-bold hover:bg-surface-container-high transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">sync</span>
                        Run Migrations
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto max-h-96">
                <table class="w-full text-left text-xs">
                    <thead class="bg-surface-container-low text-on-surface-variant uppercase font-bold sticky top-0">
                        <tr>
                            <th class="px-6 py-3">Nama Tabel</th>
                            <th class="px-6 py-3">Estimasi Baris</th>
                            <th class="px-6 py-3">Ukuran Storage</th>
                            <th class="px-6 py-3">Storage Engine</th>
                            <th class="px-6 py-3">Collation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($dbTables as $table)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-3 font-mono font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[15px] text-on-surface-variant">table_rows</span>
                                {{ $table['name'] }}
                            </td>
                            <td class="px-6 py-3 text-on-surface">{{ number_format($table['rows']) }}</td>
                            <td class="px-6 py-3 font-mono text-on-surface-variant">{{ $table['size'] }}</td>
                            <td class="px-6 py-3"><span class="px-2 py-0.5 rounded-md bg-surface-container text-on-surface text-[10px] font-bold">{{ $table['engine'] }}</span></td>
                            <td class="px-6 py-3 text-on-surface-variant text-[11px] font-mono">{{ $table['collation'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant">Data tabel tidak tersedia atau database koneksi offline.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ==================== TAB BACKUP & RESTORE ==================== -->
    <div x-show="currentTab === 'backup'" x-transition style="display: none;" class="space-y-6">

        {{-- Auto-download setelah backup berhasil --}}
        @if(session('backup_download'))
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                var link = document.createElement('a');
                link.href = '{{ route('admin.company.backup.download', session('backup_download')) }}';
                link.download = '{{ session('backup_download') }}';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        </script>
        @endif

        <div class="bg-surface rounded-2xl border border-outline-variant overflow-hidden shadow-xs">
            <div class="p-6 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">backup</span>
                        Backup & Restore Database
                    </h3>
                    <p class="text-xs text-on-surface-variant mt-1">Buat backup database dan restore jika diperlukan. Backup disimpan di server.</p>
                </div>
                <form action="{{ route('admin.company.backup') }}" method="POST">
                    @csrf
                    <button type="submit" onclick="this.disabled=true; this.innerText='Membuat backup...'; this.form.submit();"
                            class="px-5 py-2.5 bg-primary text-on-primary rounded-xl text-xs font-bold hover:brightness-110 transition-all flex items-center gap-2 shadow-sm whitespace-nowrap">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        Buat & Unduh Backup
                    </button>
                </form>
            </div>

            {{-- Daftar Backup --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-surface-container-low text-on-surface-variant uppercase font-bold">
                        <tr>
                            <th class="px-6 py-3">Nama File</th>
                            <th class="px-6 py-3">Ukuran</th>
                            <th class="px-6 py-3">Tanggal Dibuat</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($backups as $backup)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-3 font-mono text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[15px] text-on-surface-variant">description</span>
                                {{ $backup['filename'] }}
                            </td>
                            <td class="px-6 py-3 text-on-surface-variant">{{ $backup['size'] }}</td>
                            <td class="px-6 py-3 text-on-surface-variant">{{ $backup['created_at'] }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.company.backup.download', $backup['filename']) }}"
                                       class="px-3 py-1.5 bg-surface-container border border-outline-variant text-on-surface rounded-lg text-[11px] font-bold hover:bg-surface-container-high transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px]">download</span> Unduh
                                    </a>
                                    <form action="{{ route('admin.company.backup.delete', $backup['filename']) }}" method="POST"
                                          onsubmit="return confirm('Hapus backup ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-error/10 border border-error/30 text-error rounded-lg text-[11px] font-bold hover:bg-error/20 transition-all flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px]">delete</span> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">
                                Belum ada backup. Klik "Buat & Unduh Backup" untuk membuat backup pertama.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Restore --}}
            <div class="p-6 border-t border-outline-variant bg-surface-container-lowest">
                <h4 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-amber-500">restore</span>
                    Restore Database dari File SQL
                </h4>
                <p class="text-xs text-on-surface-variant mb-4">
                    ⚠️ <strong>Hati-hati:</strong> Restore akan menimpa data yang ada. Pastikan sudah backup terlebih dahulu.
                </p>
                <form action="{{ route('admin.company.restore') }}" method="POST" enctype="multipart/form-data"
                      onsubmit="return confirm('PERHATIAN: Restore akan menimpa database yang ada. Lanjutkan?');"
                      class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    @csrf
                    <input type="file" name="sql_file" accept=".sql,.txt" required
                           class="block text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-surface-container file:text-on-surface hover:file:bg-surface-container-high transition-all cursor-pointer">
                    <button type="submit"
                            class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm whitespace-nowrap">
                        <span class="material-symbols-outlined text-[16px]">restore</span>
                        Restore Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ==================== TAB 5: SISTEM & PEMELIHARAAN ==================== -->
    <div x-show="currentTab === 'system'" x-transition style="display: none;" class="space-y-6">
        
        <div class="bg-surface rounded-2xl border border-outline-variant p-6 md:p-8 shadow-xs">
            <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                <span class="material-symbols-outlined text-primary text-[20px]">build</span>
                <div>
                    <h3 class="text-base font-bold text-on-surface">Pemeliharaan & Cache Aplikasi</h3>
                    <p class="text-xs text-on-surface-variant">Jalankan aksi pembersihan cache dan optimasi performa backend Laravel.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Action 1: Clear Cache -->
                <div class="p-6 rounded-2xl bg-surface-container-low border border-outline-variant flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[22px]">cleaning_services</span>
                        </div>
                        <h4 class="font-bold text-sm text-on-surface">Bersihkan Cache Sistem (Cache Clear)</h4>
                        <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">
                            Membersihkan cache aplikasi, cache view blade, cache konfigurasi, dan compiled route agar perubahan terbaru langsung aktif.
                        </p>
                    </div>
                    <form action="{{ route('admin.company.optimize_database') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="action" value="clear_cache">
                        <button type="submit" class="w-full px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">refresh</span>
                            Clear Application Cache
                        </button>
                    </form>
                </div>

                <!-- Action 2: Optimize -->
                <div class="p-6 rounded-2xl bg-surface-container-low border border-outline-variant flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[22px]">speed</span>
                        </div>
                        <h4 class="font-bold text-sm text-on-surface">Optimasi Performa (Laravel Optimize)</h4>
                        <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">
                            Melakukan caching pada config dan route untuk mempercepat respon load time halaman pada mode produksi.
                        </p>
                    </div>
                    <form action="{{ route('admin.company.optimize_database') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="action" value="optimize">
                        <button type="submit" class="w-full px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">bolt</span>
                            Optimize Application
                        </button>
                    </form>
                </div>
            </div>

            <!-- Server Environment Details -->
            <div class="mt-8 pt-6 border-t border-outline-variant">
                <h4 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-4">Informasi Lingkungan Server</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-3.5 bg-surface-container-lowest rounded-xl border border-outline-variant">
                        <div class="text-[11px] text-on-surface-variant">Environment</div>
                        <div class="text-xs font-bold text-on-surface font-mono">{{ app()->environment() }}</div>
                    </div>
                    <div class="p-3.5 bg-surface-container-lowest rounded-xl border border-outline-variant">
                        <div class="text-[11px] text-on-surface-variant">App Debug Mode</div>
                        <div class="text-xs font-bold {{ config('app.debug') ? 'text-amber-500' : 'text-emerald-500' }} font-mono">
                            {{ config('app.debug') ? 'TRUE (Active)' : 'FALSE' }}
                        </div>
                    </div>
                    <div class="p-3.5 bg-surface-container-lowest rounded-xl border border-outline-variant">
                        <div class="text-[11px] text-on-surface-variant">App Timezone</div>
                        <div class="text-xs font-bold text-on-surface font-mono">{{ config('app.timezone') }}</div>
                    </div>
                    <div class="p-3.5 bg-surface-container-lowest rounded-xl border border-outline-variant">
                        <div class="text-[11px] text-on-surface-variant">Database Driver</div>
                        <div class="text-xs font-bold text-on-surface font-mono">{{ config('database.default') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

