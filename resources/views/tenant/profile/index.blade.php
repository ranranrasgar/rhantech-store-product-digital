@extends('layouts.tenant')

@section('title', 'Profil Akun')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Profil Akun
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kelola informasi pribadi, email, dan keamanan akun Anda.
                </p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
        @endif

        <form action="{{ route('tenant.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            @csrf
            @method('PUT')
            
            <div class="p-6 md:p-8 space-y-8">
                
                <!-- Avatar Section -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-6 pb-8 border-b border-slate-100 dark:border-[#222f49]">
                    <div class="relative inline-block">
                        <x-user-avatar class="w-20 h-20 md:w-24 md:h-24 border-4 border-white dark:border-[#161f33] shadow-sm" />
                        <label for="avatar_input" class="absolute bottom-0 right-0 bg-sky-600 hover:bg-sky-700 text-white w-8 h-8 rounded-full flex items-center justify-center cursor-pointer shadow-md transition-colors border-2 border-white dark:border-[#161f33]">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                        </label>
                        <input type="file" id="avatar_input" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                    </div>
                    <div>
                        <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white text-sm transition-all">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white text-sm transition-all">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Password Section -->
                <div class="pt-8 border-t border-slate-100 dark:border-[#222f49] space-y-6">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Ganti Password</h3>
                        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">Kosongkan bagian ini jika Anda tidak ingin mengubah password.</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="max-w-md space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Password Saat Ini</label>
                            <input type="password" name="current_password" 
                                   class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white text-sm transition-all">
                            @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            
                            @if(auth()->user()->provider_name == 'google')
                            <div class="flex items-start gap-1.5 mt-2 text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-900/20 p-2.5 rounded-lg border border-sky-100 dark:border-sky-800/30">
                                <span class="material-symbols-outlined text-[16px] shrink-0 mt-0.5">info</span>
                                <p class="text-xs leading-relaxed">Karena Anda mendaftar via Google, Anda tidak perlu mengisi kolom ini jika belum pernah membuat password lokal.</p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Password Baru</label>
                                <input type="password" name="password" 
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white text-sm transition-all">
                                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" 
                                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white text-sm transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Footer -->
            <div class="bg-slate-50/50 dark:bg-[#0c1220]/50 px-6 py-4 border-t border-slate-100 dark:border-[#222f49] flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-sm font-bold rounded-xl transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- Pengaturan Sistem & Tampilan Tema Pengguna -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl overflow-hidden p-6 md:p-8"
             x-data="{ 
                 currentTheme: localStorage.getItem('rhantech-theme') || (document.documentElement.classList.contains('dark') ? 'dark' : 'light'),
                 setTheme(theme) {
                     this.currentTheme = theme;
                     localStorage.setItem('rhantech-theme', theme);
                     document.documentElement.classList.toggle('dark', theme === 'dark');
                     document.documentElement.dataset.theme = theme;
                     document.documentElement.style.colorScheme = theme;
                     window.dispatchEvent(new Event('theme-changed'));
                 }
             }">
            <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100 dark:border-[#222f49]">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">tune</span>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Pengaturan Sistem &amp; Mode Tampilan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pilih tema antarmuka (Light / Dark) yang paling nyaman untuk Anda gunakan saat berselancar.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Mode Terang (Light) -->
                <div @click="setTheme('light')"
                     class="p-5 rounded-2xl border-2 transition-all cursor-pointer relative overflow-hidden bg-white text-slate-800 active:scale-95"
                     :class="currentTheme === 'light' ? 'border-slate-900 dark:border-white' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[22px]">light_mode</span>
                        </span>
                        <span x-show="currentTheme === 'light'" class="flex items-center gap-1 text-slate-900 font-bold text-xs bg-slate-100 px-2.5 py-1 rounded-full border border-slate-300">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span> Aktif
                        </span>
                    </div>
                    <h4 class="font-extrabold text-sm text-slate-900">Mode Terang (Light)</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Latar belakang putih bersih dan kontras tajam, optimal untuk penggunaan siang hari.</p>
                </div>

                <!-- Mode Gelap (Dark) -->
                <div @click="setTheme('dark')"
                     class="p-5 rounded-2xl border-2 transition-all cursor-pointer relative overflow-hidden bg-[#0d1117] text-white active:scale-95"
                     :class="currentTheme === 'dark' ? 'border-slate-900 dark:border-white' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600'">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2.5 rounded-xl bg-indigo-950 text-slate-200 flex items-center justify-center border border-indigo-900/60">
                            <span class="material-symbols-outlined text-[22px]">dark_mode</span>
                        </span>
                        <span x-show="currentTheme === 'dark'" class="flex items-center gap-1 text-white font-bold text-xs bg-slate-800 px-2.5 py-1 rounded-full border border-slate-700">
                            <span class="material-symbols-outlined text-[14px]">check_circle</span> Aktif
                        </span>
                    </div>
                    <h4 class="font-extrabold text-sm text-white">Mode Gelap (Dark)</h4>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Latar belakang gelap GitHub-style elegan, nyaman dan mengurangi ketegangan mata di malam hari.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            input.parentElement.querySelector('img').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
