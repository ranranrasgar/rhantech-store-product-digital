<style>
    :root {
        color-scheme: light;
        --theme-background: 255 255 255;
        --theme-surface: 255 255 255;
        --theme-surface-lowest: 255 255 255;
        --theme-surface-low: 246 248 250;
        --theme-surface-container: 246 248 250;
        --theme-surface-high: 234 238 242;
        --theme-surface-highest: 234 238 242;
        --theme-surface-variant: 234 238 242;
        --theme-on-background: 31 35 40;
        --theme-on-surface: 31 35 40;
        --theme-on-surface-variant: 101 109 118;
        --theme-primary: 14 165 233;
        --theme-on-primary-container: 14 165 233;
        --theme-outline: 208 215 222;
        --theme-outline-variant: 208 215 222;
    }

    html.dark {
        color-scheme: dark;
        --theme-background: 13 17 23;
        --theme-surface: 13 17 23;
        --theme-surface-lowest: 13 17 23;
        --theme-surface-low: 22 27 34;
        --theme-surface-container: 22 27 34;
        --theme-surface-high: 33 38 45;
        --theme-surface-highest: 33 38 45;
        --theme-surface-variant: 48 54 61;
        --theme-on-background: 230 237 243;
        --theme-on-surface: 230 237 243;
        --theme-on-surface-variant: 139 148 158;
        --theme-primary: 14 165 233;
        --theme-on-primary-container: 14 165 233;
        --theme-outline: 48 54 61;
        --theme-outline-variant: 48 54 61;
    }

    html,
    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        transition: background-color 180ms ease, color 180ms ease;
    }

    [x-cloak] {
        display: none !important;
    }

    html {
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
    }

    /* Prevent accidental horizontal page blowout on mobile without breaking vertical scroll */
    html, body {
        max-width: 100%;
        overflow-x: clip;
    }

    /* Critical Utility Fallbacks - Prevents FOUC before Tailwind CDN initializes */
    [hidden] {
        display: none !important;
    }

    .hidden:not([class*="md:"]):not([class*="lg:"]):not([class*="sm:"]) {
        display: none;
    }

    /* Responsive utility fallbacks - prevents desktop/mobile component duplication before Tailwind JS loads */
    @media (min-width: 768px) {
        .md\:hidden, [class*="md:hidden"] {
            display: none !important;
        }
    }
    @media (max-width: 767px) {
        .hidden.md\:flex, [class~="hidden"][class*="md:flex"],
        .hidden.md\:block, [class~="hidden"][class*="md:block"],
        .hidden.md\:grid, [class~="hidden"][class*="md:grid"] {
            display: none !important;
        }
    }

    .sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border-width: 0 !important;
    }

    [data-theme-toggle] {
        width: 36px !important;
        height: 36px !important;
        min-width: 36px !important;
        max-width: 36px !important;
        border-radius: 9999px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
        cursor: pointer !important;
        border: 1px solid rgba(203, 213, 225, 0.8) !important;
        background-color: #ffffff !important;
        color: #334155 !important;
        transition: all 180ms ease !important;
    }

    [data-theme-toggle]:hover {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
    }

    html.dark [data-theme-toggle] {
        border-color: rgba(51, 65, 85, 0.8) !important;
        background-color: #161b22 !important;
        color: #cbd5e1 !important;
    }

    html.dark [data-theme-toggle]:hover {
        background-color: #21262d !important;
        color: #ffffff !important;
    }

    [data-theme-toggle] svg {
        width: 18px !important;
        height: 18px !important;
        max-width: 18px !important;
        max-height: 18px !important;
        flex-shrink: 0 !important;
        pointer-events: none !important;
    }

    @media (prefers-reduced-motion: reduce) {
        html,
        body {
            transition: none;
            scroll-behavior: auto;
        }
    }

    /* Google Material Symbols Font & Robust Ligature Styling */
    @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200');

    .material-symbols-outlined {
        font-family: 'Material Symbols Outlined' !important;
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
        vertical-align: middle;
    }

    /* Hilangkan semua degradasi & shadow blur di object apapun */
    [class*="shadow"]:not([class*="ring"]) {
        --tw-shadow: 0 0 #0000 !important;
        --tw-shadow-colored: 0 0 #0000 !important;
        box-shadow: none !important;
    }
    .shadow, .shadow-xs, .shadow-none, .shadow-none, .shadow-none, .shadow-none, .shadow-none, .shadow-2xs {
        box-shadow: none !important;
    }
    [class*="drop-shadow"] {
        filter: none !important;
    }
</style>
