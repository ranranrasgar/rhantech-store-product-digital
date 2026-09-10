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

        <button @click="currentTab = 'hero'" 
                :class="currentTab === 'hero' ? 'border-primary text-primary font-bold bg-primary/5' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                class="px-5 py-3 border-b-2 text-sm transition-all flex items-center gap-2 whitespace-nowrap rounded-t-xl cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">view_headline</span>
            Hero Beranda &amp; Toko
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
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Tentang Perusahaan (About Us / Profil Lengkap)</label>
                        <textarea name="description" rows="5" 
                                  class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                  placeholder="Tuliskan cerita dan profil lengkap perusahaan yang akan tampil di halaman /about...">{{ old('description', $profile->description ?? '') }}</textarea>
                        @error('description')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Visi Perusahaan</label>
                            <textarea name="vision" rows="3" 
                                      class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                      placeholder="Visi perusahaan ke depan...">{{ old('vision', $profile->vision ?? '') }}</textarea>
                            @error('vision')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Misi Perusahaan</label>
                            <textarea name="mission" rows="3" 
                                      class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                      placeholder="Misi-misi yang dijalankan...">{{ old('mission', $profile->mission ?? '') }}</textarea>
                            @error('mission')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Tahun Berdiri / Pengalaman (Founded Year)</label>
                        <input type="text" name="founded_year" value="{{ old('founded_year', $profile->founded_year ?? '') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="Contoh: 2018 atau 6+ Tahun Pengalaman">
                        @error('founded_year')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
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

            <!-- Section: Media Sosial & Link (Dynamic Repeater) -->
            @php
                $initialSocial = !empty($profile->social_links) && is_array($profile->social_links) ? $profile->social_links : [];
                if (empty($initialSocial)) {
                    if (!empty($profile->instagram)) $initialSocial[] = ['platform' => 'instagram', 'name' => 'Instagram', 'url' => $profile->instagram];
                    if (!empty($profile->facebook))  $initialSocial[] = ['platform' => 'facebook',  'name' => 'Facebook',  'url' => $profile->facebook];
                    if (!empty($profile->youtube))   $initialSocial[] = ['platform' => 'youtube',   'name' => 'YouTube',   'url' => $profile->youtube];
                    if (!empty($profile->website))   $initialSocial[] = ['platform' => 'website',   'name' => 'Website',   'url' => $profile->website];
                    if (!empty($profile->linkedin))  $initialSocial[] = ['platform' => 'linkedin',  'name' => 'LinkedIn',  'url' => $profile->linkedin];
                }
                if (empty($initialSocial)) {
                    $initialSocial = [
                        ['platform' => 'instagram', 'name' => 'Instagram', 'url' => ''],
                        ['platform' => 'whatsapp',  'name' => 'WhatsApp',  'url' => '']
                    ];
                }
            @endphp

            <div class="mb-8 space-y-4"
                 x-data="{
                     platforms: [
                         { key: 'instagram', name: 'Instagram', icon: 'photo_camera', placeholder: 'https://instagram.com/username' },
                         { key: 'tiktok', name: 'TikTok', icon: 'music_note', placeholder: 'https://tiktok.com/@username' },
                         { key: 'facebook', name: 'Facebook', icon: 'thumb_up', placeholder: 'https://facebook.com/username' },
                         { key: 'youtube', name: 'YouTube', icon: 'smart_display', placeholder: 'https://youtube.com/@channel' },
                         { key: 'whatsapp', name: 'WhatsApp', icon: 'chat', placeholder: '08123456789 atau 628123456789' },
                         { key: 'x', name: 'X / Twitter', icon: 'tag', placeholder: 'https://x.com/username' },
                         { key: 'telegram', name: 'Telegram', icon: 'send', placeholder: 'https://t.me/username' },
                         { key: 'github', name: 'GitHub', icon: 'code', placeholder: 'https://github.com/username' },
                         { key: 'website', name: 'Website / Portofolio', icon: 'language', placeholder: 'https://domainanda.com' },
                         { key: 'custom', name: 'Custom Lainnya', icon: 'link', placeholder: 'https://...' }
                     ],
                     socialItems: {{ json_encode($initialSocial) }},
                     addItem(platformKey = 'custom') {
                         const p = this.platforms.find(x => x.key === platformKey) || { name: 'Custom', key: 'custom' };
                         this.socialItems.push({
                             platform: p.key,
                             name: p.name,
                             url: ''
                         });
                     },
                     removeItem(index) {
                         this.socialItems.splice(index, 1);
                     },
                     getIcon(platform) {
                         const p = this.platforms.find(x => x.key === platform);
                         return p ? p.icon : 'link';
                     },
                     getPlaceholder(platform) {
                         const p = this.platforms.find(x => x.key === platform);
                         return p ? p.placeholder : 'https://...';
                     },
                     onPlatformChange(index, event) {
                         const selectedKey = event.target.value;
                         const p = this.platforms.find(x => x.key === selectedKey);
                         if (p && (!this.socialItems[index].name || this.platforms.some(pl => pl.name === this.socialItems[index].name))) {
                             this.socialItems[index].name = p.name;
                         }
                     }
                 }">
                
                <div>
                    <div class="flex items-center gap-2 pb-2 mb-1 border-b border-outline-variant">
                        <span class="material-symbols-outlined text-primary text-[20px]">share</span>
                        <h3 class="text-base font-bold text-on-surface">Tautan Media Sosial & Kontak</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant">
                        Tautan sosmed yang diisi di sini akan tampil interaktif di header, footer, dan kontak website untuk memudahkan pengunjung mengunjungi dan menghubungi Anda.
                    </p>
                </div>

                <!-- Quick Add Platform Badges -->
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant space-y-2">
                    <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider block">
                        + Tambah Cepat Platform
                    </span>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="p in platforms" :key="p.key">
                            <button type="button" 
                                    @click="addItem(p.key)"
                                    class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-surface-container-lowest border border-outline-variant hover:border-primary hover:text-primary text-on-surface transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                <span class="material-symbols-outlined text-[15px] text-primary" x-text="p.icon"></span>
                                <span x-text="p.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Repeater List -->
                <div class="space-y-3">
                    <template x-for="(item, index) in socialItems" :key="index">
                        <div class="p-3.5 sm:p-4 rounded-xl bg-surface-container-lowest border border-outline-variant shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center gap-3 transition-all hover:border-primary/50">
                            
                            <!-- Platform Select -->
                            <div class="w-full sm:w-44 shrink-0">
                                <label class="block text-[10px] font-bold uppercase text-on-surface-variant mb-1">Platform</label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-2.5 text-[18px] text-primary pointer-events-none" x-text="getIcon(item.platform)"></span>
                                    <select :name="`social_links[${index}][platform]`" 
                                            x-model="item.platform" 
                                            @change="onPlatformChange(index, $event)"
                                            class="w-full pl-9 pr-7 py-2 text-xs bg-surface-container-low border border-outline-variant rounded-lg text-on-surface focus:outline-none focus:border-primary font-semibold cursor-pointer">
                                        <template x-for="p in platforms" :key="p.key">
                                            <option :value="p.key" x-text="p.name" :selected="p.key === item.platform"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Custom Display Name -->
                            <div class="w-full sm:w-48 shrink-0">
                                <label class="block text-[10px] font-bold uppercase text-on-surface-variant mb-1">Nama Tampilan</label>
                                <input type="text" 
                                       :name="`social_links[${index}][name]`" 
                                       x-model="item.name" 
                                       placeholder="Misal: IG Official"
                                       class="w-full px-3 py-2 text-xs bg-surface-container-low border border-outline-variant rounded-lg text-on-surface focus:outline-none focus:border-primary font-medium">
                            </div>

                            <!-- URL / Link Input -->
                            <div class="flex-1 min-w-0">
                                <label class="block text-[10px] font-bold uppercase text-on-surface-variant mb-1">Tautan URL / Username</label>
                                <input type="text" 
                                       :name="`social_links[${index}][url]`" 
                                       x-model="item.url" 
                                       :placeholder="getPlaceholder(item.platform)"
                                       class="w-full px-3 py-2 text-xs bg-surface-container-low border border-outline-variant rounded-lg text-on-surface focus:outline-none focus:border-primary font-mono">
                            </div>

                            <!-- Delete Button -->
                            <div class="sm:self-end sm:pb-0.5 pt-1 sm:pt-0 flex justify-end">
                                <button type="button" 
                                        @click="removeItem(index)" 
                                        title="Hapus tautan ini"
                                        class="p-2 text-rose-500 hover:bg-rose-500/10 rounded-lg transition-colors cursor-pointer flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[19px]">delete</span>
                                    <span class="sm:hidden text-xs font-semibold ml-1">Hapus</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <div x-show="socialItems.length === 0" class="p-6 text-center text-on-surface-variant text-xs border-2 border-dashed border-outline-variant rounded-xl">
                        Belum ada tautan media sosial. Klik tombol tambah di atas untuk menambahkan link Instagram, WhatsApp, TikTok, Facebook, dll.
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" 
                            @click="addItem('custom')" 
                            class="px-4 py-2 rounded-xl text-xs font-bold text-primary bg-primary/10 border border-primary/20 hover:bg-primary/20 transition-colors flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                        <span>Tambah Tautan Kustom</span>
                    </button>
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

    <!-- ==================== TAB: HERO BERANDA & TOKO ==================== -->
    <div x-show="currentTab === 'hero'" x-transition style="display: none;" class="space-y-6">
        <form action="{{ route('admin.company.store') }}" method="POST" enctype="multipart/form-data" class="bg-surface rounded-2xl border border-outline-variant p-6 md:p-8 shadow-xs">
            @csrf
            <input type="hidden" name="redirect_tab" value="hero">
            <!-- Menjaga field wajib info dasar -->
            <input type="hidden" name="company_name" value="{{ $profile->company_name ?? 'Rhantech' }}">
            <input type="hidden" name="email" value="{{ $profile->email ?? 'admin@rhantech.com' }}">
            <input type="hidden" name="phone" value="{{ $profile->phone ?? '08123456789' }}">
            <input type="hidden" name="tagline" value="{{ $profile->tagline ?? '' }}">
            <input type="hidden" name="whatsapp" value="{{ $profile->whatsapp ?? '' }}">
            <input type="hidden" name="address" value="{{ $profile->address ?? '' }}">
            <input type="hidden" name="short_description" value="{{ $profile->short_description ?? '' }}">
            <input type="hidden" name="description" value="{{ $profile->description ?? '' }}">
            <input type="hidden" name="vision" value="{{ $profile->vision ?? '' }}">
            <input type="hidden" name="mission" value="{{ $profile->mission ?? '' }}">
            <input type="hidden" name="founded_year" value="{{ $profile->founded_year ?? '' }}">
            <input type="hidden" name="facebook" value="{{ $profile->facebook ?? '' }}">
            <input type="hidden" name="instagram" value="{{ $profile->instagram ?? '' }}">
            <input type="hidden" name="linkedin" value="{{ $profile->linkedin ?? '' }}">
            <input type="hidden" name="website" value="{{ $profile->website ?? '' }}">
            <input type="hidden" name="youtube" value="{{ $profile->youtube ?? '' }}">

            <!-- Header Section Hero Tab -->
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-outline-variant">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-[24px]">view_headline</span>
                    <div>
                        <h3 class="text-base font-bold text-on-surface">Kustomisasi Hero Beranda &amp; Showcase Toko</h3>
                        <p class="text-xs text-on-surface-variant">Atur kata-kata, gambar banner hero, atau tampilkan 10 Toko Terfavorit (terlaris) bergantian.</p>
                    </div>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                    Preview di Home <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                </a>
            </div>

            <!-- Pilih Mode Tampilan Hero -->
            <div class="mb-8 p-5 bg-surface-container-low rounded-2xl border border-outline-variant">
                <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
                    <span class="material-symbols-outlined text-[16px] align-middle text-primary mr-1">dashboard_customize</span>
                    Mode Tampilan Utama Hero Section
                </label>
                <p class="text-xs text-on-surface-variant mb-4">
                    Pilih apa yang ingin ditampilkan di area hero paling atas halaman depan (Home):
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ selectedMode: '{{ old('hero_mode', $profile->hero_mode ?? 'custom') }}' }">
                    <label :class="selectedMode === 'custom' ? 'border-primary ring-2 ring-primary/20 bg-surface' : 'border-outline-variant bg-surface-container-lowest'" 
                           class="p-4 rounded-xl border cursor-pointer flex flex-col justify-between transition-all hover:border-primary/50">
                        <div class="flex items-start gap-3">
                            <input type="radio" name="hero_mode" value="custom" x-model="selectedMode" class="mt-1 text-primary focus:ring-primary">
                            <div>
                                <span class="font-bold text-sm text-on-surface block">1. Banner &amp; Teks Kustom</span>
                                <span class="text-xs text-on-surface-variant block mt-1">Menampilkan kata-kata promosi, tombol CTA, dan foto/gambar pilihan admin.</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-primary mt-3 inline-block">Default Platform</span>
                    </label>

                    <label :class="selectedMode === 'top_stores' ? 'border-primary ring-2 ring-primary/20 bg-surface' : 'border-outline-variant bg-surface-container-lowest'" 
                           class="p-4 rounded-xl border cursor-pointer flex flex-col justify-between transition-all hover:border-primary/50">
                        <div class="flex items-start gap-3">
                            <input type="radio" name="hero_mode" value="top_stores" x-model="selectedMode" class="mt-1 text-primary focus:ring-primary">
                            <div>
                                <span class="font-bold text-sm text-on-surface block">2. 10 Toko Terfavorit</span>
                                <span class="text-xs text-on-surface-variant block mt-1">Area hero langsung menampilkan Carousel bergantian 10 Toko Terfavorit &amp; Terlaris.</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mt-3 inline-block">Showcase Tenant</span>
                    </label>

                    <label :class="selectedMode === 'both' ? 'border-primary ring-2 ring-primary/20 bg-surface' : 'border-outline-variant bg-surface-container-lowest'" 
                           class="p-4 rounded-xl border cursor-pointer flex flex-col justify-between transition-all hover:border-primary/50">
                        <div class="flex items-start gap-3">
                            <input type="radio" name="hero_mode" value="both" x-model="selectedMode" class="mt-1 text-primary focus:ring-primary">
                            <div>
                                <span class="font-bold text-sm text-on-surface block">3. Keduanya (Banner + Toko)</span>
                                <span class="text-xs text-on-surface-variant block mt-1">Hero teks di sisi kiri, dan slider 10 Toko Terfavorit di sisi kanan menggantikan foto biasa.</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 mt-3 inline-block">Kombinasi Interaktif</span>
                    </label>
                </div>
            </div>

            <!-- Teks & Kata-kata Hero -->
            <div class="mb-8">
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[20px]">edit_note</span>
                    <h4 class="text-sm font-bold text-on-surface">Teks &amp; Kata-kata Promosi Hero</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Badge Text (Label Kecil Di Atas Judul)</label>
                        <input type="text" name="hero_badge" value="{{ old('hero_badge', $profile->hero_badge ?? 'Marketplace Produk Digital') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="Contoh: Marketplace Produk Digital atau Pilihan Komunitas & Platform">
                        <p class="text-[11px] text-on-surface-variant mt-1">Muncul sebagai kapsul kecil tepat di atas judul besar.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Judul Utama (Headline)</label>
                        <input type="text" name="hero_title" value="{{ old('hero_title', $profile->hero_title ?? 'Katalog Developer & Aplikasi Siap Pakai') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="Contoh: Katalog Developer & Aplikasi Siap Pakai">
                        <p class="text-[11px] text-on-surface-variant mt-1">Teks setelah tanda '&' atau kata 'Digital' otomatis diberi warna aksen tema.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Deskripsi / Sub-judul Hero</label>
                        <textarea name="hero_subtitle" rows="3" 
                                  class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                  placeholder="Tuliskan deskripsi hero yang natural dan komunikatif...">{{ old('hero_subtitle', $profile->hero_subtitle ?? 'Temukan source code siap deploy, template aplikasi, dan sistem digital berkualitas langsung dari developer terverifikasi untuk mempercepat proyek Anda.') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tombol CTA & Floating Stats -->
            <div class="mb-8">
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[20px]">smart_button</span>
                    <h4 class="text-sm font-bold text-on-surface">Tombol Aksi (Call To Action) &amp; Floating Badge</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant">
                        <span class="font-bold text-xs text-primary uppercase tracking-wider block mb-3">Tombol Utama (Gradient Button)</span>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] font-bold text-on-surface mb-1">Teks Tombol</label>
                                <input type="text" name="hero_btn_primary_text" value="{{ old('hero_btn_primary_text', $profile->hero_btn_primary_text ?? 'View Our Work') }}" 
                                       class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-xl text-xs focus:ring-2 focus:ring-primary/20"
                                       placeholder="Contoh: View Our Work atau Lihat Semua Produk">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-on-surface mb-1">URL / Link Tujuan</label>
                                <input type="text" name="hero_btn_primary_url" value="{{ old('hero_btn_primary_url', $profile->hero_btn_primary_url ?? '/projects') }}" 
                                       class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-xl text-xs focus:ring-2 focus:ring-primary/20"
                                       placeholder="Contoh: /projects atau /katalog">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant">
                        <span class="font-bold text-xs text-on-surface uppercase tracking-wider block mb-3">Tombol Kedua (Outline Button)</span>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] font-bold text-on-surface mb-1">Teks Tombol</label>
                                <input type="text" name="hero_btn_secondary_text" value="{{ old('hero_btn_secondary_text', $profile->hero_btn_secondary_text ?? "Let's Talk") }}" 
                                       class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-xl text-xs focus:ring-2 focus:ring-primary/20"
                                       placeholder="Contoh: Let's Talk atau Hubungi Kami">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-on-surface mb-1">URL / Link Tujuan</label>
                                <input type="text" name="hero_btn_secondary_url" value="{{ old('hero_btn_secondary_url', $profile->hero_btn_secondary_url ?? '/contact') }}" 
                                       class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-xl text-xs focus:ring-2 focus:ring-primary/20"
                                       placeholder="Contoh: /contact atau /store">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Nilai Statistik Mengambang (Floating Stats)</label>
                        <input type="text" name="hero_stats_val" value="{{ old('hero_stats_val', $profile->hero_stats_val ?? '99%') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="Contoh: 99% atau 10k+">
                        <p class="text-[11px] text-on-surface-variant mt-1">Angka atau nilai utama yang muncul di kartu badge animasi.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Label Statistik Mengambang</label>
                        <input type="text" name="hero_stats_label" value="{{ old('hero_stats_label', $profile->hero_stats_label ?? 'Project Success Rate') }}" 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-xl text-sm focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                               placeholder="Contoh: Project Success Rate atau Transaksi Sukses">
                    </div>
                </div>
            </div>

            <!-- Upload Gambar Hero -->
            <div class="mb-8">
                <div class="flex items-center gap-2 pb-3 mb-6 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[20px]">add_photo_alternate</span>
                    <h4 class="text-sm font-bold text-on-surface">Gambar Hero Banner</h4>
                </div>

                <div class="p-5 rounded-2xl bg-surface-container-low border border-outline-variant">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                        <div>
                            <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Upload Foto / Banner Hero Baru</label>
                            <input type="file" name="hero_image" accept="image/*" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-xl text-xs">
                            <p class="text-[11px] text-on-surface-variant mt-2">
                                Format: JPG, PNG, WEBP (Maksimal 3MB). Disarankan foto vertikal/portrait atau rasio 4:5 berkualitas tinggi.
                            </p>
                            @error('hero_image')<span class="text-error text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Preview Gambar Saat Ini:</label>
                            @if(isset($profile) && $profile->hero_image)
                                <div class="relative w-40 h-48 rounded-xl overflow-hidden border border-outline-variant shadow-sm">
                                    <img src="{{ asset('storage/' . $profile->hero_image) }}" class="w-full h-full object-cover">
                                    <span class="absolute bottom-1 right-1 px-2 py-0.5 rounded bg-black/70 text-[10px] text-white font-bold">Kustom</span>
                                </div>
                            @else
                                <div class="relative w-40 h-48 rounded-xl overflow-hidden border border-outline-variant shadow-sm">
                                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                                    <span class="absolute bottom-1 right-1 px-2 py-0.5 rounded bg-black/70 text-[10px] text-white font-bold">Default</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button Hero -->
            <div class="flex justify-end pt-4 border-t border-outline-variant">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:brightness-110 transition-all shadow-md flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Pengaturan Hero &amp; Toko
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

        <!-- Media Storage Breakdown Card -->
        <div class="bg-surface rounded-2xl border border-outline-variant overflow-hidden shadow-xs">
            <div class="p-6 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">folder_special</span>
                        Penggunaan Media Storage (storage/app/public)
                    </h3>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Total pemakaian: <strong>{{ $mediaStats['total_size'] ?? '0 B' }}</strong> ({{ number_format($mediaStats['file_count'] ?? 0) }} file)
                    </p>
                </div>
                <button type="button" @click="currentTab = 'backup'" class="px-4 py-2 bg-primary/10 border border-primary/20 text-primary rounded-xl text-xs font-bold hover:bg-primary/20 transition-all flex items-center gap-1.5 w-fit">
                    <span class="material-symbols-outlined text-[16px]">cloud_sync</span>
                    Kelola Backup Media
                </button>
            </div>
            <div class="p-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                @forelse($mediaStats['folders'] ?? [] as $folder)
                <div class="p-3 bg-surface-container-low rounded-xl border border-outline-variant/60 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">folder</span>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-on-surface truncate capitalize">{{ $folder['name'] }}</div>
                        <div class="text-[11px] text-on-surface-variant">{{ $folder['count'] }} file &bull; {{ $folder['size'] }}</div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-4 text-xs text-on-surface-variant">Belum ada file media yang tersimpan.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ==================== TAB BACKUP & RESTORE ==================== -->
    <div x-show="currentTab === 'backup'" x-transition style="display: none;" class="space-y-6">

        <!-- SECTION 1: BACKUP MEDIA & ASSET STORAGE -->
        <div class="bg-surface rounded-2xl border border-outline-variant overflow-hidden shadow-xs">
            <div class="p-6 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-4 bg-primary/[0.02]">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">Media Storage</span>
                        <h3 class="text-base font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[22px]">perm_media</span>
                            Backup Media &amp; Gambar
                        </h3>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-1.5">
                        Mengarsipkan seluruh file upload gambar &amp; dokumen terkait data (produk, avatar, banner, logo, klien, ads) dalam format arsip <strong>.zip</strong>.
                    </p>
                    <div class="flex items-center gap-3 mt-2 text-xs text-on-surface-variant font-medium">
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-primary">data_usage</span> Pemakaian: <strong class="text-on-surface ml-0.5">{{ $mediaStats['total_size'] ?? '0 B' }}</strong></span>
                        <span>&bull;</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px] text-primary">image</span> Total File: <strong class="text-on-surface ml-0.5">{{ number_format($mediaStats['file_count'] ?? 0) }} file</strong></span>
                    </div>
                </div>
                <form action="{{ route('admin.company.backup_media') }}" method="POST">
                    @csrf
                    <button type="submit" onclick="this.disabled=true; this.innerHTML='<span class=\'material-symbols-outlined text-[16px] animate-spin\'>sync</span> Mengompres media...'; this.form.submit();"
                            class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm whitespace-nowrap cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">archive</span>
                        Buat Backup Media (.zip)
                    </button>
                </form>
            </div>

            {{-- Daftar Backup Media --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-surface-container-low text-on-surface-variant uppercase font-bold">
                        <tr>
                            <th class="px-6 py-3">Nama Arsip Media</th>
                            <th class="px-6 py-3">Ukuran Arsip</th>
                            <th class="px-6 py-3">Tanggal Dibuat</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($mediaBackups as $mBackup)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-3 font-mono text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[17px] text-emerald-500">folder_zip</span>
                                <span class="font-bold">{{ $mBackup['filename'] }}</span>
                            </td>
                            <td class="px-6 py-3 text-on-surface-variant font-mono">{{ $mBackup['size'] }}</td>
                            <td class="px-6 py-3 text-on-surface-variant">{{ $mBackup['created_at'] }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.company.backup_media.download', $mBackup['filename']) }}"
                                       class="px-3 py-1.5 bg-surface-container border border-outline-variant text-on-surface rounded-lg text-[11px] font-bold hover:bg-surface-container-high transition-all flex items-center gap-1 shadow-2xs">
                                        <span class="material-symbols-outlined text-[13px] text-emerald-500">download</span> Unduh ZIP
                                    </a>
                                    <form action="{{ route('admin.company.backup_media.delete', $mBackup['filename']) }}" method="POST"
                                          onsubmit="return confirm('Hapus file backup media ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-error/10 border border-error/30 text-error rounded-lg text-[11px] font-bold hover:bg-error/20 transition-all flex items-center gap-1 cursor-pointer">
                                            <span class="material-symbols-outlined text-[13px]">delete</span> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[32px] text-on-surface-variant/40">perm_media</span>
                                    <span>Belum ada backup media. Klik <strong>"Buat Backup Media (.zip)"</strong> di atas untuk mengarsipkan semua media upload.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Restore Media --}}
            <div class="p-6 border-t border-outline-variant bg-surface-container-lowest">
                <h4 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-emerald-600">unarchive</span>
                    Restore Media &amp; Gambar dari File ZIP
                </h4>
                <p class="text-xs text-on-surface-variant mb-4">
                    Unggah file backup media <strong>.zip</strong> untuk mengekstrak ulang file gambar &amp; asset ke direktori media publik. File dengan nama sama akan diperbarui otomatis.
                </p>
                <form action="{{ route('admin.company.restore_media') }}" method="POST" enctype="multipart/form-data"
                      onsubmit="return confirm('Restore media akan mengekstrak file ke storage publik. Lanjutkan?');"
                      class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                    @csrf
                    <input type="file" name="media_zip" accept=".zip" required
                           class="block text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-surface-container file:text-on-surface hover:file:bg-surface-container-high transition-all cursor-pointer">
                    <button type="submit"
                            class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm whitespace-nowrap cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">unarchive</span>
                        Restore Media
                    </button>
                </form>
            </div>
        </div>

        <!-- SECTION 2: BACKUP DATABASE -->
        <div class="bg-surface rounded-2xl border border-outline-variant overflow-hidden shadow-xs">
            <div class="p-6 border-b border-outline-variant flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-sky-500/10 text-sky-600 border border-sky-500/20">SQL Data</span>
                        <h3 class="text-base font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">database</span>
                            Backup &amp; Restore Database (SQL)
                        </h3>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-1.5">Buat backup database MySQL ke server dan unduh kapan saja dalam format <strong>.sql</strong>.</p>
                </div>
                <form action="{{ route('admin.company.backup') }}" method="POST">
                    @csrf
                    <button type="submit" onclick="this.disabled=true; this.innerHTML='<span class=\'material-symbols-outlined text-[16px] animate-spin\'>sync</span> Membuat backup...'; this.form.submit();"
                            class="px-5 py-2.5 bg-primary text-on-primary rounded-xl text-xs font-bold hover:brightness-110 transition-all flex items-center gap-2 shadow-sm whitespace-nowrap cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                        Buat Backup Database
                    </button>
                </form>
            </div>

            {{-- Daftar Backup Database --}}
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
                                Belum ada backup. Klik "Buat Backup Database" untuk membuat backup pertama.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Restore Database --}}
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

