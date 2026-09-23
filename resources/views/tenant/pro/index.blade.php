@extends('layouts.tenant')

@section('title', 'Layanan Toko PRO')

@section('content')
<div class="p-4 md:p-6 lg:p-8 max-w-6xl mx-auto w-full space-y-8">
    
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-semibold flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-2xl">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-semibold flex items-center gap-3">
            <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-2xl">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($store->isPro())
        <!-- Active PRO Banner (Clean Flat Design) -->
        <div class="bg-amber-500 rounded-3xl p-6 md:p-10 text-slate-950 shadow-none relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-950 text-amber-400 rounded-full text-xs font-black uppercase tracking-wider mb-4 shadow-none">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        Status Toko PRO Aktif
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black mb-2 flex items-center gap-2">
                        Toko Anda Resmi <span class="underline decoration-slate-950">PRO</span>
                        <span class="material-symbols-outlined text-[32px]">workspace_premium</span>
                    </h1>
                    <p class="text-slate-950/80 text-sm md:text-base font-medium max-w-xl">
                        Selamat! Toko Anda menikmati prioritas penarikan dengan fee platform 1%, WA Broadcast tanpa batas, dan modul portofolio proyek.
                    </p>
                    <div class="flex flex-wrap items-center gap-2.5 mt-4">
                        <a href="{{ route('help.show', 'panduan-lengkap-layanan-toko-pro-keuntungan-dan-fitur-eksklusif') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-950/15 hover:bg-slate-950/25 text-slate-950 font-bold text-xs border border-slate-950/20 transition shadow-none">
                            <span class="material-symbols-outlined text-[16px]">menu_book</span>
                            <span>Buku Panduan Layanan PRO</span>
                        </a>
                        <a href="{{ route('help.show', 'panduan-cara-upgrade-toko-pro-pembayaran-qris-dan-potong-saldo') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-950/15 hover:bg-slate-950/25 text-slate-950 font-bold text-xs border border-slate-950/20 transition shadow-none">
                            <span class="material-symbols-outlined text-[16px]">qr_code_2</span>
                            <span>Panduan Pembayaran QRIS</span>
                        </a>
                    </div>
                </div>

                <div class="bg-slate-950/10 backdrop-blur-sm border border-slate-950/20 p-5 rounded-2xl text-slate-950 min-w-[240px]">
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-950/70">Masa Aktif Layanan</div>
                    <div class="text-xl font-black mt-1">
                        {{ $store->pro_expires_at ? $store->pro_expires_at->format('d M Y') : 'Lifetime (Selamanya)' }}
                    </div>
                    <div class="text-xs text-slate-950/80 mt-1">
                        Paket: <span class="font-bold capitalize">{{ $store->pro_plan ?? 'Aktif' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3 Kartu Keuntungan Aktif -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white dark:bg-[#000000] rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-none flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">percent</span>
                </div>
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-zinc-100 mb-1">Fee Penarikan 1%</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Potongan penarikan saldo Anda saat ini hanya 1% (jauh lebih hemat dibanding reguler 2,5%).</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#000000] rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-none flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">send_to_mobile</span>
                </div>
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-zinc-100 mb-1">WA Broadcast Aktif</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Kirim pesan promosi & voucher langsung ke pelanggan lewat WhatsApp dengan 1 klik.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#000000] rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 shadow-none flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">work</span>
                </div>
                <div>
                    <h3 class="font-bold text-zinc-900 dark:text-zinc-100 mb-1">Modul Portofolio</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">Pamerkan proyek karya dan rekam jejak digital terbaik Anda di halaman toko publik.</p>
                </div>
            </div>
        </div>

    @else
        <!-- Header Non-PRO (Clean Flat Style) -->
        <div class="text-center max-w-2xl mx-auto space-y-3 pt-4">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-500 text-slate-950 shadow-none">
                <span class="material-symbols-outlined text-3xl font-bold">workspace_premium</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">
                Upgrade ke Toko <span class="text-amber-500">PRO</span>
            </h1>
            <p class="text-zinc-500 dark:text-zinc-400 text-sm md:text-base">
                Buka seluruh potensi bisnis Anda. Nikmati potongan fee payout hanya 1%, fitur WA Broadcast, modul portofolio, dan verifikasi lencana PRO.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-2.5 pt-2">
                <a href="{{ route('help.show', 'panduan-lengkap-layanan-toko-pro-keuntungan-dan-fitur-eksklusif') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 font-bold text-xs hover:bg-amber-100 dark:hover:bg-amber-900/50 transition shadow-none">
                    <span class="material-symbols-outlined text-[16px]">menu_book</span>
                    <span>Buku Panduan Layanan PRO</span>
                </a>
                <a href="{{ route('help.show', 'panduan-cara-upgrade-toko-pro-pembayaran-qris-dan-potong-saldo') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-800 font-semibold text-xs hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                    <span class="material-symbols-outlined text-[16px]">qr_code_scanner</span>
                    <span>Panduan Pembayaran QRIS</span>
                </a>
            </div>
        </div>

        <!-- Formulir & Paket Pilihan Upgrade PRO -->
        <div class="bg-white dark:bg-[#000000] rounded-3xl border border-zinc-200 dark:border-zinc-800 p-6 md:p-10 shadow-none space-y-8">
            <form action="{{ route('tenant.pro.upgrade') }}" method="POST" id="proUpgradeForm" class="space-y-8">
                @csrf

                <!-- Langkah 1: Pilih Paket -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-orange-500 text-white text-[11px] font-black flex items-center justify-center">1</span>
                            Pilih Paket Berlangganan
                        </label>
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">Tersedia {{ count($plans) }} Pilihan Fleksibel</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-{{ min(max(count($plans), 1), 3) }} gap-4">
                        @php
                            $defaultPlan = $plans->where('is_popular', true)->first() ?? $plans->first();
                        @endphp
                        @foreach($plans as $plan)
                            @php
                                $isSelected = $defaultPlan && $defaultPlan->slug === $plan->slug;
                                $isPopular = (bool)($plan->is_popular ?? false);
                            @endphp
                            <div class="flex flex-col gap-0">
                                {{-- Kartu Paket --}}
                                <label class="relative border-2 {{ $isSelected ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-zinc-200 dark:border-zinc-800' }} {{ $isPopular ? 'rounded-t-2xl rounded-b-none' : 'rounded-2xl' }} p-5 cursor-pointer hover:border-amber-500 dark:hover:border-amber-500 transition-all flex flex-col justify-between plan-card bg-white dark:bg-[#000000]" id="card_{{ $plan->slug }}">

                                    {{-- Badge promo pojok kanan atas --}}
                                    @if($plan->badge)
                                        <div class="absolute -top-3 right-3 bg-amber-500 text-slate-950 text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-none">
                                            {{ $plan->badge }}
                                        </div>
                                    @endif

                                    <input type="radio" name="plan" value="{{ $plan->slug }}" class="sr-only" {{ $isSelected ? 'checked' : '' }} onchange="updatePlan('{{ $plan->slug }}', {{ (int)$plan->price }})">

                                    <div>
                                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                                            {{ $isPopular ? 'Paling Populer' : 'Paket Pilihan' }}
                                        </div>
                                        <h3 class="text-lg font-black text-zinc-900 dark:text-zinc-100">{{ $plan->name }}</h3>
                                        <div class="mt-3 flex items-baseline gap-1">
                                            <span class="text-2xl font-black text-zinc-900 dark:text-zinc-100">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                            <span class="text-xs text-slate-400">{{ $plan->duration_label ?? ($plan->duration_days ? '/ ' . $plan->duration_days . ' hari' : '/ selamanya') }}</span>
                                        </div>
                                        @if($plan->description)
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2">{{ $plan->description }}</p>
                                        @endif
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-[#1d273d] space-y-1.5">
                                        @forelse($plan->features_list as $feature)
                                            <div class="flex items-center text-xs text-zinc-600 dark:text-zinc-300 font-medium">
                                                <span class="material-symbols-outlined text-[16px] text-emerald-500 mr-1.5 shrink-0">check_circle</span>
                                                <span>{{ $feature }}</span>
                                            </div>
                                        @empty
                                            <div class="flex items-center text-xs text-zinc-600 dark:text-zinc-300 font-medium">
                                                <span class="material-symbols-outlined text-[16px] text-emerald-500 mr-1.5 shrink-0">check_circle</span>
                                                <span>Fee payout 1% & Fitur PRO Aktif</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </label>

                                {{-- Strip DIREKOMENDASIKAN di bawah kartu --}}
                                @if($isPopular)
                                    <div class="flex items-center justify-center gap-1.5 bg-amber-500 text-slate-950 text-[11px] font-black uppercase tracking-wider py-1.5 px-3 rounded-b-2xl shadow-none">
                                        <span class="material-symbols-outlined text-[14px]">star</span>
                                        <span>Direkomendasikan</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Langkah 2: Pilih Metode Pembayaran -->
                <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-[#1d273d]">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-orange-500 text-white text-[11px] font-black flex items-center justify-center">2</span>
                            Pilih Metode Pembayaran
                        </label>
                        <a href="{{ route('help.show', 'panduan-cara-upgrade-toko-pro-pembayaran-qris-dan-potong-saldo') }}" target="_blank" class="text-xs text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1 font-bold">
                            <span class="material-symbols-outlined text-[15px]">help</span>
                            Panduan Cara Bayar
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Pilihan 1: QRIS / Midtrans Instan -->
                        <label class="relative border-2 border-amber-500 rounded-2xl p-5 cursor-pointer bg-slate-50/50 dark:bg-[#0c1220]/50 hover:border-amber-500 transition-all flex items-start gap-4 payment-source-card ring-1 ring-amber-500/30" id="card_src_qris">
                            <input type="radio" name="payment_source" value="qris" class="mt-1" checked onchange="updatePaymentSource('qris')">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-sm text-zinc-900 dark:text-zinc-100">QRIS & E-Wallet (Midtrans Instan)</span>
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400 text-[10px] font-bold">Rekomendasi</span>
                                </div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                                    Scan kode QRIS instan via GoPay, ShopeePay, OVO, Dana, BCA, Mandiri, BRI, BNI. Langsung aktif dalam 5 detik!
                                </p>
                                <div class="mt-2 text-[11px] text-slate-400 font-mono">
                                    Format Invoice Callback: <strong class="text-zinc-700 dark:text-zinc-300">RHN-PRO-...</strong>
                                </div>
                            </div>
                        </label>

                        <!-- Pilihan 2: Potong Saldo Penjualan Toko -->
                        <label class="relative border-2 border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 cursor-pointer bg-slate-50/50 dark:bg-[#0c1220]/50 hover:border-amber-500 transition-all flex items-start gap-4 payment-source-card" id="card_src_balance">
                            <input type="radio" name="payment_source" value="balance" class="mt-1" onchange="updatePaymentSource('balance')">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-sm text-zinc-900 dark:text-zinc-100">Potong Saldo Penjualan Toko</span>
                                    <span class="text-xs font-bold text-zinc-500 dark:text-zinc-400">Saldo: Rp {{ number_format($store->balance, 0, ',', '.') }}</span>
                                </div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 leading-relaxed">
                                    Gunakan akumulasi keuntungan hasil penjualan toko Anda tanpa perlu transfer uang keluar.
                                </p>
                                @if($store->balance < 49000)
                                    <div class="mt-2 text-[11px] text-rose-500 font-medium">
                                        * Saldo toko saat ini belum mencukupi untuk paket ini.
                                    </div>
                                @endif
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Ringkasan & Tombol Bayar -->
                <div class="bg-slate-50 dark:bg-[#0c1220] rounded-2xl p-6 border border-zinc-200 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div>
                        <div class="text-xs text-zinc-500 dark:text-zinc-400 font-bold uppercase tracking-wider">Total Tagihan Upgrade:</div>
                        <div class="text-2xl md:text-3xl font-black text-zinc-900 dark:text-zinc-100 mt-0.5" id="displayTotal">
                            Rp 399.000
                        </div>
                        <div class="text-[11px] text-slate-400 mt-0.5" id="displayPlanLabel">
                            Paket Tahunan (1 Tahun) &bull; Pembayaran Otomatis QRIS
                        </div>
                    </div>

                    <button type="submit" id="submitUpgradeBtn" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-sm shadow-none shadow-amber-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
                        <span id="btnText">Bayar Sekarang via QRIS</span>
                    </button>
                </div>

            </form>
        </div>
    @endif

    <!-- Riwayat Transaksi Langganan PRO -->
    <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden shadow-none">
        <div class="p-5 md:p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Riwayat Upgrade & Transaksi PRO</h2>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Daftar transaksi langganan dan status aktivasi akun PRO toko Anda.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="p-4 md:px-6">No. Invoice</th>
                        <th class="p-4 md:px-6">Paket</th>
                        <th class="p-4 md:px-6">Nominal</th>
                        <th class="p-4 md:px-6">Metode</th>
                        <th class="p-4 md:px-6">Status</th>
                        <th class="p-4 md:px-6">Tanggal</th>
                        <th class="p-4 md:px-6 text-right pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                    @forelse($subscriptions as $sub)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-[#151e30]/40 transition">
                        <td class="p-4 md:px-6 font-mono font-bold text-zinc-900 dark:text-zinc-100">
                            {{ $sub->reference_no }}
                        </td>
                        <td class="p-4 md:px-6 capitalize font-semibold text-zinc-700 dark:text-zinc-300">
                            {{ $sub->plan }}
                        </td>
                        <td class="p-4 md:px-6 font-bold text-zinc-900 dark:text-zinc-100">
                            Rp {{ number_format($sub->amount, 0, ',', '.') }}
                        </td>
                        <td class="p-4 md:px-6 text-zinc-500 dark:text-zinc-400 text-xs">
                            {{ $sub->payment_method ?? 'Midtrans QRIS' }}
                        </td>
                        <td class="p-4 md:px-6">
                            @if($sub->payment_status === 'paid')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas / Aktif
                                </span>
                            @elseif($sub->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Bayar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/40">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                </span>
                            @endif
                        </td>
                        <td class="p-4 md:px-6 text-zinc-500 dark:text-zinc-400 text-xs">
                            {{ $sub->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="p-4 md:px-6 text-right pr-6">
                            @if($sub->payment_status === 'pending' && $sub->snap_token)
                                <a href="{{ route('tenant.pro.payment', $sub->reference_no) }}" class="px-3 py-1.5 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold transition inline-flex items-center gap-1 shadow-none">
                                    <span class="material-symbols-outlined text-[15px]">qr_code_2</span> Bayar
                                </a>
                            @else
                                <span class="text-slate-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400 text-xs">
                            Belum ada riwayat transaksi upgrade PRO.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscriptions->hasPages())
            <div class="p-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-center">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    const plansData = {!! json_encode($plansData ?? []) !!};

    let currentPlan = "{{ $defaultPlan->slug ?? 'yearly' }}";
    let currentAmount = {{ (int)($defaultPlan->price ?? 399000) }};
    let currentSource = 'qris';

    function updatePlan(plan, amount) {
        currentPlan = plan;
        currentAmount = amount;

        // Update card visual
        Object.keys(plansData).forEach(p => {
            const card = document.getElementById('card_' + p);
            if (card) {
                if (p === plan) {
                    card.classList.add('border-amber-500', 'ring-2', 'ring-amber-500/20', 'bg-amber-50/20', 'dark:bg-amber-950/10');
                    card.classList.remove('border-slate-200', 'dark:border-zinc-800', 'border-amber-400/80', 'dark:border-amber-600/60');
                } else {
                    card.classList.remove('border-amber-500', 'ring-2', 'ring-amber-500/20', 'bg-amber-50/20', 'dark:bg-amber-950/10');
                    if (plansData[p] && plansData[p].is_popular) {
                        card.classList.add('border-amber-400/80', 'dark:border-amber-600/60');
                        card.classList.remove('border-slate-200', 'dark:border-zinc-800');
                    } else {
                        card.classList.add('border-slate-200', 'dark:border-zinc-800');
                        card.classList.remove('border-amber-400/80', 'dark:border-amber-600/60');
                    }
                }
            }
        });

        renderDisplay();
    }

    function updatePaymentSource(source) {
        currentSource = source;

        const cardQris = document.getElementById('card_src_qris');
        const cardBal = document.getElementById('card_src_balance');

        if (source === 'qris') {
            cardQris.classList.add('border-amber-500', 'ring-1', 'ring-amber-500/30');
            cardQris.classList.remove('border-slate-200', 'dark:border-zinc-800');
            cardBal.classList.remove('border-amber-500', 'ring-1', 'ring-amber-500/30');
            cardBal.classList.add('border-slate-200', 'dark:border-zinc-800');
            document.getElementById('btnText').innerText = 'Bayar Sekarang via QRIS';
        } else {
            cardBal.classList.add('border-amber-500', 'ring-1', 'ring-amber-500/30');
            cardBal.classList.remove('border-slate-200', 'dark:border-zinc-800');
            cardQris.classList.remove('border-amber-500', 'ring-1', 'ring-amber-500/30');
            cardQris.classList.add('border-slate-200', 'dark:border-zinc-800');
            document.getElementById('btnText').innerText = 'Upgrade via Saldo Toko';
        }

        renderDisplay();
    }

    function renderDisplay() {
        document.getElementById('displayTotal').innerText = 'Rp ' + currentAmount.toLocaleString('id-ID');
        const planInfo = plansData[currentPlan] ? plansData[currentPlan].label : currentPlan;
        const sourceLabel = currentSource === 'qris' ? 'Pembayaran QRIS Midtrans Instan' : 'Pembayaran Saldo Penjualan';
        document.getElementById('displayPlanLabel').innerText = planInfo + ' • ' + sourceLabel;
    }

    // Inisialisasi tampilan awal
    renderDisplay();
</script>
@endsection
