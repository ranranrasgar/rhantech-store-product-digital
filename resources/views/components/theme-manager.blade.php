<script>
    (() => {
        const storageKey = 'rhantech-theme';
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

        const getStoredTheme = () => {
            try {
                const theme = localStorage.getItem(storageKey);
                return ['light', 'dark'].includes(theme) ? theme : null;
            } catch (error) {
                return null;
            }
        };

        const updateControls = (theme) => {
            const isDark = theme === 'dark';
            const actionLabel = isDark ? 'Switch to light mode' : 'Switch to dark mode';

            document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                button.setAttribute('aria-label', actionLabel);
                button.setAttribute('title', actionLabel);
                const moon = button.querySelector('[data-theme-icon="moon"]');
                const sun = button.querySelector('[data-theme-icon="sun"]');
                if (moon) {
                    moon.classList.toggle('hidden', isDark);
                    moon.style.display = isDark ? 'none' : 'block';
                }
                if (sun) {
                    sun.classList.toggle('hidden', !isDark);
                    sun.style.display = !isDark ? 'none' : 'block';
                }

                const label = button.querySelector('[data-theme-label]');
                if (label) {
                    label.textContent = actionLabel;
                    label.style.display = 'none';
                }
            });
        };

        const applyTheme = (theme, persist = false) => {
            const isDark = theme === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.dataset.theme = theme;
            document.documentElement.style.colorScheme = theme;

            if (persist) {
                try {
                    localStorage.setItem(storageKey, theme);
                } catch (error) {
                    // Storage can be unavailable in privacy-restricted browsers.
                }
            }

            updateControls(theme);
        };

        const syncTheme = () => {
            const savedTheme = getStoredTheme();
            const theme = savedTheme ? savedTheme : (document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            applyTheme(theme, false);
        };

        // Attach a single delegation click listener on document to prevent duplicate listener execution
        if (!window.__themeToggleDelegationAttached) {
            window.__themeToggleDelegationAttached = true;
            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-theme-toggle]');
                if (!button) return;

                event.preventDefault();
                event.stopPropagation();

                const isCurrentlyDark = document.documentElement.classList.contains('dark');
                const nextTheme = isCurrentlyDark ? 'light' : 'dark';
                applyTheme(nextTheme, true);
            });
        }

        // Initialize theme UI state
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', syncTheme, { once: true });
        } else {
            syncTheme();
        }

        document.addEventListener('livewire:navigated', () => {
            syncTheme();
        });

        window.addEventListener('storage', (event) => {
            if (event.key === storageKey && ['light', 'dark'].includes(event.newValue)) {
                applyTheme(event.newValue, false);
            }
        });
    })();
</script>
