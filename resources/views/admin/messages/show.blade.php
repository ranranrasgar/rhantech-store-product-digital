@extends('layouts.admin')
@section('title', 'Detail Pesan Masuk')

@section('content')
@php
    $cleanPhone = preg_replace('/[^0-9]/', '', $message->phone ?? '');
    if (str_starts_with($cleanPhone, '0')) {
        $cleanPhone = '62' . substr($cleanPhone, 1);
    }
@endphp

<div class="p-lg md:p-xl flex-1 max-w-4xl mx-auto w-full space-y-4" x-data="{ replyOpen: false }">
    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center gap-3 shadow-xs">
        <span class="material-symbols-outlined text-emerald-600 text-[22px]">check_circle</span>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 flex items-center gap-3 shadow-xs">
        <span class="material-symbols-outlined text-rose-600 text-[22px]">error</span>
        <span class="font-medium text-sm">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Message Card --}}
    <div class="bg-surface rounded-2xl border border-outline-variant p-6 md:p-8 shadow-none">
        {{-- Header Info --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 border-b border-outline-variant/60 pb-6 mb-6">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-headline-sm font-bold text-on-surface text-lg md:text-xl">
                        {{ $message->subject ?: '(Tanpa Subjek)' }}
                    </h3>
                    @if($message->read_at)
                    <span class="px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 text-xs font-bold border border-zinc-200 dark:border-zinc-800">
                        Dibaca: {{ $message->read_at->format('d M Y H:i') }}
                    </span>
                    @else
                    <span class="px-2 py-0.5 rounded-full bg-orange-100 dark:bg-orange-950 text-orange-700 dark:text-orange-300 text-xs font-bold border border-orange-300 dark:border-orange-800">
                        Pesan Baru
                    </span>
                    @endif
                </div>

                <div class="text-sm text-on-surface-variant flex items-center gap-2 flex-wrap">
                    <span>Pengirim: <strong class="text-on-surface font-semibold">{{ $message->name }}</strong></span>
                    <span>&bull;</span>
                    <a href="mailto:{{ $message->email }}" class="text-primary hover:underline flex items-center gap-1 font-mono text-xs">
                        <span class="material-symbols-outlined text-[14px]">email</span>
                        {{ $message->email }}
                    </a>
                </div>

                @if($message->phone)
                <div class="text-xs text-emerald-600 dark:text-emerald-400 flex items-center gap-2 mt-1">
                    <span class="material-symbols-outlined text-[15px]">call</span>
                    <span class="font-mono">{{ $message->phone }}</span>
                    @if(!empty($cleanPhone))
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo ' . $message->name . ', menanggapi pesan Anda di ' . config('app.name', 'Rhantech') . ':') }}" 
                       target="_blank" 
                       class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 text-xs font-bold transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">chat</span>
                        Chat WhatsApp
                    </a>
                    @endif
                </div>
                @endif

                @if($message->company)
                <div class="text-xs text-on-surface-variant/80 italic">
                    Perusahaan/Instansi: {{ $message->company }}
                </div>
                @endif
            </div>

            <div class="text-left md:text-right shrink-0">
                <div class="font-mono text-xs text-on-surface font-bold">{{ $message->created_at->format('d F Y, H:i') }} WIB</div>
                <div class="text-[11px] text-on-surface-variant/70">{{ $message->created_at->diffForHumans() }}</div>
            </div>
        </div>
        
        {{-- Message Body --}}
        <div class="p-4 md:p-6 rounded-xl bg-surface-container-lowest border border-outline-variant/50 text-on-surface font-body-lg text-sm md:text-base leading-relaxed whitespace-pre-wrap min-h-[180px]">
{{ $message->message }}
        </div>

        {{-- Topbar Buttons --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mt-6 pt-4 border-t border-outline-variant/60">
            <a href="{{ route('admin.messages.index') }}" class="px-4 py-2 border border-outline-variant rounded-xl font-semibold text-xs text-on-surface hover:bg-surface-variant transition flex items-center gap-1.5" wire:navigate>
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali ke Inbox
            </a>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Tombol Buka Form Balas --}}
                <button type="button" 
                        @click="replyOpen = !replyOpen"
                        class="px-4 py-2 bg-primary text-on-primary rounded-xl font-bold text-xs hover:bg-primary/90 transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">reply</span>
                    <span x-text="replyOpen ? 'Tutup Form Balas' : 'Balas Pesan Ini'"></span>
                </button>

                {{-- Toggle Read Status --}}
                <form action="{{ route('admin.messages.toggle-read', $message) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-3.5 py-2 border border-outline-variant bg-surface-container rounded-xl font-semibold text-xs text-on-surface hover:bg-surface-variant transition flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">{{ $message->read_at ? 'mark_email_unread' : 'mark_email_read' }}</span>
                        {{ $message->read_at ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}
                    </button>
                </form>

                {{-- Delete Button --}}
                <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini secara permanen?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-2 bg-error text-white rounded-xl font-bold text-xs hover:bg-[#93000A] transition shadow flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                        Hapus Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Reply Form Section (Accordion / Toggle) --}}
    <div x-show="replyOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-surface rounded-2xl border border-primary/30 p-6 md:p-8 shadow-none">
        
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-outline-variant">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[24px]">send</span>
                <h4 class="font-bold text-base text-on-surface">Kirim Balasan Email ke {{ $message->name }}</h4>
            </div>
            <div class="flex items-center gap-2">
                <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . ($message->subject ?: 'Kontak Website')) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-primary/10 text-primary text-xs font-bold transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                    Buka di Email App
                </a>
            </div>
        </div>

        <form action="{{ route('admin.messages.reply', $message) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-on-surface mb-1">Kepada</label>
                <input type="text" value="{{ $message->name }} &lt;{{ $message->email }}&gt;" disabled class="w-full px-3 py-2 text-sm rounded-lg bg-surface-container-low border border-outline-variant text-on-surface-variant font-mono">
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface mb-1">Subjek Balasan</label>
                <input type="text" name="reply_subject" value="Re: {{ $message->subject ?: 'Pesan dari Kontak Website' }}" required class="w-full px-3 py-2 text-sm rounded-lg bg-surface-container-low border border-outline-variant text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div>
                <label class="block text-xs font-bold text-on-surface mb-1">Isi Pesan Balasan</label>
                <textarea name="reply_message" rows="6" required placeholder="Tuliskan jawaban atau konfirmasi untuk pengunjung..." class="w-full px-3 py-2 text-sm rounded-lg bg-surface-container-low border border-outline-variant text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="replyOpen = false" class="px-4 py-2 border border-outline-variant rounded-xl font-bold text-xs text-on-surface hover:bg-surface-variant transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-xl font-bold text-xs hover:bg-primary/90 transition shadow flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    Kirim Balasan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
