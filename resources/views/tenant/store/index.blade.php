@extends('layouts.tenant')

@section('title', 'Pengaturan Profil Toko')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200" x-data="{ tab: 'profil' }">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Pengaturan Toko
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kelola informasi profil toko, logo brand, dan data rekening pencairan saldo.
                </p>
            </div>
            
            @if(isset($store) && $store->slug)
            <div class="flex items-center gap-3">
                <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] hover:bg-slate-50 dark:hover:bg-[#161f33] text-slate-700 dark:text-slate-200 text-xs md:text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-sky-500">storefront</span>
                    Halaman Toko Publik
                </a>
            </div>
            @endif
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs md:text-sm font-semibold flex items-center gap-2.5 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 text-xs md:text-sm font-semibold flex items-center gap-2.5 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">warning</span>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <!-- Main Card Form -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Navigation Tabs -->
            <div class="border-b border-slate-100 dark:border-[#222f49] px-6 flex items-center gap-8 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                <button type="button" @click="tab = 'profil'" :class="tab === 'profil' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">store</span> Profil Toko
                </button>
                <button type="button" @click="tab = 'sosmed'" :class="tab === 'sosmed' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">share</span> Media Sosial
                </button>
                <button type="button" @click="tab = 'rekening'" :class="tab === 'rekening' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">credit_card</span> Rekening Bank
                </button>
            </div>

            <form action="{{ route('tenant.store.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                @csrf

                <!-- TAB 1: PROFIL TOKO -->
                <div x-show="tab === 'profil'" class="space-y-6">
                    
                    <!-- Logo Toko -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                            Logo Brand Toko
                        </label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                            <div class="w-24 h-24 rounded-2xl bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 overflow-hidden flex items-center justify-center shrink-0 shadow-inner">
                                @if(isset($store) && $store->logo)
                                    <img id="logo-preview" src="{{ asset('storage/' . $store->logo) }}" alt="Logo" class="w-full h-full object-cover">
                                @else
                                    <img id="logo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($store->name ?? 'Toko') }}&background=0284c7&color=fff" alt="Logo" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <input type="file" name="logo" id="logo-input" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400
                                  file:mr-4 file:py-2.5 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-xs file:font-bold
                                  file:bg-sky-500/10 file:text-sky-600
                                  dark:file:bg-sky-500/20 dark:file:text-sky-400
                                  hover:file:bg-sky-500/20
                                  transition-all cursor-pointer
                                " onchange="previewImage(event)">
                                <p class="text-[11px] text-slate-400 mt-2">Disarankan rasio 1:1 (persegi). Format: JPG, PNG, WEBP. Maks. 2MB.</p>
                                @error('logo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nama Toko -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                            Nama Toko <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $store->name ?? '') }}" required
                            class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all" placeholder="Contoh: Digital Code Studio">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Custom URL / Slug Toko -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Tautan URL / Slug Toko
                            </label>
                            <span class="text-[11px] text-slate-400">Bebas ditentukan sendiri (unik)</span>
                        </div>
                        <div class="flex items-center rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] overflow-hidden focus-within:ring-2 focus-within:ring-sky-500/20 focus-within:border-sky-500">
                            <span class="px-3.5 py-2.5 text-xs md:text-sm font-semibold text-slate-400 border-r border-slate-200 dark:border-[#222f49] bg-slate-100/60 dark:bg-[#111726] select-none whitespace-nowrap">
                                {{ url('/') }}/
                            </span>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $store->slug ?? '') }}" placeholder="gudang-aplikasi"
                                class="flex-1 px-3.5 py-2.5 text-xs md:text-sm bg-transparent border-0 focus:outline-none text-slate-900 dark:text-white font-mono">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">
                            Gunakan huruf kecil, angka, atau strip (-). Contoh: <strong class="text-sky-600 dark:text-sky-400">gudang-aplikasi</strong> sehingga alamat tokomu menjadi <span class="font-mono text-[11px]">{{ url('/') }}/gudang-aplikasi</span>
                        </p>
                        @error('slug') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror

                        @if(!empty($store->slug))
                        <div x-data="{ copied: false, url: '{{ url('/' . $store->slug) }}' }" 
                             class="mt-3 p-3 rounded-2xl bg-sky-50/70 dark:bg-sky-950/20 border border-sky-200/80 dark:border-sky-800/40 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                            <div class="flex items-center gap-2 min-w-0 text-xs text-sky-800 dark:text-sky-300">
                                <span class="material-symbols-outlined text-[18px] text-sky-500 shrink-0">link</span>
                                <div class="truncate">
                                    <span class="text-[10px] uppercase font-bold text-sky-600 dark:text-sky-400 block sm:inline mr-1">Tautan Publik:</span>
                                    <strong class="font-mono text-xs select-all text-slate-900 dark:text-white">{{ url('/' . $store->slug) }}</strong>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" 
                                        @click="navigator.clipboard.writeText(url); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer"
                                        :class="copied ? 'bg-emerald-500 text-white' : 'bg-sky-500 hover:bg-sky-400 text-white'">
                                    <span class="material-symbols-outlined text-[15px]" x-text="copied ? 'check' : 'content_copy'"></span>
                                    <span x-text="copied ? 'Tersalin!' : 'Salin Bio Link'"></span>
                                </button>
                                <a href="{{ route('store.show', $store->slug) }}" target="_blank"
                                   class="px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-sky-200 dark:border-sky-800 text-xs font-bold text-sky-600 dark:text-sky-400 hover:bg-sky-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-1 shadow-xs">
                                    <span class="material-symbols-outlined text-[15px]">open_in_new</span>
                                    <span>Tes Buka</span>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Deskripsi Toko -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                            Deskripsi Singkat Toko
                        </label>
                        <textarea id="description" name="description" rows="3" 
                            class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all leading-relaxed" placeholder="Jelaskan spesialisasi produk digital toko Anda...">{{ old('description', $store->description ?? '') }}</textarea>
                        @error('description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-[#1d273d]">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-sky-500">location_on</span> Lokasi & Alamat (Opsional)
                        </h3>

                        <!-- Alamat Fisik -->
                        <div class="space-y-4">
                            <div>
                                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                    Alamat Domisili / Lokasi Toko
                                </label>
                                <textarea id="address" name="address" rows="2" 
                                    class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all" placeholder="Kota, Provinsi, Indonesia">{{ old('address', $store->address ?? '') }}</textarea>
                                @error('address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Hidden Geolocation Coordinates for Auto-Maps -->
                            <input type="hidden" name="latitude" id="geo-lat" value="">
                            <input type="hidden" name="longitude" id="geo-lng" value="">

                            <!-- Info Lokasi Otomatis (Readonly untuk Keamanan Superadmin) -->
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] flex items-start gap-3">
                                <span class="material-symbols-outlined text-[20px] text-sky-500 shrink-0 mt-0.5">my_location</span>
                                <div class="text-xs">
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block mb-0.5">Titik Lokasi Google Maps Otomatis</span>
                                    <p class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed">
                                        Koordinat dan tautan Google Maps akan otomatis digenerate oleh sistem saat formulir disimpan berdasarkan izin lokasi browser atau alamat yang Anda isi.
                                    </p>
                                    @if(isset($store) && $store->maps_location)
                                    <div class="mt-2">
                                        <a href="{{ $store->maps_location }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-sky-600 dark:text-sky-400 hover:underline text-[11px]">
                                            <span class="material-symbols-outlined text-[14px]">open_in_new</span> Lihat Titik Lokasi Tersimpan
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- TAB 2: MEDIA SOSIAL TOKO -->
                <div x-show="tab === 'sosmed'" class="space-y-6" style="display: none;"
                     x-data="{
                         platforms: [
                             { key: 'instagram', name: 'Instagram', icon: 'photo_camera', placeholder: 'https://instagram.com/username' },
                             { key: 'tiktok', name: 'TikTok', icon: 'music_video', placeholder: 'https://tiktok.com/@username' },
                             { key: 'facebook', name: 'Facebook', icon: 'public', placeholder: 'https://facebook.com/namahalaman' },
                             { key: 'youtube', name: 'YouTube', icon: 'smart_display', placeholder: 'https://youtube.com/@channel' },
                             { key: 'whatsapp', name: 'WhatsApp', icon: 'chat', placeholder: '08123456789 atau 628123456789' },
                             { key: 'x', name: 'X / Twitter', icon: 'tag', placeholder: 'https://x.com/username' },
                             { key: 'telegram', name: 'Telegram', icon: 'send', placeholder: 'https://t.me/username' },
                             { key: 'github', name: 'GitHub', icon: 'code', placeholder: 'https://github.com/username' },
                             { key: 'website', name: 'Website / Portofolio', icon: 'language', placeholder: 'https://domainanda.com' },
                             { key: 'custom', name: 'Custom Lainnya', icon: 'link', placeholder: 'https://...' }
                         ],
                         socialItems: {{ json_encode(!empty($store->social_links) && is_array($store->social_links) ? $store->social_links : [
                             ['platform' => 'instagram', 'name' => 'Instagram', 'url' => ''],
                             ['platform' => 'whatsapp', 'name' => 'WhatsApp', 'url' => '']
                         ]) }},
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
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-sky-500">share</span> Tautan Media Sosial & Kontak Toko
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                            Tautan sosmed yang diisi di sini akan tampil interaktif di halaman toko (khususnya versi mobile) untuk memudahkan calon pembeli mengunjungi dan menghubungi Anda.
                        </p>
                    </div>

                    <!-- Quick Add Platform Badges -->
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] space-y-2">
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                            + Tambah Cepat Platform
                        </span>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="p in platforms" :key="p.key">
                                <button type="button" 
                                        @click="addItem(p.key)"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] hover:border-sky-500 hover:text-sky-500 dark:hover:text-sky-400 text-slate-700 dark:text-slate-300 transition-all flex items-center gap-1.5 shadow-xs cursor-pointer">
                                    <span class="material-symbols-outlined text-[15px]" x-text="p.icon"></span>
                                    <span x-text="p.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Repeater List -->
                    <div class="space-y-3">
                        <template x-for="(item, index) in socialItems" :key="index">
                            <div class="p-3.5 sm:p-4 rounded-xl bg-white dark:bg-[#0e1526] border border-slate-200 dark:border-[#222f49] shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center gap-3 transition-all hover:border-slate-300 dark:hover:border-slate-700">
                                
                                <!-- Platform Select -->
                                <div class="w-full sm:w-44 shrink-0">
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Platform</label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined absolute left-2.5 text-[18px] text-sky-500 pointer-events-none" x-text="getIcon(item.platform)"></span>
                                        <select :name="`social_links[${index}][platform]`" 
                                                x-model="item.platform" 
                                                @change="onPlatformChange(index, $event)"
                                                class="w-full pl-9 pr-7 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-sky-500 font-semibold cursor-pointer">
                                            <template x-for="p in platforms" :key="p.key">
                                                <option :value="p.key" x-text="p.name" :selected="p.key === item.platform"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Custom Display Name -->
                                <div class="w-full sm:w-48 shrink-0">
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nama Tampilan</label>
                                    <input type="text" 
                                           :name="`social_links[${index}][name]`" 
                                           x-model="item.name" 
                                           placeholder="Misal: IG Official"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-sky-500">
                                </div>

                                <!-- URL / Link Input -->
                                <div class="flex-1 min-w-0">
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Tautan URL / Username</label>
                                    <input type="text" 
                                           :name="`social_links[${index}][url]`" 
                                           x-model="item.url" 
                                           :placeholder="getPlaceholder(item.platform)"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-sky-500 font-mono">
                                </div>

                                <!-- Delete Button -->
                                <div class="sm:self-end sm:pb-0.5 pt-1 sm:pt-0 flex justify-end">
                                    <button type="button" 
                                            @click="removeItem(index)" 
                                            title="Hapus tautan ini"
                                            class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg transition-colors cursor-pointer flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[19px]">delete</span>
                                        <span class="sm:hidden text-xs font-semibold ml-1">Hapus</span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="socialItems.length === 0" class="p-6 text-center text-slate-400 text-xs border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                            Belum ada tautan media sosial. Klik tombol tambah di atas untuk menambahkan link Instagram, WhatsApp, TikTok, Facebook, dll.
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="button" 
                                @click="addItem('custom')" 
                                class="px-4 py-2 rounded-xl text-xs font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/30 border border-sky-200 dark:border-sky-800 hover:bg-sky-100 dark:hover:bg-sky-900/40 transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                            <span>Tambah Tautan Kustom</span>
                        </button>
                    </div>
                </div>

                <!-- TAB 3: REKENING BANK -->
                <div x-show="tab === 'rekening'" class="space-y-6" style="display: none;">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500">account_balance</span> Rekening Pencairan Saldo Penjual
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                            Data rekening ini digunakan saat Anda mengajukan penarikan saldo penghasilan toko.
                        </p>
                    </div>

                    <div>
                        <label for="bank_account_info" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                            Informasi Rekening Bank Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="bank_account_info" name="bank_account_info" rows="5" 
                            class="w-full px-4 py-3 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all font-mono leading-relaxed" placeholder="Contoh:&#10;Bank BCA&#10;No. Rekening: 4370351509&#10;Atas Nama: RANRAN RAHAYU">{{ old('bank_account_info', $store->bank_account_info ?? '') }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-2">Pastikan nama pemilik rekening sesuai dengan nama identitas Anda untuk kelancaran verifikasi pencairan.</p>
                        @error('bank_account_info') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Persetujuan Kontrak Elektronik & Regulasi RI (Khusus Buka Toko Baru atau Informasi Status Legalitas) -->
                @if(!isset($store) || empty($store->id))
                <div class="p-5 rounded-2xl bg-sky-50/60 dark:bg-sky-950/20 border-2 border-sky-200/80 dark:border-sky-800/50 space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="p-2 bg-sky-500 text-white rounded-xl shrink-0 mt-0.5 shadow-sm">
                            <span class="material-symbols-outlined text-[20px]">gavel</span>
                        </span>
                        <div>
                            <h4 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white">Persetujuan Kontrak Elektronik & Kepatuhan Hukum RI</h4>
                            <p class="text-[11px] md:text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                                Berdasarkan UU ITE No. 1/2024, PP No. 80/2019 (PMSE), UU Hak Cipta No. 28/2014, dan UU Perlindungan Data Pribadi No. 27/2022, setiap penjual wajib menyatakan persetujuan secara sah sebelum mengaktifkan toko.
                            </p>
                        </div>
                    </div>

                    <div class="pl-0 sm:pl-11 space-y-3">
                        <div class="p-3 bg-white dark:bg-[#0c1220] rounded-xl border border-sky-100 dark:border-sky-900/40 text-[11px] text-slate-600 dark:text-slate-300 space-y-1.5">
                            <p class="font-semibold text-slate-800 dark:text-slate-200">Klausul Kewajiban Penjual (Tenant):</p>
                            <ul class="list-disc pl-4 space-y-1 text-slate-500 dark:text-slate-400">
                                <li>Menjamin bahwa seluruh produk digital yang dijual adalah karya asli atau memiliki hak lisensi distribusi resmi (<strong>Dilarang keras script bajakan/nulled/cracked</strong>).</li>
                                <li>Bertanggung jawab penuh atas kualitas produk, keaslian link download, dan layanan purna jual kepada pembeli.</li>
                                <li>Menyetujui bahwa platform Rhantech berhak melakukan penonaktifan sementara (*Notice and Takedown*) atau pembekuan akun jika terdapat laporan pelanggaran HAKI yang sah.</li>
                            </ul>
                        </div>

                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="agree_terms" value="1" required
                                   class="mt-1 w-4 h-4 text-sky-600 border-slate-300 rounded focus:ring-sky-500 shrink-0 cursor-pointer">
                            <span class="text-xs text-slate-700 dark:text-slate-300 leading-snug">
                                Saya telah membaca, memahami, dan menyetujui 
                                <a href="{{ route('legal.terms') }}" target="_blank" class="text-sky-600 dark:text-sky-400 font-bold hover:underline">Syarat & Ketentuan Layanan</a>, 
                                <a href="{{ route('legal.copyright') }}" target="_blank" class="text-sky-600 dark:text-sky-400 font-bold hover:underline">Kebijakan Hak Cipta & Lisensi (HAKI)</a>, 
                                <a href="{{ route('legal.refund') }}" target="_blank" class="text-sky-600 dark:text-sky-400 font-bold hover:underline">Kebijakan Refund</a>, serta 
                                <a href="{{ route('legal.privacy') }}" target="_blank" class="text-sky-600 dark:text-sky-400 font-bold hover:underline">Kebijakan Privasi (UU PDP)</a> Rhantech.
                            </span>
                        </label>
                        @error('agree_terms') <span class="text-xs text-rose-500 font-semibold block">{{ $message }}</span> @enderror
                    </div>
                </div>
                @else
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] flex items-center justify-between gap-4 text-xs">
                    <div class="flex items-center gap-2.5">
                        <span class="material-symbols-outlined text-[18px] text-emerald-500">verified_user</span>
                        <span class="text-slate-600 dark:text-slate-400">
                            Kontrak Elektronik Toko Disetujui pada: <strong class="text-slate-800 dark:text-slate-200">{{ $store->terms_accepted_at ? $store->terms_accepted_at->format('d M Y, H:i') : 'Saat Pendaftaran Toko' }}</strong>
                            @if($store->terms_accepted_ip)
                                <span class="text-slate-400 text-[11px]">(IP: {{ $store->terms_accepted_ip }})</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('legal.terms') }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline font-semibold text-[11px]">Syarat & Ketentuan ↗</a>
                        <a href="{{ route('legal.copyright') }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline font-semibold text-[11px]">Hak Cipta ↗</a>
                    </div>
                </div>
                @endif

                <!-- Action Submit Button -->
                <div class="pt-6 border-t border-slate-100 dark:border-[#1d273d] flex items-center justify-end gap-3">
                    <a href="{{ route('tenant.dashboard') }}" class="px-5 py-2.5 text-xs md:text-sm font-semibold border border-slate-200 dark:border-[#222f49] text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-[#161f33] transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs md:text-sm font-bold text-white bg-sky-500 hover:bg-sky-400 rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all flex items-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">store</span>
                        {{ isset($store) && $store->id ? 'Simpan Pengaturan Toko' : 'Buka Toko Sekarang' }}
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('logo-preview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Otomatis deteksi koordinat browser saat halaman dibuka
document.addEventListener('DOMContentLoaded', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latInput = document.getElementById('geo-lat');
            const lngInput = document.getElementById('geo-lng');
            if (latInput && lngInput) {
                latInput.value = position.coords.latitude;
                lngInput.value = position.coords.longitude;
            }
        }, function(error) {
            // Geolocation fallback to address
            console.log('Geolocation permission skipped or unavailable:', error.message);
        });
    }
});
</script>
@endsection
