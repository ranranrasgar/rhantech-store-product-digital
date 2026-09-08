@extends('layouts.public')
@section('title', 'Tentang Kami - ' . ($company->company_name ?? 'Rhantech'))

@section('content')
<div class="py-12 md:py-20">
    <div class="max-w-container-max mx-auto px-4 md:px-8">
        
        <!-- Hero Section About -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary/10 via-surface-container to-surface border border-outline-variant/30 p-8 md:p-14 mb-16 shadow-xs">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary font-semibold text-xs mb-6 uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    Tentang Kami &amp; Profil
                </div>
                
                <h1 class="text-3xl md:text-5xl font-black text-on-background dark:text-white tracking-tight leading-tight mb-6">
                    {{ $company->company_name ?? 'Rhantech Digital Solutions' }}
                </h1>
                
                @if(!empty($company->tagline))
                <p class="text-lg md:text-xl font-medium text-secondary mb-6">
                    {{ $company->tagline }}
                </p>
                @endif

                <p class="text-base md:text-lg text-on-surface-variant leading-relaxed">
                    {{ $company->short_description ?? 'Membangun ekosistem dan platform digital masa depan yang memberdayakan kreator, software engineer, dan pelaku bisnis di seluruh Indonesia.' }}
                </p>
            </div>
        </div>

        <!-- Main Story & Stats Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 mb-20 items-start">
            <!-- Left: Description / Story (Managed by Admin) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-surface rounded-2xl border border-outline-variant/40 p-8 md:p-10 shadow-xs">
                    <h2 class="text-2xl font-bold text-on-background dark:text-white mb-6 flex items-center gap-3">
                        <span class="p-2.5 bg-primary/10 rounded-xl text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined text-[24px]">corporate_fare</span>
                        </span>
                        Sekilas Perusahaan
                    </h2>
                    
                    <div class="prose dark:prose-invert max-w-none text-on-surface-variant leading-relaxed whitespace-pre-line text-base">
                        @if(!empty($company->description))
                            {{ $company->description }}
                        @else
                            Rhantech adalah ekosistem dan wadah teknologi modern yang berfokus pada penyediaan solusi digital, pengembangan perangkat lunak siap pakai, aplikasi web &amp; mobile, serta marketplace produk digital terlengkap.

                            Kami berkomitmen membantu bisnis dan organisasi mengakselerasi transformasi digital mereka melalui inovasi berbasis standar teknologi terkini, arsitektur yang tangguh, serta antarmuka yang intuitif dan berdaya guna tinggi.
                        @endif
                    </div>
                </div>

                <!-- Vision & Mission Grid -->
                @if(!empty($company->vision) || !empty($company->mission))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(!empty($company->vision))
                    <div class="bg-surface rounded-2xl border border-outline-variant/40 p-6 shadow-xs">
                        <div class="flex items-center gap-2.5 mb-3 text-primary">
                            <span class="material-symbols-outlined text-[22px]">visibility</span>
                            <h3 class="font-bold text-base text-on-surface">Visi Kami</h3>
                        </div>
                        <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">
                            {{ $company->vision }}
                        </p>
                    </div>
                    @endif

                    @if(!empty($company->mission))
                    <div class="bg-surface rounded-2xl border border-outline-variant/40 p-6 shadow-xs">
                        <div class="flex items-center gap-2.5 mb-3 text-secondary">
                            <span class="material-symbols-outlined text-[22px]">flag</span>
                            <h3 class="font-bold text-base text-on-surface">Misi Kami</h3>
                        </div>
                        <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">
                            {{ $company->mission }}
                        </p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Right: Highlight Metrics & Key Facts -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Highlight Card -->
                <div class="bg-surface-container rounded-2xl border border-outline-variant/40 p-8 shadow-xs">
                    <h3 class="text-lg font-bold text-on-background dark:text-white mb-6">Pencapaian &amp; Ekosistem</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-surface rounded-xl border border-outline-variant/30 text-center">
                            <div class="text-3xl font-black text-primary mb-1">
                                {{ $company->founded_year ? (is_numeric($company->founded_year) ? (date('Y') - (int)$company->founded_year) . '+' : $company->founded_year) : '6+' }}
                            </div>
                            <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">
                                {{ is_numeric($company->founded_year ?? '') ? 'Tahun Pengalaman' : 'Pengalaman' }}
                            </div>
                        </div>

                        <div class="p-4 bg-surface rounded-xl border border-outline-variant/30 text-center">
                            <div class="text-3xl font-black text-secondary mb-1">
                                {{ $totalProducts > 0 ? $totalProducts . '+' : '50+' }}
                            </div>
                            <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">
                                Produk Digital
                            </div>
                        </div>

                        <div class="p-4 bg-surface rounded-xl border border-outline-variant/30 text-center">
                            <div class="text-3xl font-black text-cyan-600 dark:text-cyan-400 mb-1">
                                {{ $totalProjects > 0 ? $totalProjects . '+' : '100+' }}
                            </div>
                            <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">
                                Proyek Selesai
                            </div>
                        </div>

                        <div class="p-4 bg-surface rounded-xl border border-outline-variant/30 text-center">
                            <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mb-1">
                                {{ $totalStores > 0 ? $totalStores . '+' : '10+' }}
                            </div>
                            <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">
                                Mitra Toko / Tenant
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Office Info Card -->
                <div class="bg-surface rounded-2xl border border-outline-variant/40 p-8 shadow-xs space-y-4">
                    <h3 class="text-lg font-bold text-on-background dark:text-white mb-2">Informasi Kontak</h3>
                    
                    @if(!empty($company->email))
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-[18px]">email</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs text-on-surface-variant font-medium">Email Resmi</div>
                            <a href="mailto:{{ $company->email }}" class="text-sm font-semibold text-on-surface hover:text-primary transition-colors truncate block">
                                {{ $company->email }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(!empty($company->phone) || !empty($company->whatsapp))
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <span class="material-symbols-outlined text-[18px]">call</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs text-on-surface-variant font-medium">Telepon / WhatsApp</div>
                            <a href="{{ !empty($company->whatsapp) ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $company->whatsapp) : 'tel:' . ($company->phone ?? '') }}" target="_blank" class="text-sm font-semibold text-on-surface hover:text-emerald-600 transition-colors truncate block">
                                {{ $company->whatsapp ?: $company->phone }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(!empty($company->address))
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-secondary flex-shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs text-on-surface-variant font-medium">Lokasi Kantor</div>
                            <div class="text-sm font-semibold text-on-surface leading-relaxed">
                                {{ $company->address }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="pt-4 mt-2 border-t border-outline-variant/30 flex gap-3">
                        <a href="{{ url('/contact') }}" class="flex-1 py-2.5 bg-primary text-white text-center rounded-xl font-bold text-xs hover:brightness-110 transition-all shadow-xs" wire:navigate>
                            Hubungi Kami
                        </a>
                        <a href="{{ route('products.index') }}" class="flex-1 py-2.5 bg-surface-container text-on-surface text-center rounded-xl font-bold text-xs hover:bg-surface-container-high transition-all border border-outline-variant/40" wire:navigate>
                            Jelajahi Store
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
