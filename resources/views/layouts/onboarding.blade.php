<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex"/>
<title>@yield("title", "Mulai Setup") — Rhantech</title>
<link rel="icon" type="image/png" href="{{ isset($company) && $company->favicon ? "/storage/".$company->favicon : "/favicon.ico" }}" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
@include("components.phosphor-icons")
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      fontFamily: { sans: ["Geist", "ui-sans-serif", "system-ui"] },
      colors: { brand: { DEFAULT: "#00838f", dark: "#006d77" } },
    }
  }
};
</script>
<style>
  body { font-family: "Geist", ui-sans-serif, system-ui; }
  [x-cloak] { display: none !important; }
  @keyframes fade-up { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
  .animate-fade-up { animation: fade-up 0.35s ease both; }
  .animate-fade-up-delay-1 { animation: fade-up 0.35s ease 0.08s both; }
  @media (prefers-reduced-motion: reduce) {
    .animate-fade-up, .animate-fade-up-delay-1 { animation: none; }
  }
</style>
@include("components.theme-init")
</head>
<body class="min-h-full bg-slate-50 dark:bg-[#090d16] text-zinc-900 dark:text-zinc-100 antialiased">

{{-- Top Bar --}}
<header class="sticky top-0 z-40 flex items-center justify-between gap-3 px-4 sm:px-8 py-3 bg-white/90 dark:bg-[#090d16]/90 backdrop-blur border-b border-slate-200/70 dark:border-slate-800">
    <a href="{{ url("/") }}" class="flex items-center gap-2 shrink-0 text-zinc-800 dark:text-zinc-100 hover:opacity-80 transition">
        @if(isset($company) && $company->logo)
            <img src="{{ asset("storage/".$company->logo) }}" alt="{{ $company->company_name }}" class="h-7 w-auto object-contain">
        @else
            <span class="font-black text-xl tracking-tight text-brand">Rhantech</span>
        @endif
    </a>
    <div class="flex items-center gap-3 min-w-0 text-xs text-zinc-500 dark:text-zinc-400">
        <span class="truncate font-semibold text-slate-700 dark:text-slate-200">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route("logout") }}" class="shrink-0">
            @csrf
            <button type="submit" class="flex items-center gap-1 py-1.5 text-rose-600 hover:text-rose-700 font-semibold transition-colors">
                <i class="ph ph-sign-out text-[15px]"></i> Keluar
            </button>
        </form>
    </div>
</header>

{{-- Main Content --}}
<main class="px-4 pt-5 sm:pt-10 pb-32 md:pb-12">
    @yield("content")
</main>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
</body>
</html>
