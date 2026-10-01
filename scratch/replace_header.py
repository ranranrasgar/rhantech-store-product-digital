import re

with open('resources/views/tenant/dashboard.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

start_marker = '<div class="pb-6 border-b border-zinc-100 dark:border-zinc-800">'
end_marker = '<!-- 6 Essential Metrics Cards (Classic Unified Row) -->'

desktop_marker = "<!-- DESKTOP VIEW"
idx_desktop = content.find(desktop_marker)

if idx_desktop != -1:
    idx_start = content.find(start_marker, idx_desktop)
    idx_end = content.find(end_marker, idx_start)

    if idx_start != -1 and idx_end != -1:
        replacement = """<div class="pb-6 border-b border-zinc-100 dark:border-zinc-800 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Left: Store Info -->
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl p-0.5 bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0">
                        @if($store && $store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full bg-[#00838f] rounded-xl flex items-center justify-center font-black text-xl text-white">
                                {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-900 text-[9px] font-semibold text-zinc-700 dark:text-zinc-300 mb-1 border border-zinc-200 dark:border-zinc-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Merchant Partner
                        </div>
                        <h1 class="text-xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                            {{ $store->name ?? 'Toko Saya' }}
                        </h1>
                        <a href="{{ route('store.show', $store->slug ?? 'toko-'.$store->id) }}" target="_blank" class="text-xs text-orange-600 dark:text-orange-400 hover:underline flex items-center gap-1 mt-0.5 font-semibold">
                            Lihat Toko Publik <span class="material-symbols-outlined text-[12px]">open_in_new</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Compact Share & Action -->
                @php
                    $storeSlug = $store->slug ?: 'toko-' . $store->id;
                    $storeDirectUrl = url('/' . $storeSlug);
                @endphp
                <div class="flex flex-wrap items-center gap-3" x-data="{
                    storeUrl: '{{ $storeDirectUrl }}',
                    copied: false,
                    copyLink() {
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(this.storeUrl).then(() => {
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                            });
                        }
                    }
                }">
                    <!-- Compact Copy Link -->
                    <div class="flex items-center bg-slate-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 rounded-xl p-1 shrink-0">
                        <span class="material-symbols-outlined text-[16px] text-zinc-400 ml-2">link</span>
                        <input type="text" readonly :value="storeUrl" class="bg-transparent border-none px-2 py-1 text-[11px] font-mono text-zinc-600 dark:text-zinc-400 focus:outline-none w-32 sm:w-48 truncate cursor-pointer" @click="copyLink()">
                        <button type="button" @click="copyLink()" class="px-3 py-1.5 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-[10px] font-bold rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-700 transition-colors shadow-xs" :class="copied ? 'text-emerald-600' : 'text-zinc-700 dark:text-zinc-300'">
                            <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                        </button>
                    </div>

                    <a href="{{ route('tenant.products.create') }}" class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs flex items-center gap-1.5 transition-colors shrink-0 shadow-xs">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Produk
                    </a>
                </div>
            </div>

        """
        
        new_content = content[:idx_start] + replacement + content[idx_end:]
        
        # We also need to remove the CSS for marquee which is outside the replaced block
        # Let's remove lines with <style> @keyframes marquee-scroll-horizontal ... </style>
        css_start = '<style>\n            @keyframes marquee-scroll-horizontal'
        css_end = '</style>'
        if css_start in new_content:
            idx_css_start = new_content.find(css_start)
            idx_css_end = new_content.find(css_end, idx_css_start)
            if idx_css_end != -1:
                new_content = new_content[:idx_css_start] + new_content[idx_css_end + len(css_end):]
        
        with open('resources/views/tenant/dashboard.blade.php', 'w', encoding='utf-8') as f:
            f.write(new_content)
        print("Replaced!")
    else:
        print("Could not find start or end marker.")
else:
    print("Could not find desktop marker.")
