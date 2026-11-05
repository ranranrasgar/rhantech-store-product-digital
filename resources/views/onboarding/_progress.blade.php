{{-- Bar langkah. $step: langkah aktif, $total: ekspresi Alpine atau angka jumlah langkah. --}}
<div class="mb-5" x-data="{ total: {{ $total }} }" x-effect="total = {{ $total }}">
    <div class="flex gap-1.5" role="progressbar" aria-valuemin="1" :aria-valuemax="total" aria-valuenow="{{ $step }}" aria-label="Langkah pendaftaran">
        <template x-for="i in total" :key="i">
            <span class="h-1.5 flex-1 rounded-full transition-colors duration-300" :class="i <= {{ $step }} ? 'bg-brand' : 'bg-slate-200 dark:bg-slate-700'"></span>
        </template>
    </div>
    <p class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
        Langkah {{ $step }} dari <span x-text="total"></span>
    </p>
</div>
