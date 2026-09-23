<button
    type="button"
    data-theme-toggle
    aria-label="Switch to dark mode"
    title="Switch to dark mode"
    style="width: 36px; height: 36px; min-width: 36px; min-height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; padding: 0; cursor: pointer;"
    {{ $attributes->merge(['class' => 'inline-flex h-9 w-9 items-center justify-center rounded-full border border-outline-variant bg-surface-container-low text-on-surface-variant transition-colors hover:bg-surface-container-high hover:text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background']) }}
>
    <svg data-theme-icon="moon" width="20" height="20" style="width:20px; height:20px; max-width:20px; max-height:20px; flex-shrink:0; display:block;" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/>
    </svg>
    <svg data-theme-icon="sun" width="20" height="20" style="display:none; width:20px; height:20px; max-width:20px; max-height:20px; flex-shrink:0;" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="12" cy="12" r="4"/>
        <path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/>
    </svg>
    <span class="sr-only" data-theme-label style="display:none;">Switch to dark mode</span>
</button>
