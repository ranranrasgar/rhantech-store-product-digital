<!-- Modal Upgrade Toko PRO -->
<div x-show="isProModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div x-show="isProModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-md" @click="closeProModal()"></div>
    
    <!-- Modal Content -->
    <div x-show="isProModalOpen" x-transition.scale.95 class="bg-white dark:bg-[#0e1626] border border-amber-400/40 rounded-3xl shadow-2xl w-full max-w-xl relative z-10 overflow-hidden flex flex-col max-h-[92vh]">
        <!-- Golden Decorative Glow Header -->
        <div class="relative overflow-hidden p-6 text-white pb-7" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #0f172a 100%);">
            <div class="absolute -right-10 -top-10 w-44 h-44 rounded-full bg-amber-400/20 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-44 h-44 rounded-full bg-indigo-500/20 blur-2xl pointer-events-none"></div>
            
            <div class="relative z-10 flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg border border-amber-300/40 shrink-0" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #d97706 100%);">
                        <span class="material-symbols-outlined text-[26px] text-slate-950 font-black">workspace_premium</span>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-400/20 border border-amber-300/40 text-amber-300 mb-1">
                            <span>★ FITUR EKSKLUSIF</span>
                        </div>
                        <h3 class="text-xl font-black text-white drop-shadow-sm">Upgrade ke Toko PRO</h3>
                    </div>
                </div>
                <button type="button" @click="closeProModal()" class="text-slate-400 hover:text-white p-1 rounded-lg transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>
            <p class="mt-3 text-xs text-slate-300 leading-relaxed">
                Buka potensi maksimal bisnis digital Anda dengan status <strong class="text-amber-300 font-bold">Toko PRO Resmi</strong>. Dapatkan berbagai keistimewaan dan modul premium untuk melipatgandakan omset Anda.
            </p>
        </div>

        <!-- Body: 4 Keuntungan Utama -->
        <div class="p-6 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">
                Keuntungan Eksklusif Akun Toko PRO:
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- 1. Fee Penarikan Hanya 1% -->
                <div class="p-3.5 rounded-2xl bg-amber-500/5 dark:bg-amber-400/5 border border-amber-500/20 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">percent</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Fee Penarikan Cuma 1%</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Potongan dana terendah (reguler 2.5%). Margin keuntungan penjualan produk jadi maksimal.</p>
                    </div>
                </div>

                <!-- 2. Banner Warna Gradien & Tampilan Eksklusif -->
                <div class="p-3.5 rounded-2xl bg-indigo-500/5 dark:bg-indigo-400/5 border border-indigo-500/20 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">palette</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Banner Gradien Bebas</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Bebas pilih tema banner warna gradien modern estetis & kombinasi warna tanpa batas.</p>
                    </div>
                </div>

                <!-- 3. Modul WhatsApp Broadcast -->
                <div class="p-3.5 rounded-2xl bg-emerald-500/5 dark:bg-emerald-400/5 border border-emerald-500/20 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">cell_tower</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Modul WhatsApp Blast</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Kirim pesan promo massal ke kontak pembeli dan follower toko Anda via WhatsApp.</p>
                    </div>
                </div>

                <!-- 4. Modul Project & Portofolio Toko -->
                <div class="p-3.5 rounded-2xl bg-sky-500/5 dark:bg-sky-400/5 border border-sky-500/20 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">folder_special</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">Modul Portofolio Proyek</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-snug">Pajang portofolio & proyek klien Anda langsung di profil toko untuk meyakinkan pembeli.</p>
                    </div>
                </div>
            </div>

            <!-- 5. Avatar Glowing Badge VIP -->
            <div class="p-3.5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-yellow-400/10 to-transparent border border-amber-400/30 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full border-2 border-amber-400 p-0.5 flex items-center justify-center bg-slate-900 shadow-md">
                            <span class="text-xs font-black text-amber-300">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</span>
                        </div>
                        <span class="absolute -bottom-1 -right-1 bg-amber-400 text-slate-950 rounded-full p-0.5 text-[9px] font-black leading-none shadow">★</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">Lencana Avatar PRO Emas</span>
                            <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-gradient-to-r from-amber-400 to-yellow-300 text-slate-950">PRO VERIFIED</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Border avatar glowing emas di halaman profil toko publik & dashboard.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Action -->
        <div class="px-6 py-4 border-t border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#121c30] flex items-center justify-between gap-3 shrink-0">
            <div>
                <span class="block text-[10px] text-slate-400 font-medium">Mulai dari</span>
                <span class="text-sm font-black text-slate-900 dark:text-white">Rp 49.000 <span class="text-[10px] font-normal text-slate-400">/ bulan</span></span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="closeProModal()" class="px-3.5 py-2 text-xs font-medium text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 cursor-pointer">
                    Nanti Saja
                </button>
                <a href="{{ route('tenant.pro.index') }}" class="px-4 py-2 rounded-xl text-xs font-black text-slate-950 shadow-md hover:scale-105 transition-all flex items-center gap-1.5 cursor-pointer" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #d97706 100%);">
                    <span class="material-symbols-outlined text-[16px]">bolt</span>
                    <span>Upgrade ke Toko PRO</span>
                </a>
            </div>
        </div>
    </div>
</div>
