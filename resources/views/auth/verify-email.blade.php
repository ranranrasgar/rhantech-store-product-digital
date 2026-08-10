@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-surface-container-lowest flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-surface rounded-lg  border border-outline-variant p-8">
        
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl">mark_email_unread</span>
            </div>
            <h1 class="text-2xl font-headline-lg font-bold text-on-surface mb-2">Verifikasi Email Anda</h1>
            <p class="text-on-surface-variant font-body-md text-sm">
                Terima kasih telah mendaftar! Sebelum memulai, harap verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan ke email Anda.
            </p>
        </div>

        @if (session('message'))
            <div class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 p-4 rounded-lg text-sm mb-6 text-center border border-green-200 dark:border-green-800">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex flex-col gap-4">
            <p class="text-xs text-on-surface-variant text-center">
                Jika Anda tidak menerima email tersebut, kami dapat mengirimkan tautan baru untuk Anda.
            </p>
            
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-on-primary py-3 rounded-lg font-bold transition-colors">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-2 text-center">
                @csrf
                <button type="submit" class="text-sm text-primary hover:underline font-medium">
                    Logout
                </button>
            </form>
        </div>
        
    </div>
</div>
@endsection
