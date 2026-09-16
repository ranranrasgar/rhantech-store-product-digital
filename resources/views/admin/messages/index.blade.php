@extends('layouts.admin')
@section('title', 'Pesan Masuk (Inbox)')

@section('content')
<div class="p-lg md:p-xl flex-1 flex flex-col gap-lg max-w-container-max mx-auto w-full"
     x-data="{
        selectedIds: [],
        selectAll: false,
        replyModalOpen: false,
        replyData: {
            id: null,
            name: '',
            email: '',
            phone: '',
            subject: '',
            originalMessage: '',
            url: ''
        },
        toggleAll(allIds) {
            if (this.selectAll) {
                this.selectedIds = [...allIds];
            } else {
                this.selectedIds = [];
            }
        },
        openReply(msg) {
            this.replyData = {
                id: msg.id,
                name: msg.name,
                email: msg.email,
                phone: msg.phone || '',
                subject: 'Re: ' + (msg.subject || 'Pesan dari Kontak Website'),
                originalMessage: msg.message,
                url: '/admin/messages/' + msg.id + '/reply'
            };
            this.replyModalOpen = true;
        },
        get cleanPhone() {
            let p = (this.replyData.phone || '').replace(/[^0-9]/g, '');
            if (p.startsWith('0')) {
                p = '62' + p.substring(1);
            }
            return p;
        },
        submitBulk(actionName) {
            if (this.selectedIds.length === 0) return;
            if (actionName === 'delete') {
                if (!confirm('Apakah Anda yakin ingin menghapus ' + this.selectedIds.length + ' pesan terpilih secara permanen?')) {
                    return;
                }
            }
            $refs.bulkActionInput.value = actionName;
            $refs.bulkIdsInput.value = this.selectedIds.join(',');
            $refs.bulkForm.submit();
        }
     }">

    {{-- Header & Metrics --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-headline-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[28px]">mail</span>
                Pesan Masuk (Inbox)
            </h2>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Kelola pesan, pertanyaan, dan konsultasi dari formulir kontak website.
            </p>
        </div>

        {{-- Metric Badges --}}
        <div class="flex items-center gap-3 flex-wrap">
            <div class="px-4 py-2 rounded-xl bg-surface-container border border-outline-variant/60 flex items-center gap-2.5">
                <span class="material-symbols-outlined text-on-surface-variant text-[20px]">inbox</span>
                <div>
                    <div class="text-[11px] font-semibold text-on-surface-variant uppercase tracking-wider">Total Pesan</div>
                    <div class="text-base font-black text-on-surface">{{ number_format($totalCount ?? 0) }}</div>
                </div>
            </div>
            <div class="px-4 py-2 rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800 flex items-center gap-2.5">
                <span class="material-symbols-outlined text-sky-600 dark:text-sky-400 text-[20px]">mark_email_unread</span>
                <div>
                    <div class="text-[11px] font-semibold text-sky-700 dark:text-sky-300 uppercase tracking-wider">Belum Dibaca</div>
                    <div class="text-base font-black text-sky-600 dark:text-sky-400">{{ number_format($unreadCount ?? 0) }}</div>
                </div>
            </div>
            <div class="px-4 py-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-center gap-2.5">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[20px]">mark_email_read</span>
                <div>
                    <div class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-300 uppercase tracking-wider">Sudah Dibaca</div>
                    <div class="text-base font-black text-emerald-600 dark:text-emerald-400">{{ number_format($readCount ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

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

    {{-- Filter Bar --}}
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            {{-- Search input --}}
            <div class="relative flex-1 min-w-[240px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px]">search</span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama, email, no HP, subjek, pesan..." 
                       class="w-full pl-10 pr-4 py-2 rounded-lg bg-surface-container-low border border-outline-variant text-on-surface text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            {{-- Status filter buttons / select --}}
            <div class="flex items-center gap-2 flex-wrap">
                <div class="inline-flex rounded-lg border border-outline-variant p-0.5 bg-surface-container-low text-xs">
                    <a href="{{ route('admin.messages.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}"
                       class="px-3 py-1.5 rounded-md font-semibold transition {{ (!request()->filled('status') || request('status') === 'all') ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface hover:text-primary' }}">
                        Semua ({{ $totalCount }})
                    </a>
                    <a href="{{ route('admin.messages.index', array_merge(request()->except('status', 'page'), ['status' => 'unread'])) }}"
                       class="px-3 py-1.5 rounded-md font-semibold transition flex items-center gap-1 {{ request('status') === 'unread' ? 'bg-sky-600 text-white shadow-xs' : 'text-on-surface hover:text-sky-600' }}">
                        <span>Belum Dibaca</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-sky-100 text-sky-800 dark:bg-sky-900 dark:text-sky-200 font-bold">{{ $unreadCount }}</span>
                    </a>
                    <a href="{{ route('admin.messages.index', array_merge(request()->except('status', 'page'), ['status' => 'read'])) }}"
                       class="px-3 py-1.5 rounded-md font-semibold transition {{ request('status') === 'read' ? 'bg-emerald-600 text-white shadow-xs' : 'text-on-surface hover:text-emerald-600' }}">
                        Sudah Dibaca ({{ $readCount }})
                    </a>
                </div>

                {{-- Date filter inputs --}}
                <input type="date" 
                       name="date_from" 
                       value="{{ request('date_from') }}" 
                       title="Dari tanggal"
                       class="py-1.5 px-2.5 rounded-lg bg-surface-container-low border border-outline-variant text-on-surface text-xs">
                
                <input type="date" 
                       name="date_to" 
                       value="{{ request('date_to') }}" 
                       title="Sampai tanggal"
                       class="py-1.5 px-2.5 rounded-lg bg-surface-container-low border border-outline-variant text-on-surface text-xs">

                <button type="submit" class="px-3.5 py-2 bg-primary text-on-primary rounded-lg font-bold text-xs hover:bg-primary/90 transition flex items-center gap-1 shadow-xs cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                    Filter
                </button>

                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.messages.index') }}" class="px-3 py-2 bg-surface-container text-on-surface-variant hover:text-on-surface rounded-lg font-semibold text-xs border border-outline-variant transition" title="Reset filter">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Bulk Actions Floating / Top Bar (Muncul saat ada checkbox terpilih) --}}
    <div x-show="selectedIds.length > 0" 
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="p-3 bg-slate-900 text-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-700 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-amber-400 text-[20px]">check_circle</span>
            <span class="text-xs font-bold">
                <span x-text="selectedIds.length" class="text-amber-400 font-extrabold text-sm"></span> pesan dipilih
            </span>
        </div>

        <div class="flex items-center gap-2">
            {{-- Tombol Tandai Sudah Dibaca --}}
            <button type="button" 
                    @click="submitBulk('mark_read')"
                    class="px-3 py-1.5 rounded-lg bg-emerald-600/30 hover:bg-emerald-600 text-emerald-200 hover:text-white border border-emerald-500/40 text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                <span class="material-symbols-outlined text-[16px]">mark_email_read</span>
                Tandai Dibaca
            </button>

            {{-- Tombol Tandai Belum Dibaca --}}
            <button type="button" 
                    @click="submitBulk('mark_unread')"
                    class="px-3 py-1.5 rounded-lg bg-sky-600/30 hover:bg-sky-600 text-sky-200 hover:text-white border border-sky-500/40 text-xs font-bold transition flex items-center gap-1 cursor-pointer">
                <span class="material-symbols-outlined text-[16px]">mark_email_unread</span>
                Tandai Belum Dibaca
            </button>

            {{-- Tombol Hapus Global (Hapus Terpilih) --}}
            <button type="button" 
                    @click="submitBulk('delete')"
                    class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-xs cursor-pointer">
                <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                Hapus Terpilih (Global)
            </button>

            {{-- Batalkan Pilihan --}}
            <button type="button" 
                    @click="selectedIds = []; selectAll = false"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition cursor-pointer" 
                    title="Batalkan Pilihan">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>
    </div>

    {{-- Hidden form for bulk actions --}}
    <form x-ref="bulkForm" action="{{ route('admin.messages.bulk-action') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="action" x-ref="bulkActionInput" value="">
        <input type="hidden" name="selected_ids" x-ref="bulkIdsInput" value="">
    </form>

    {{-- Messages Table --}}
    @php
        $allCurrentIds = $messages->pluck('id')->toArray();
    @endphp
    <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden flex-1 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant text-xs">
                    <tr>
                        <th class="py-3.5 px-4 w-10 text-center">
                            <input type="checkbox" 
                                   x-model="selectAll" 
                                   @change="toggleAll({{ json_encode($allCurrentIds) }})"
                                   class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant cursor-pointer"
                                   title="Pilih Semua di Halaman Ini">
                        </th>
                        <th class="py-3.5 px-4 font-label-md font-bold text-on-surface-variant uppercase tracking-wider w-40">Tanggal</th>
                        <th class="py-3.5 px-4 font-label-md font-bold text-on-surface-variant uppercase tracking-wider w-64">Pengirim &amp; Kontak</th>
                        <th class="py-3.5 px-4 font-label-md font-bold text-on-surface-variant uppercase tracking-wider">Subjek &amp; Pesan</th>
                        <th class="py-3.5 px-4 font-label-md font-bold text-on-surface-variant uppercase tracking-wider text-center w-28">Status</th>
                        <th class="py-3.5 px-4 font-label-md font-bold text-on-surface-variant uppercase tracking-wider text-right pr-6 w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/40 bg-surface text-sm">
                    @forelse($messages as $msg)
                    @php
                        $isRead = !is_null($msg->read_at);
                        $cleanPhone = preg_replace('/[^0-9]/', '', $msg->phone ?? '');
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp
                    <tr class="transition-colors hover:bg-surface-container-lowest {{ $isRead ? 'opacity-80' : 'bg-sky-50/40 dark:bg-sky-950/20 font-medium' }}">
                        {{-- Checkbox per item --}}
                        <td class="py-3 px-4 text-center">
                            <input type="checkbox" 
                                   value="{{ $msg->id }}" 
                                   x-model="selectedIds"
                                   class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant cursor-pointer">
                        </td>

                        {{-- Tanggal --}}
                        <td class="py-3 px-4 whitespace-nowrap text-xs">
                            <div class="font-bold text-on-surface">{{ $msg->created_at->format('d M Y') }}</div>
                            <div class="font-mono text-on-surface-variant text-[11px]">{{ $msg->created_at->format('H:i') }} WIB</div>
                            <div class="text-[10px] text-on-surface-variant/70 mt-0.5">{{ $msg->created_at->diffForHumans() }}</div>
                        </td>

                        {{-- Pengirim & Kontak --}}
                        <td class="py-3 px-4">
                            <div class="font-bold text-on-surface text-sm flex items-center gap-1.5">
                                <span>{{ $msg->name }}</span>
                                @if(!$isRead)
                                    <span class="w-2 h-2 rounded-full bg-sky-500 inline-block" title="Pesan baru belum dibaca"></span>
                                @endif
                            </div>
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                <span class="material-symbols-outlined text-[13px] text-on-surface-variant/60">email</span>
                                <a href="mailto:{{ $msg->email }}" class="hover:text-primary hover:underline truncate max-w-[180px]" title="Kirim email">{{ $msg->email }}</a>
                            </div>
                            @if($msg->phone)
                            <div class="text-xs text-emerald-600 dark:text-emerald-400 flex items-center gap-1 mt-0.5 font-mono">
                                <span class="material-symbols-outlined text-[13px]">call</span>
                                <span>{{ $msg->phone }}</span>
                                @if(!empty($cleanPhone))
                                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Halo ' . $msg->name . ', menanggapi pesan Anda di ' . config('app.name', 'Rhantech') . ':') }}" 
                                   target="_blank" 
                                   class="ml-1 inline-flex items-center px-1.5 py-0.2 rounded bg-emerald-100 dark:bg-emerald-950 text-[10px] font-bold text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 transition" 
                                   title="Chat via WhatsApp">
                                    WA
                                </a>
                                @endif
                            </div>
                            @endif
                            @if($msg->company)
                            <div class="text-[11px] text-on-surface-variant/70 italic mt-0.5">
                                {{ $msg->company }}
                            </div>
                            @endif
                        </td>

                        {{-- Subjek & Cuplikan Pesan --}}
                        <td class="py-3 px-4 min-w-[200px]">
                            <a href="{{ route('admin.messages.show', $msg) }}" class="font-bold text-on-surface hover:text-primary transition block mb-1">
                                {{ $msg->subject ?: '(Tanpa Subjek)' }}
                            </a>
                            <p class="text-xs text-on-surface-variant line-clamp-2 max-w-xl font-normal leading-relaxed">
                                {{ Str::limit($msg->message, 140) }}
                            </p>
                        </td>

                        {{-- Status --}}
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            @if($isRead)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[11px] font-bold border border-slate-200 dark:border-slate-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Dibaca
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[11px] font-bold border border-sky-300 dark:border-sky-800 animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                Baru
                            </span>
                            @endif
                        </td>

                        {{-- Aksi (Balas, Detail, Toggle Read, Hapus) --}}
                        <td class="py-3 px-4 text-right pr-6 whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                {{-- Tombol Balas (Reply Modal) --}}
                                <button type="button" 
                                        @click="openReply({{ json_encode($msg) }})"
                                        class="p-1.5 rounded-lg bg-primary/10 hover:bg-primary text-primary hover:text-white transition cursor-pointer flex items-center justify-center shadow-xs" 
                                        title="Balas Pesan Ini">
                                    <span class="material-symbols-outlined text-[18px]">reply</span>
                                </button>

                                {{-- Tombol Lihat / Detail --}}
                                <a href="{{ route('admin.messages.show', $msg) }}" 
                                   class="p-1.5 rounded-lg bg-surface-container hover:bg-surface-variant text-on-surface-variant hover:text-on-surface transition flex items-center justify-center shadow-xs" 
                                   title="Buka & Baca Pesan Lengkap" 
                                   wire:navigate>
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>

                                {{-- Toggle Mark Read / Unread --}}
                                <form action="{{ route('admin.messages.toggle-read', $msg) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="p-1.5 rounded-lg bg-surface-container hover:bg-surface-variant text-on-surface-variant hover:text-on-surface transition cursor-pointer flex items-center justify-center shadow-xs" 
                                            title="{{ $isRead ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}">
                                        <span class="material-symbols-outlined text-[18px]">
                                            {{ $isRead ? 'mark_email_unread' : 'mark_email_read' }}
                                        </span>
                                    </button>
                                </form>

                                {{-- Tombol Hapus Tunggal --}}
                                <form action="{{ route('admin.messages.destroy', $msg) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan dari {{ addslashes($msg->name) }}?');" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1.5 rounded-lg bg-error-container/30 hover:bg-error text-error hover:text-white transition cursor-pointer flex items-center justify-center shadow-xs" 
                                            title="Hapus Pesan">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="w-16 h-16 bg-surface-container-high rounded-full flex items-center justify-center mb-4 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[36px]">mark_email_unread</span>
                                </div>
                                <h3 class="font-headline-sm font-bold text-on-surface mb-1">Tidak ada pesan ditemukan</h3>
                                <p class="font-body-md text-on-surface-variant max-w-sm mx-auto text-xs">
                                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                                        Pencarian dengan filter saat ini tidak membuahkan hasil. Coba ubah kata kunci atau reset filter.
                                    @else
                                        Belum ada pesan yang masuk dari formulir kontak website.
                                    @endif
                                </p>
                                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                                <a href="{{ route('admin.messages.index') }}" class="mt-4 px-4 py-2 rounded-lg bg-primary text-on-primary text-xs font-bold hover:bg-primary/90 transition shadow-xs">
                                    Reset Semua Filter
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($messages->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low">
            {{ $messages->links() }}
        </div>
        @endif
    </div>

    {{-- Modal Balas Pesan (Reply Modal) --}}
    <div x-show="replyModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="replyModalOpen = false">
        
        <div class="bg-surface dark:bg-[#111827] border border-outline-variant rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden text-on-surface"
             @click.outside="replyModalOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between bg-surface-container-low">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[22px]">reply</span>
                    <h3 class="font-bold text-base text-on-surface">Balas Pesan Pengunjung</h3>
                </div>
                <button type="button" @click="replyModalOpen = false" class="p-1 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            {{-- Form Kirim Balasan --}}
            <form :action="replyData.url" method="POST" class="p-6 space-y-4">
                @csrf

                {{-- Penerima --}}
                <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/60 text-xs flex items-start justify-between gap-3">
                    <div>
                        <div class="text-on-surface-variant">Kirim balasan ke:</div>
                        <div class="font-bold text-on-surface text-sm mt-0.5" x-text="replyData.name + ' <' + replyData.email + '>'"></div>
                    </div>
                    {{-- Opsi Buka Mail Client --}}
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a :href="'mailto:' + replyData.email + '?subject=' + encodeURIComponent(replyData.subject)" 
                           target="_blank" 
                           class="px-2.5 py-1 rounded-md bg-surface-variant hover:bg-primary/20 text-primary text-[11px] font-bold transition flex items-center gap-1" 
                           title="Buka di aplikasi email Anda (Gmail / Outlook)">
                            <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                            Mail Client
                        </a>
                        <template x-if="cleanPhone">
                            <a :href="'https://wa.me/' + cleanPhone + '?text=' + encodeURIComponent('Halo ' + replyData.name + ', menanggapi pesan Anda: ')" 
                               target="_blank" 
                               class="px-2.5 py-1 rounded-md bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 text-[11px] font-bold transition flex items-center gap-1" 
                               title="Balas via WhatsApp">
                                <span class="material-symbols-outlined text-[13px]">chat</span>
                                WhatsApp
                            </a>
                        </template>
                    </div>
                </div>

                {{-- Cuplikan Pesan Asli --}}
                <div class="p-3 rounded-xl bg-surface-container-lowest border border-outline-variant/50 text-xs">
                    <div class="text-on-surface-variant font-semibold mb-1">Pesan dari pengunjung:</div>
                    <div class="text-on-surface/80 italic whitespace-pre-wrap max-h-24 overflow-y-auto font-body-sm" x-text="replyData.originalMessage"></div>
                </div>

                {{-- Subjek Balasan --}}
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1">Subjek Email</label>
                    <input type="text" 
                           name="reply_subject" 
                           x-model="replyData.subject" 
                           required 
                           class="w-full px-3 py-2 text-sm rounded-lg bg-surface-container-low border border-outline-variant text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>

                {{-- Isi Pesan Balasan --}}
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1">Isi Pesan Balasan</label>
                    <textarea name="reply_message" 
                              rows="5" 
                              required 
                              placeholder="Tuliskan jawaban atau pesan balasan Anda di sini..."
                              class="w-full px-3 py-2 text-sm rounded-lg bg-surface-container-low border border-outline-variant text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
                </div>

                {{-- Footer Actions --}}
                <div class="flex items-center justify-end gap-2 pt-2 border-t border-outline-variant">
                    <button type="button" @click="replyModalOpen = false" class="px-4 py-2 rounded-lg border border-outline-variant text-on-surface hover:bg-surface-variant text-xs font-bold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-primary text-on-primary text-xs font-bold hover:bg-primary/90 transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                        Kirim Balasan Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
