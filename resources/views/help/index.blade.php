<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan - {{ env('APP_NAME', 'rhantech') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@300..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Google Sans', sans-serif; background-color: #fff; }
        .search-container { background-color: #f8f9fa; border-bottom: 1px solid #dadce0; }
        .category-card { transition: box-shadow 0.2s; }
        .category-card:hover { box-shadow: 0 1px 3px rgba(60,64,67,0.3), 0 4px 8px 3px rgba(60,64,67,0.15); }
    </style>
</head>
<body class="text-gray-800">

    <!-- Top Navigation -->
    <header class="flex items-center justify-between px-6 py-3 border-b border-gray-200 sticky top-0 bg-white z-50">
        <div class="flex items-center gap-3">
            <a href="/" class="flex items-center gap-2">
                <span class="font-bold text-xl text-gray-600 tracking-tight">{{ env('APP_NAME', 'rhantech') }}</span>
                <span class="text-xl text-gray-500">Bantuan</span>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <a href="/" class="text-sm font-medium text-blue-600 hover:underline">Kembali ke Beranda</a>
        </div>
    </header>

    <!-- Search Hero Section -->
    <section class="search-container py-16 px-4 text-center">
        <h1 class="text-4xl font-normal mb-8 text-gray-800">Bagaimana kami dapat membantu Anda?</h1>
        
        <form action="{{ route('help.index') }}" method="GET" class="max-w-2xl mx-auto relative">
            <div class="relative flex items-center w-full h-12 rounded-full shadow-md bg-white overflow-hidden border border-transparent focus-within:border-blue-500 transition-colors">
                <div class="grid place-items-center h-full w-12 text-gray-400">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input class="peer h-full w-full outline-none text-sm text-gray-700 pr-2 bg-transparent" type="text" name="q" placeholder="Jelaskan masalah Anda" autocomplete="off" />
            </div>
        </form>
    </section>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-12">
        
        @if(count($popularArticles) > 0)
        <!-- Popular Articles -->
        <div class="mb-12 border border-gray-300 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-300">
                <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Artikel Populer</h2>
            </div>
            <div class="bg-white">
                <ul class="divide-y divide-gray-200">
                    @foreach($popularArticles as $article)
                    <li>
                        <a href="{{ route('help.show', $article->slug) }}" class="flex items-center px-6 py-4 hover:bg-gray-50 transition-colors">
                            <span class="material-symbols-outlined text-gray-400 mr-4">article</span>
                            <span class="text-blue-600 hover:underline font-medium">{{ $article->title }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Help Categories -->
        <h2 class="text-2xl font-normal mb-6 text-center">Jelajahi topik bantuan</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($categories as $category)
            <a href="#category-{{ $category->slug }}" class="category-card block bg-white border border-gray-200 rounded-xl p-6 text-center h-full">
                <span class="material-symbols-outlined text-4xl text-blue-500 mb-4">{{ $category->icon ?: 'folder' }}</span>
                <h3 class="text-lg font-medium text-gray-800 mb-2">{{ $category->name }}</h3>
                <p class="text-sm text-gray-500">{{ $category->description }}</p>
            </a>
            @empty
            <div class="col-span-full text-center text-gray-500 py-10">
                Belum ada topik bantuan.
            </div>
            @endforelse
        </div>

        <!-- Articles by Category (List) -->
        <div class="mt-16 space-y-12">
            @foreach($categories as $category)
            <div id="category-{{ $category->slug }}">
                <div class="flex items-center gap-3 mb-4 border-b pb-2">
                    <span class="material-symbols-outlined text-2xl text-gray-600">{{ $category->icon ?: 'folder' }}</span>
                    <h3 class="text-xl font-medium text-gray-800">{{ $category->name }}</h3>
                </div>
                
                <ul class="space-y-3 pl-2">
                    @forelse($category->articles as $article)
                    <li>
                        <a href="{{ route('help.show', $article->slug) }}" class="text-blue-600 hover:underline inline-flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-gray-400">description</span>
                            {{ $article->title }}
                        </a>
                    </li>
                    @empty
                    <li class="text-gray-500 text-sm italic">Belum ada panduan.</li>
                    @endforelse
                </ul>
            </div>
            @endforeach
        </div>

    </main>

    <footer class="bg-gray-50 border-t border-gray-200 py-6 text-center text-sm text-gray-500 mt-20">
        &copy; {{ date('Y') }} {{ env('APP_NAME', 'rhantech') }}. Pusat Bantuan.
    </footer>

</body>
</html>
