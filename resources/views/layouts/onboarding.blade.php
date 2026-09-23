<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield("title", "Mulai Setup") — Rhantech</title>
<link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? "/storage/".$company->favicon : "/favicon.ico" }}" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      fontFamily: { sans: ["Geist", "ui-sans-serif", "system-ui"] },
    }
  }
};
</script>
<style>
  body { font-family: "Geist", ui-sans-serif, system-ui; }
  .material-symbols-outlined { font-size: inherit; line-height: inherit; display: inline-flex; align-items: center; vertical-align: middle; }
  @keyframes fade-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .animate-fade-up { animation: fade-up 0.5s ease forwards; }
  .animate-fade-up-delay-1 { animation: fade-up 0.5s ease 0.1s forwards; opacity: 0; }
  .animate-fade-up-delay-2 { animation: fade-up 0.5s ease 0.2s forwards; opacity: 0; }
  .animate-fade-up-delay-3 { animation: fade-up 0.5s ease 0.3s forwards; opacity: 0; }
</style>
@include("components.theme-init")
</head>
<body class="h-full bg-gradient-to-br from-slate-50 via-white to-teal-50/30 dark:from-[#060b14] dark:via-[#090d16] dark:to-[#060b14] text-zinc-900 dark:text-zinc-100 antialiased">

{{-- Top Bar --}}
<header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-4 sm:px-8 py-4">
    <a href="{{ url("/") }}" class="flex items-center gap-2 text-zinc-800 dark:text-zinc-100 hover:opacity-80 transition">
        @if(isset($company) && $company->logo)
            <img src="{{ asset("storage/".$company->logo) }}" alt="{{ $company->company_name }}" class="h-7 w-auto object-contain">
        @else
            <span class="font-black text-xl tracking-tight text-[#00838f]">Rhantech</span>
        @endif
    </a>
    <div class="flex items-center gap-3 text-xs text-zinc-500 dark:text-zinc-400">
        <span>Masuk sebagai <span class="font-bold text-slate-700 dark:text-slate-200">{{ auth()->user()->name }}</span></span>
        <form method="POST" action="{{ route("logout") }}">
            @csrf
            <button type="submit" class="text-rose-500 hover:text-rose-600 font-semibold transition-colors">Keluar</button>
        </form>
    </div>
</header>

{{-- Main Content --}}
<main class="min-h-screen pt-20 pb-12 px-4">
    @yield("content")
</main>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
