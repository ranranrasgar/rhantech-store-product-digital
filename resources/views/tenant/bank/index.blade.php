@extends('layouts.tenant')

@section('title', 'Payment Services')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-6 bg-surface-container-lowest dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md min-h-[calc(100vh-56px)]">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Top Alerts (Session Success/Error) -->
        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 p-4 rounded-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button class="opacity-50 hover:opacity-100" onclick="this.parentElement.style.display='none'"><span class="material-symbols-outlined text-sm">close</span></button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 p-4 rounded-lg flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button class="opacity-50 hover:opacity-100" onclick="this.parentElement.style.display='none'"><span class="material-symbols-outlined text-sm">close</span></button>
            </div>
        @endif

        <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden">
            
            <div class="px-6 py-4 flex justify-between items-center bg-surface dark:bg-[#161b22] border-b border-outline-variant dark:border-[#30363d]">
                <h3 class="font-bold text-lg text-on-surface dark:text-white">Rekening Bank</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Tambah Rekening Bank Card -->
                    <a href="{{ route('tenant.store.index') }}" class="border-2 border-dashed border-outline-variant dark:border-[#30363d] rounded-lg p-6 flex flex-col items-center justify-center min-h-[160px] text-on-surface-variant dark:text-gray-400 hover:bg-surface-container-lowest dark:hover:bg-[#0d1117] transition-colors group cursor-pointer">
                        <span class="material-symbols-outlined text-4xl mb-2 group-hover:text-primary transition-colors">add</span>
                        <span class="text-sm font-semibold group-hover:text-primary transition-colors">Tambah Rekening Bank</span>
                    </a>

                    <!-- Saved Bank Account Card -->
                    @if(!empty($store->bank_account_info))
                        <div class="border border-outline-variant dark:border-[#30363d] rounded-lg p-0 overflow-hidden relative min-h-[160px] shadow-sm flex flex-col justify-between group">
                            
                            <!-- Background Logo (Decorative) -->
                            <div class="absolute right-[-20px] top-4 text-9xl font-black text-outline-variant/10 dark:text-white/5 select-none z-0 pointer-events-none">
                                BCA
                            </div>

                            <!-- Card Header -->
                            <div class="p-4 relative z-10 flex flex-col items-start bg-gradient-to-r from-[#6b7280] to-[#4b5563] text-white">
                                <div class="flex items-center gap-2 bg-white px-2 py-1 rounded text-[#0055aa] text-xs font-bold w-12 justify-center shadow-sm">
                                    BCA
                                </div>
                            </div>
                            
                            <!-- Card Body -->
                            <div class="p-4 relative z-10 flex-1 flex flex-col justify-center bg-surface dark:bg-[#161b22]">
                                <div class="flex items-center gap-1 text-green-600 dark:text-green-400 text-xs font-bold mb-4">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Telah Ditambahkan
                                </div>
                                <div class="text-xl tracking-widest font-mono text-on-surface dark:text-white">
                                    **** {{ substr($store->bank_account_info, -4) ?: '0000' }}
                                </div>
                            </div>
                            
                            <!-- Card Footer -->
                            <div class="px-4 py-3 bg-surface-container-lowest dark:bg-[#0d1117] border-t border-outline-variant dark:border-[#30363d] relative z-10 flex justify-between items-center">
                                <div class="text-sm font-bold text-on-surface-variant dark:text-gray-400 tracking-widest uppercase">
                                    {{ substr(auth()->user()->name, 0, 1) }}****{{ substr(auth()->user()->name, -1) }} {{ substr($store->bank_account_info, 0, 1) }}****{{ substr($store->bank_account_info, 3, 1) ?? 'U' }}
                                </div>
                                <div class="text-[10px] font-bold bg-[#e0f2fe] text-[#0284c7] px-2 py-1 rounded">
                                    UTAMA
                                </div>
                            </div>

                            <!-- Edit Overlay -->
                            <a href="{{ route('tenant.store.index') }}" class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity z-20 flex items-center justify-center text-white font-bold backdrop-blur-sm">
                                <span class="flex items-center gap-2"><span class="material-symbols-outlined">edit</span> Ubah Rekening</span>
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
