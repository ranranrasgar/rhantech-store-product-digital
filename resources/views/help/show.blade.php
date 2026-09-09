<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - Pusat Bantuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@300..700,0..1&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Google Sans', sans-serif; background-color: #fff; }
        .article-content h1 { font-size: 1.75rem; font-weight: normal; margin-top: 1.5rem; margin-bottom: 1rem; color: #202124; }
        .article-content h2 { font-size: 1.5rem; font-weight: normal; margin-top: 1.5rem; margin-bottom: 1rem; color: #202124; }
        .article-content h3 { font-size: 1.25rem; font-weight: 500; margin-top: 1.25rem; margin-bottom: 0.75rem; color: #202124; }
        .article-content p { margin-bottom: 1rem; line-height: 1.6; color: #3c4043; }
        .article-content ul, .article-content ol { margin-left: 1.5rem; margin-bottom: 1rem; color: #3c4043; }
        .article-content ul { list-style-type: disc; }
        .article-content ol { list-style-type: decimal; }
        .article-content li { margin-bottom: 0.5rem; }
        .article-content a { color: #1a73e8; text-decoration: none; }
        .article-content a:hover { text-decoration: underline; }
        .article-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 1rem 0; border: 1px solid #dadce0; }
        .article-content blockquote { border-left: 4px solid #dadce0; padding-left: 1rem; margin-left: 0; color: #5f6368; font-style: italic; }
        .article-content code { background-color: #f1f3f4; padding: 0.2rem 0.4rem; border-radius: 4px; font-family: monospace; font-size: 0.9em; }
        
        .sidebar-nav a { display: block; padding: 8px 16px; border-radius: 0 16px 16px 0; color: #3c4043; text-decoration: none; margin-bottom: 4px; font-size: 14px; }
        .sidebar-nav a:hover { background-color: #f1f3f4; }
        .sidebar-nav a.active { background-color: #e8f0fe; color: #1a73e8; font-weight: 500; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <!-- Top Navigation -->
    <header class="flex items-center justify-between px-6 py-3 sticky top-0 bg-white z-50 border-b border-gray-200">
        <div class="flex items-center gap-3 w-full max-w-6xl mx-auto">
            <a href="{{ route('help.index') }}" class="flex items-center gap-2 mr-6 shrink-0">
                <span class="font-bold text-xl text-gray-600 tracking-tight">{{ env('APP_NAME', 'rhantech') }}</span>
                <span class="text-xl text-gray-500">Bantuan</span>
            </a>
            
            <form action="{{ route('help.index') }}" method="GET" class="flex-1 max-w-2xl relative hidden md:block">
                <div class="relative flex items-center w-full h-10 rounded-lg bg-gray-100 overflow-hidden border border-transparent focus-within:bg-white focus-within:shadow-md transition-all">
                    <div class="grid place-items-center h-full w-10 text-gray-500">
                        <span class="material-symbols-outlined text-[20px] leading-none">search</span>
                    </div>
                    <input class="peer h-full w-full outline-none text-sm text-gray-700 pr-2 bg-transparent" type="text" name="q" placeholder="Jelaskan masalah Anda" autocomplete="off" />
                </div>
            </form>
        </div>
    </header>

    <!-- Main Content Grid -->
    <div class="flex-1 max-w-6xl mx-auto w-full flex flex-col md:flex-row mt-6 px-4 gap-8">
        
        <!-- Sidebar Navigation -->
        <aside class="hidden md:block w-64 shrink-0 pb-12">
            <div class="sticky top-24">
                <h3 class="font-medium text-sm text-gray-500 uppercase tracking-wider pl-4 mb-4">
                    {{ $article->category->name ?? 'Terkait' }}
                </h3>
                <nav class="sidebar-nav">
                    @foreach($relatedArticles as $related)
                        <a href="{{ route('help.show', $related->slug) }}" class="{{ $related->id == $article->id ? 'active' : '' }}">
                            {{ $related->title }}
                        </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <!-- Article Content -->
        <main class="flex-1 pb-20">
            <!-- Breadcrumbs -->
            <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('help.index') }}" class="hover:text-blue-600 hover:underline">Pusat Bantuan</a>
                    </li>
                    @if($article->category)
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <span class="text-gray-500">{{ $article->category->name }}</span>
                        </div>
                    </li>
                    @endif
                </ol>
            </nav>

            <article class="bg-white">
                <h1 class="text-3xl font-normal text-gray-900 mb-8">{{ $article->title }}</h1>
                
                <div class="article-content max-w-none">
                    {!! $article->content !!}
                </div>
            </article>

            <!-- Feedback Section -->
            <div class="mt-16 border-t border-gray-200 pt-8 text-center" id="feedback-section">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Apakah ini membantu?</h3>
                <div class="flex justify-center gap-4">
                    <button onclick="submitFeedback('yes')" class="px-6 py-2 border border-gray-300 rounded-full hover:bg-gray-50 text-sm font-medium transition-colors text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Ya</button>
                    <button onclick="submitFeedback('no')" class="px-6 py-2 border border-gray-300 rounded-full hover:bg-gray-50 text-sm font-medium transition-colors text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Tidak</button>
                </div>
            </div>
        </main>
        
    </div>

    <footer class="bg-gray-50 border-t border-gray-200 py-6 text-center text-sm text-gray-500 mt-auto">
        &copy; {{ date('Y') }} {{ env('APP_NAME', 'rhantech') }}. Pusat Bantuan.
    </footer>

    <script>
        function submitFeedback(type) {
            const section = document.getElementById('feedback-section');
            const articleId = {{ $article->id }};
            const token = '{{ csrf_token() }}';

            fetch(`/help/article/${articleId}/feedback`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ type: type })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    section.innerHTML = `<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg inline-block">
                        <span class="material-symbols-outlined align-middle mr-2">check_circle</span>
                        ${data.message}
                    </div>`;
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
