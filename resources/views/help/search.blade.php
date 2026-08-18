<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian "{{ $query }}" - Pusat Bantuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@300..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Google Sans', sans-serif; background-color: #fff; }
        .search-container { background-color: #fff; border-bottom: 1px solid #dadce0; }
    </style>
</head>
<body class="text-gray-800">

    <!-- Top Navigation -->
    <header class="flex items-center justify-between px-6 py-3 sticky top-0 bg-white z-50">
        <div class="flex items-center gap-3 w-full max-w-4xl mx-auto">
            <a href="{{ route('help.index') }}" class="flex items-center gap-2 mr-6">
                <span class="font-bold text-xl text-gray-600 tracking-tight">{{ env('APP_NAME', 'rhantech') }}</span>
                <span class="text-xl text-gray-500">Bantuan</span>
            </a>
            
            <form action="{{ route('help.index') }}" method="GET" class="flex-1 max-w-2xl relative">
                <div class="relative flex items-center w-full h-10 rounded-lg bg-gray-100 overflow-hidden border border-transparent focus-within:bg-white focus-within:shadow-md transition-all">
                    <div class="grid place-items-center h-full w-10 text-gray-500">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </div>
                    <input class="peer h-full w-full outline-none text-sm text-gray-700 pr-2 bg-transparent" type="text" name="q" value="{{ $query }}" placeholder="Jelaskan masalah Anda" autocomplete="off" />
                </div>
            </form>
        </div>
    </header>
    <div class="h-[1px] w-full bg-gray-200"></div>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        
        <h1 class="text-2xl font-normal mb-8">
            Hasil penelusuran untuk <strong>"{{ $query }}"</strong>
        </h1>
        
        <div class="space-y-6">
            @forelse($articles as $article)
            <div class="border-b border-gray-100 pb-6">
                <a href="{{ route('help.show', $article->slug) }}" class="group block">
                    <h2 class="text-xl text-blue-600 group-hover:underline mb-1">{{ $article->title }}</h2>
                    <p class="text-xs text-green-700 mb-2">{{ url('/help/article/' . $article->slug) }}</p>
                    <div class="text-sm text-gray-600 line-clamp-2">
                        {!! strip_tags($article->content) !!}
                    </div>
                </a>
                <div class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">folder</span>
                    {{ $article->category->name ?? 'Umum' }}
                </div>
            </div>
            @empty
            <div class="text-center py-16">
                <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search_off</span>
                <h2 class="text-xl text-gray-600 mb-2">Tidak ada hasil yang ditemukan</h2>
                <p class="text-gray-500">Silakan periksa ejaan Anda atau coba gunakan kata kunci lain yang lebih umum.</p>
                <div class="mt-8">
                    <a href="{{ route('help.index') }}" class="text-blue-600 hover:underline">Kembali ke Beranda Pusat Bantuan</a>
                </div>
            </div>
            @endforelse
        </div>

    </main>

</body>
</html>
