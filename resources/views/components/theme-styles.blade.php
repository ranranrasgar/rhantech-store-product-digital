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
        --theme-primary: 6 182 212;
        --theme-on-primary-container: 6 182 212;
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
        --theme-primary: 6 182 212;
        --theme-on-primary-container: 6 182 212;
        --theme-outline: 48 54 61;
        --theme-outline-variant: 48 54 61;
    }

    html,
    body {
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
    }

    [data-theme-toggle] svg {
        width: 20px !important;
        height: 20px !important;
        max-width: 20px !important;
        max-height: 20px !important;
        flex-shrink: 0 !important;
    }

    @media (prefers-reduced-motion: reduce) {
        html,
        body {
            transition: none;
            scroll-behavior: auto;
        }
    }
</style>
