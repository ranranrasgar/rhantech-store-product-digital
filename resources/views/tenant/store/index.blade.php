@extends('layouts.tenant')

@section('title', 'Profil & Pengaturan')

@section('content')
<div class="flex-1 overflow-y-auto p-3.5 sm:p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200" 
     x-data="{ 
         tab: '{{ request('tab', 'profil') }}',
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
         },
         currentTheme: localStorage.getItem('rhantech-theme') || (document.documentElement.classList.contains('dark') ? 'dark' : 'light')
     }">
    <div class="max-w-4xl mx-auto space-y-4 md:space-y-6 pb-12">
        
        <!-- Flash Session Alerts -->
        @if(session('success'))
            <div class="p-3.5 md:p-4 rounded-xl md:rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs md:text-sm font-semibold flex items-center gap-2.5 shadow-2xs">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[20px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3.5 md:p-4 rounded-xl md:rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-xs md:text-sm font-semibold flex items-center gap-2.5 shadow-2xs">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[20px]">warning</span>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-3.5 md:p-4 rounded-xl md:rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs md:text-sm space-y-1 shadow-2xs">
                <div class="font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">error</span> Mohon periksa kesalahan input:
                </div>
                <ul class="list-disc pl-6 text-[11px] space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- HEADER (Mobile & Desktop)                                                 -->
        <!-- ========================================================================= -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                    Profil &amp; Pengaturan
                </h1>
                <p class="text-[11px] md:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Identitas brand, tautan URL bio link, media sosial, dan data rekening pencairan.
                </p>
            </div>
            
            @if(isset($store) && $store->slug)
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="w-full sm:w-auto px-3.5 py-2 rounded-xl bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] hover:bg-slate-50 dark:hover:bg-[#161f33] text-slate-700 dark:text-slate-200 text-xs font-bold transition-all shadow-2xs flex items-center justify-center gap-1.5 active:scale-95">
                    <span class="material-symbols-outlined text-[17px] text-[#00838f]">
                        {{ ($store->store_mode ?? 'store') === 'profile' ? 'contact_page' : (($store->store_mode ?? 'store') === 'hybrid' ? 'layers' : 'storefront') }}
                    </span>
                    <span>Lihat Halaman Publik</span>
                    <span class="material-symbols-outlined text-[14px] opacity-60">open_in_new</span>
                </a>
            </div>
            @endif
        </div>

        <!-- ========================================================================= -->
        <!-- HORIZONTAL SWIPEABLE PILL TABS (Mobile & Desktop)                         -->
        <!-- ========================================================================= -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-3.5 px-3.5 sm:mx-0 sm:px-0 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden" style="scrollbar-width: none; -ms-overflow-style: none;">
            <button type="button" @click="tab = 'profil'" 
                    class="shrink-0 px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                    :class="tab === 'profil' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-300 active:scale-95'">
                <span class="material-symbols-outlined text-[17px]">badge</span>
                <span>Profil Utama</span>
            </button>

            <button type="button" @click="tab = 'sosmed'" 
                    class="shrink-0 px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                    :class="tab === 'sosmed' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-300 active:scale-95'">
                <span class="material-symbols-outlined text-[17px]">share</span>
                <span>Media Sosial</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="tab === 'sosmed' ? 'bg-white/20 text-white dark:bg-black/20 dark:text-slate-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'" x-text="socialItems.length"></span>
            </button>

            <button type="button" @click="tab = 'rekening'" 
                    class="shrink-0 px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                    :class="tab === 'rekening' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-300 active:scale-95'">
                <span class="material-symbols-outlined text-[17px]">credit_card</span>
                <span>Rekening Bank</span>
            </button>

            <button type="button" @click="tab = 'sistem'" 
                    class="shrink-0 px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                    :class="tab === 'sistem' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-300 active:scale-95'">
                <span class="material-symbols-outlined text-[17px]">tune</span>
                <span>Pengaturan Sistem</span>
            </button>
        </div>

        <!-- ========================================================================= -->
        <!-- MAIN FORM CARD                                                            -->
        <!-- ========================================================================= -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-2xs overflow-hidden">
            
            <form action="{{ route('tenant.store.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 md:p-8 space-y-5" onsubmit="const btn = this.querySelector('button[type=submit]'); btn.disabled = true; btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.innerHTML = '<span class=\'material-symbols-outlined text-[18px] animate-spin\'>progress_activity</span><span>Menyimpan...</span>';">
                @csrf

                <!-- Banner Informasi ke Desain Tampilan -->
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2.5 text-slate-700 dark:text-slate-200">
                        <span class="p-2 rounded-lg bg-slate-200/70 dark:bg-slate-800 text-slate-800 dark:text-white shrink-0">
                            <span class="material-symbols-outlined text-[18px] block">palette</span>
                        </span>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white text-xs">Atur Tampilan &amp; Tombol Bio Link</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Mode Halaman, warna tema, banner, dan tombol link diatur di <strong>Desain Tampilan</strong>.</p>
                        </div>
                    </div>
                    <a href="{{ route('tenant.appearance.index') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 font-bold text-[11px] whitespace-nowrap transition-all flex items-center justify-center gap-1 shrink-0 w-full sm:w-auto active:scale-95">
                        <span>Buka Desain Tampilan</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>

                <!-- ================================================================= -->
                <!-- TAB 1: PROFIL UTAMA                                               -->
                <!-- ================================================================= -->
                <div x-show="tab === 'profil'" class="space-y-5">
                    
                    <!-- Logo / Foto Profil -->
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200/80 dark:border-[#222f49]">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                            Foto Profil / Logo Brand
                        </label>
                        <div class="flex flex-col sm:flex-row items-center sm:items-center gap-4 text-center sm:text-left">
                            <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white dark:bg-[#111726] border-2 border-dashed border-slate-300 dark:border-slate-700 overflow-hidden flex items-center justify-center shrink-0">
                                @if(isset($store) && $store->logo)
                                    <img id="logo-preview" src="{{ asset('storage/' . $store->logo) }}" alt="Logo" class="w-full h-full object-cover">
                                @else
                                    <img id="logo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($store->name ?? 'Toko') }}&background=00838f&color=fff" alt="Logo" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 w-full">
                                <input type="file" name="logo" id="logo-input" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400
                                  file:mr-3 file:py-2 file:px-3.5
                                  file:rounded-xl file:border-0
                                  file:text-xs file:font-bold
                                  file:bg-teal-50 file:text-[#00838f]
                                  dark:file:bg-teal-950/60 dark:file:text-teal-300
                                  hover:file:bg-teal-100
                                  transition-all cursor-pointer
                                " onchange="previewImage(event)">
                                <p class="text-[11px] text-slate-400 mt-1.5">Disarankan rasio 1:1 (persegi). Format: JPG, PNG, WEBP. Maks 2MB.</p>
                                @error('logo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nama Profil / Toko -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Nama Profil / Toko <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $store->name ?? '') }}" required
                            class="w-full px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00838f]/20 focus:border-[#00838f] text-slate-900 dark:text-white transition-all placeholder-slate-400" placeholder="Contoh: R-Tech Studio / Gudang Source Code">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Custom URL / Slug / Username Bio Link -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Tautan URL / Username Bio Link
                            </label>
                            <span class="text-[10px] text-slate-400">Bebas ditentukan (unik)</span>
                        </div>
                        <div class="flex items-center rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] overflow-hidden focus-within:ring-2 focus-within:ring-[#00838f]/20 focus-within:border-[#00838f]">
                            <span class="px-3 py-2.5 text-xs font-semibold text-slate-400 border-r border-slate-200 dark:border-[#222f49] bg-slate-100/70 dark:bg-[#111726] select-none whitespace-nowrap">
                                {{ url('/') }}/
                            </span>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $store->slug ?? '') }}" placeholder="nama-toko"
                                class="flex-1 px-3 py-2.5 text-xs md:text-sm bg-transparent border-0 focus:outline-none text-slate-900 dark:text-white font-mono">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Gunakan huruf kecil, angka, atau tanda strip (-). Contoh: <strong class="text-[#00838f] dark:text-teal-400">r-tech</strong> sehingga alamat tokomu menjadi <span class="font-mono text-[10px]">{{ url('/') }}/r-tech</span>
                        </p>
                        @error('slug') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror

                        @if(!empty($store->slug))
                        <div x-data="{ copied: false, url: '{{ url('/' . $store->slug) }}' }" 
                             class="mt-2.5 p-3 rounded-xl bg-teal-50/70 dark:bg-teal-950/30 border border-teal-200/80 dark:border-teal-800/40 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0 text-xs text-teal-900 dark:text-teal-300">
                                <span class="material-symbols-outlined text-[17px] text-[#00838f] shrink-0">link</span>
                                <div class="truncate">
                                    <span class="text-[10px] uppercase font-bold text-[#00838f] dark:text-teal-400 mr-1">Tautan Publik:</span>
                                    <strong class="font-mono text-xs select-all text-slate-900 dark:text-white">{{ url('/' . $store->slug) }}</strong>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" 
                                        @click="navigator.clipboard.writeText(url); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all shadow-2xs flex items-center gap-1 cursor-pointer active:scale-95"
                                        :class="copied ? 'bg-emerald-600 text-white' : 'bg-[#00838f] hover:bg-[#00727d] text-white'">
                                    <span class="material-symbols-outlined text-[14px]" x-text="copied ? 'check' : 'content_copy'"></span>
                                    <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                                </button>
                                <a href="{{ route('store.show', $store->slug) }}" target="_blank"
                                   class="px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-teal-200 dark:border-teal-800 text-xs font-bold text-[#00838f] dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-1 shadow-2xs active:scale-95">
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                    <span>Buka</span>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Deskripsi / Bio Singkat -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Bio / Deskripsi Singkat
                        </label>
                        <textarea id="description" name="description" rows="3" 
                            class="w-full px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00838f]/20 focus:border-[#00838f] text-slate-900 dark:text-white transition-all leading-relaxed placeholder-slate-400" placeholder="Tulis bio profil atau deskripsi singkat toko Anda...">{{ old('description', $store->description ?? '') }}</textarea>
                        @error('description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat Fisik / Domisili -->
                    <div class="pt-4 border-t border-slate-100 dark:border-[#1d273d]">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-[#00838f]">location_on</span> Wilayah Domisili (Opsional)
                        </h3>
                        <textarea id="address" name="address" rows="2" 
                            class="w-full px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00838f]/20 focus:border-[#00838f] text-slate-900 dark:text-white transition-all placeholder-slate-400" placeholder="Kota, Provinsi, Indonesia">{{ old('address', $store->address ?? '') }}</textarea>
                        @error('address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                </div>

                <!-- ================================================================= -->
                <!-- TAB 2: MEDIA SOSIAL TOKO                                          -->
                <!-- ================================================================= -->
                <div x-show="tab === 'sosmed'" class="space-y-5" style="display: none;">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-[#00838f]">share</span> Tautan Media Sosial &amp; Kontak
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Tautan media sosial akan tampil di profil / toko untuk memudahkan pelanggan menghubungi Anda.
                        </p>
                    </div>

                    <!-- Quick Add Platform Badges -->
                    <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200/80 dark:border-[#222f49] space-y-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                            + Tambah Cepat Platform:
                        </span>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="p in platforms" :key="p.key">
                                <button type="button" 
                                        @click="addItem(p.key)"
                                        class="px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] hover:border-[#00838f] hover:text-[#00838f] dark:hover:text-teal-400 text-slate-700 dark:text-slate-300 transition-all flex items-center gap-1 active:scale-95 cursor-pointer shadow-2xs">
                                    <span class="material-symbols-outlined text-[14px]" x-text="p.icon"></span>
                                    <span x-text="p.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Repeater List -->
                    <div class="space-y-3">
                        <template x-for="(item, index) in socialItems" :key="index">
                            <div class="p-3.5 rounded-xl bg-white dark:bg-[#0e1526] border border-slate-200 dark:border-[#222f49] shadow-2xs flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                                
                                <!-- Platform Select -->
                                <div class="w-full sm:w-40 shrink-0">
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Platform</label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined absolute left-2.5 text-[16px] text-[#00838f] pointer-events-none" x-text="getIcon(item.platform)"></span>
                                        <select :name="`social_links[${index}][platform]`" 
                                                x-model="item.platform" 
                                                @change="onPlatformChange(index, $event)"
                                                class="w-full pl-8 pr-6 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-[#00838f] font-semibold cursor-pointer">
                                            <template x-for="p in platforms" :key="p.key">
                                                <option :value="p.key" x-text="p.name" :selected="p.key === item.platform"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>

                                <!-- Custom Display Name -->
                                <div class="w-full sm:w-44 shrink-0">
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nama Tampilan</label>
                                    <input type="text" 
                                           :name="`social_links[${index}][name]`" 
                                           x-model="item.name" 
                                           placeholder="Misal: IG Official"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-[#00838f]">
                                </div>

                                <!-- URL / Link Input -->
                                <div class="flex-1 min-w-0">
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Tautan URL / Username</label>
                                    <input type="text" 
                                           :name="`social_links[${index}][url]`" 
                                           x-model="item.url" 
                                           :placeholder="getPlaceholder(item.platform)"
                                           class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white focus:outline-none focus:border-[#00838f] font-mono">
                                </div>

                                <!-- Delete Button -->
                                <div class="sm:self-end sm:pb-0.5 pt-1 sm:pt-0 flex justify-end">
                                    <button type="button" 
                                            @click="removeItem(index)" 
                                            title="Hapus tautan ini"
                                            class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg transition-colors cursor-pointer flex items-center justify-center active:scale-95">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                        <span class="sm:hidden text-xs font-semibold ml-1">Hapus</span>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="socialItems.length === 0" class="p-6 text-center text-slate-400 text-xs border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                            Belum ada tautan media sosial. Klik tombol tambah di atas untuk menambahkan link Instagram, WhatsApp, TikTok, dll.
                        </div>
                    </div>

                    <div class="pt-1">
                        <button type="button" 
                                @click="addItem('custom')" 
                                class="px-3.5 py-2 rounded-xl text-xs font-bold text-[#00838f] dark:text-teal-400 bg-teal-50 dark:bg-teal-950/30 border border-teal-200 dark:border-teal-800 hover:bg-teal-100 dark:hover:bg-teal-900/40 transition-colors flex items-center gap-1.5 cursor-pointer active:scale-95">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                            <span>Tambah Tautan Lain</span>
                        </button>
                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- TAB 3: REKENING BANK                                              -->
                <!-- ================================================================= -->
                <div x-show="tab === 'rekening'" class="space-y-5" style="display: none;">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500">account_balance</span> Rekening Pencairan Saldo
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Data rekening ini digunakan saat Anda mengajukan penarikan saldo penghasilan toko.
                        </p>
                    </div>

                    <div>
                        <label for="bank_account_info" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Informasi Rekening Bank Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="bank_account_info" name="bank_account_info" rows="5" 
                            class="w-full px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00838f]/20 focus:border-[#00838f] text-slate-900 dark:text-white transition-all font-mono leading-relaxed placeholder-slate-400" placeholder="Contoh:&#10;Bank BCA&#10;No. Rekening: 4370351509&#10;Atas Nama: RANRAN RAHAYU">{{ old('bank_account_info', $store->bank_account_info ?? '') }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1.5">Pastikan nama pemilik rekening sesuai dengan identitas Anda agar proses pencairan saldo berjalan lancar.</p>
                        @error('bank_account_info') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- ================================================================= -->
                <!-- TAB 4: PENGATURAN SISTEM                                          -->
                <!-- ================================================================= -->
                <div x-show="tab === 'sistem'" class="space-y-6" style="display: none;">
                    
                    <!-- 1. Mode Tema Sistem (Dark / Light Mode) -->
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-[#00838f] text-[20px]">palette</span>
                            <h3 class="text-sm md:text-base font-bold text-slate-900 dark:text-white">Mode Tema Tampilan Sistem</h3>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pilih tema antarmuka yang nyaman untuk mata Anda saat mengelola toko.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Light Mode -->
                        <div @click="currentTheme = 'light'; localStorage.setItem('rhantech-theme', 'light'); document.documentElement.classList.remove('dark'); document.documentElement.dataset.theme = 'light'; document.documentElement.style.colorScheme = 'light';"
                             class="p-4 rounded-2xl border-2 transition-all cursor-pointer shadow-2xs relative overflow-hidden bg-white text-slate-800 active:scale-95"
                             :class="currentTheme === 'light' ? 'border-[#00838f] ring-2 ring-[#00838f]/20' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="p-2 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">light_mode</span>
                                </span>
                                <span x-show="currentTheme === 'light'" class="flex items-center gap-1 text-[#00838f] font-bold text-xs bg-teal-50 px-2.5 py-0.5 rounded-full border border-teal-200">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Aktif
                                </span>
                            </div>
                            <h4 class="font-extrabold text-sm text-slate-900">Mode Terang (Light)</h4>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">Latar belakang bersih dan kontras tajam, optimal untuk penggunaan siang hari.</p>
                        </div>

                        <!-- Dark Mode -->
                        <div @click="currentTheme = 'dark'; localStorage.setItem('rhantech-theme', 'dark'); document.documentElement.classList.add('dark'); document.documentElement.dataset.theme = 'dark'; document.documentElement.style.colorScheme = 'dark';"
                             class="p-4 rounded-2xl border-2 transition-all cursor-pointer shadow-2xs relative overflow-hidden bg-slate-900 text-white active:scale-95"
                             :class="currentTheme === 'dark' ? 'border-[#00838f] ring-2 ring-[#00838f]/20' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="p-2 rounded-xl bg-indigo-950 text-teal-400 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">dark_mode</span>
                                </span>
                                <span x-show="currentTheme === 'dark'" class="flex items-center gap-1 text-teal-300 font-bold text-xs bg-teal-950/80 px-2.5 py-0.5 rounded-full border border-teal-800">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Aktif
                                </span>
                            </div>
                            <h4 class="font-extrabold text-sm text-white">Mode Gelap (Dark)</h4>
                            <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">Latar belakang gelap elegan, nyaman dan mengurangi ketegangan mata di malam hari.</p>
                        </div>
                    </div>

                    <!-- 2. Mode Halaman Publik Toko -->
                    <div class="pt-5 border-t border-slate-100 dark:border-[#222f49]">
                        <div class="mb-3">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="material-symbols-outlined text-[#00838f] text-[20px]">devices</span>
                                <h3 class="text-sm md:text-base font-bold text-slate-900 dark:text-white">Mode Halaman Publik Toko</h3>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Atur tampilan utama yang dilihat pembeli saat mengunjungi link toko Anda.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @php
                                $selectedStoreMode = old('store_mode', $store->store_mode ?? 'store');
                            @endphp
                            <label class="p-3.5 rounded-xl border-2 cursor-pointer transition-all flex flex-col justify-between bg-white dark:bg-[#0c1220] hover:border-slate-300 dark:hover:border-slate-600 has-[:checked]:border-[#00838f] has-[:checked]:bg-teal-50/40 dark:has-[:checked]:bg-teal-950/30 shadow-2xs active:scale-95">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-1.5 rounded-lg bg-teal-500/10 text-[#00838f] dark:text-teal-400 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">storefront</span>
                                    </span>
                                    <input type="radio" name="store_mode" value="store" {{ $selectedStoreMode === 'store' ? 'checked' : '' }} class="text-[#00838f] focus:ring-[#00838f] w-4 h-4">
                                </div>
                                <div>
                                    <span class="font-bold text-xs text-slate-900 dark:text-white block mb-0.5">Toko Digital</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug block">Katalog e-commerce produk digital lengkap dengan filter & kategori.</span>
                                </div>
                            </label>

                            <label class="p-3.5 rounded-xl border-2 cursor-pointer transition-all flex flex-col justify-between bg-white dark:bg-[#0c1220] hover:border-slate-300 dark:hover:border-slate-600 has-[:checked]:border-[#00838f] has-[:checked]:bg-teal-50/40 dark:has-[:checked]:bg-teal-950/30 shadow-2xs active:scale-95">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">contact_page</span>
                                    </span>
                                    <input type="radio" name="store_mode" value="profile" {{ $selectedStoreMode === 'profile' ? 'checked' : '' }} class="text-[#00838f] focus:ring-[#00838f] w-4 h-4">
                                </div>
                                <div>
                                    <span class="font-bold text-xs text-slate-900 dark:text-white block mb-0.5">Bio Link</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug block">Tampilan tombol tautan media sosial & produk gaya Linktree/Lynk.id.</span>
                                </div>
                            </label>

                            <label class="p-3.5 rounded-xl border-2 cursor-pointer transition-all flex flex-col justify-between bg-white dark:bg-[#0c1220] hover:border-slate-300 dark:hover:border-slate-600 has-[:checked]:border-[#00838f] has-[:checked]:bg-teal-50/40 dark:has-[:checked]:bg-teal-950/30 shadow-2xs active:scale-95">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-1.5 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">layers</span>
                                    </span>
                                    <input type="radio" name="store_mode" value="hybrid" {{ $selectedStoreMode === 'hybrid' ? 'checked' : '' }} class="text-[#00838f] focus:ring-[#00838f] w-4 h-4">
                                </div>
                                <div>
                                    <span class="font-bold text-xs text-slate-900 dark:text-white block mb-0.5">Hybrid</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug block">Kombinasi fleksibel tombol tautan bio link dan etalase katalog produk.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Persetujuan Kontrak Elektronik & Regulasi RI (Khusus Buka Toko Baru) -->
                @if(!isset($store) || empty($store->id))
                <div class="p-4 rounded-xl bg-teal-50/60 dark:bg-teal-950/20 border-2 border-teal-200/80 dark:border-teal-800/50 space-y-3">
                    <div class="flex items-start gap-2.5">
                        <span class="p-1.5 bg-[#00838f] text-white rounded-lg shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">gavel</span>
                        </span>
                        <div>
                            <h4 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white">Persetujuan Kontrak Elektronik & Kepatuhan Hukum RI</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                                Berdasarkan UU ITE No. 1/2024, PP No. 80/2019 (PMSE), dan UU Hak Cipta No. 28/2014, setiap penjual wajib menyatakan persetujuan secara sah sebelum mengaktifkan toko.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" name="agree_terms" value="1" required
                                   class="mt-1 w-4 h-4 text-[#00838f] border-slate-300 rounded focus:ring-[#00838f] shrink-0 cursor-pointer">
                            <span class="text-xs text-slate-700 dark:text-slate-300 leading-snug">
                                Saya menyetujui seluruh 
                                <a href="{{ route('legal.terms') }}" target="_blank" class="text-[#00838f] font-bold hover:underline">Syarat & Ketentuan</a> serta 
                                <a href="{{ route('legal.copyright') }}" target="_blank" class="text-[#00838f] font-bold hover:underline">Kebijakan Hak Cipta (HAKI)</a>.
                            </span>
                        </label>
                        @error('agree_terms') <span class="text-xs text-rose-500 font-semibold block">{{ $message }}</span> @enderror
                    </div>
                </div>
                @else
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[17px] text-emerald-500 shrink-0">verified_user</span>
                        <span class="text-slate-600 dark:text-slate-400 text-[11px]">
                            Kontrak Elektronik Toko: <strong class="text-slate-800 dark:text-slate-200">{{ $store->terms_accepted_at ? $store->terms_accepted_at->format('d M Y, H:i') : 'Terverifikasi' }}</strong>
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('legal.terms') }}" target="_blank" class="text-[#00838f] dark:text-teal-400 hover:underline font-semibold text-[11px]">Syarat &amp; Ketentuan ↗</a>
                    </div>
                </div>
                @endif

                <!-- Submit Action Buttons (Mobile & Desktop Responsive) -->
                <div class="pt-4 border-t border-slate-100 dark:border-[#1d273d] flex flex-col sm:flex-row items-center justify-end gap-2.5">
                    <a href="{{ route('tenant.dashboard') }}" class="w-full sm:w-auto text-center px-4 py-2.5 text-xs font-semibold border border-slate-200 dark:border-[#222f49] text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-[#161f33] transition-colors active:scale-95">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-xs md:text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 rounded-xl active:scale-95 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[17px]">check_circle</span>
                        <span>{{ isset($store) && $store->id ? 'Simpan Profil & Pengaturan' : 'Buka Toko Sekarang' }}</span>
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
</script>
@endsection
