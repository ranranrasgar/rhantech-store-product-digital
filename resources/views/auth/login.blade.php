<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.theme-init')
    <title>Admin Login - rhantech</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>tailwind.config = { darkMode: 'class' };</script>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <style>body { font-family: 'Geist', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-slate-950 dark:text-slate-100 antialiased min-h-screen flex items-center justify-center p-6 transition-colors">
    <div class="absolute right-6 top-6">
        <x-theme-toggle class="border-gray-200 bg-white text-gray-600 hover:bg-gray-100 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 focus:ring-[#06B6D4] focus:ring-offset-gray-50 dark:focus:ring-offset-slate-950" />
    </div>

    <div class="max-w-md w-full bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-gray-100 dark:border-slate-800 p-8 transition-colors">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Admin Login</h1>
            <p class="text-gray-500 dark:text-slate-400">Sign in to manage your company profile.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/50 text-red-700 dark:text-red-300 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 bg-white dark:bg-slate-950 text-gray-900 dark:text-slate-100 border border-gray-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent outline-none transition-all">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 bg-white dark:bg-slate-950 text-gray-900 dark:text-slate-100 border border-gray-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#06B6D4] focus:border-transparent outline-none transition-all">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-[#06B6D4] bg-white dark:bg-slate-950 border-gray-300 dark:border-slate-700 rounded focus:ring-[#06B6D4]">
                    <span class="ml-2 text-sm text-gray-600 dark:text-slate-400">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-[#06B6D4] hover:bg-[#0891B2] text-white font-medium rounded-lg transition-colors shadow-md hover:shadow-lg focus:ring-2 focus:ring-offset-2 focus:ring-[#06B6D4]">
                Sign In
            </button>
        </form>
    </div>
    @include('components.theme-manager')
</body>
</html>
