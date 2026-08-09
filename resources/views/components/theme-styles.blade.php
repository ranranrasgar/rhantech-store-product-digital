<style>
    :root {
        color-scheme: light;
        --theme-background: 248 249 255;
        --theme-surface: 248 249 255;
        --theme-surface-lowest: 255 255 255;
        --theme-surface-low: 239 244 255;
        --theme-surface-container: 229 238 255;
        --theme-surface-high: 220 233 255;
        --theme-surface-highest: 211 228 254;
        --theme-surface-variant: 211 228 254;
        --theme-on-background: 11 28 48;
        --theme-on-surface: 11 28 48;
        --theme-on-surface-variant: 69 70 77;
        --theme-primary: 0 0 0;
        --theme-on-primary-container: 63 70 92;
        --theme-outline: 118 119 125;
        --theme-outline-variant: 198 198 205;
    }

    html.dark {
        color-scheme: dark;
        --theme-background: 8 15 27;
        --theme-surface: 11 20 35;
        --theme-surface-lowest: 6 12 22;
        --theme-surface-low: 15 27 45;
        --theme-surface-container: 20 34 54;
        --theme-surface-high: 27 43 66;
        --theme-surface-highest: 35 53 78;
        --theme-surface-variant: 40 58 82;
        --theme-on-background: 234 241 255;
        --theme-on-surface: 234 241 255;
        --theme-on-surface-variant: 190 202 220;
        --theme-primary: 234 241 255;
        --theme-on-primary-container: 218 226 253;
        --theme-outline: 145 158 177;
        --theme-outline-variant: 60 77 101;
    }

    html,
    body {
        transition: background-color 180ms ease, color 180ms ease;
    }

    @media (prefers-reduced-motion: reduce) {
        html,
        body {
            transition: none;
        }
    }
</style>
