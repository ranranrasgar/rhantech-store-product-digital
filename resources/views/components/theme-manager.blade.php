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
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.dataset.theme = theme;
            document.documentElement.style.colorScheme = theme;

            if (persist) {
                try {
                    localStorage.setItem(storageKey, theme);
                } catch (error) {
                    // The selected theme still applies for the current page.
                }
            }

            updateControls(theme);
        };

        const initialise = () => {
            const initialTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            updateControls(initialTheme);

            document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                    applyTheme(nextTheme, true);
                });
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initialise, { once: true });
        }
        document.addEventListener('livewire:navigated', () => {
            const savedTheme = localStorage.getItem(storageKey);
            const theme = ['light', 'dark'].includes(savedTheme) ? savedTheme : (mediaQuery.matches ? 'dark' : 'light');
            applyTheme(theme);
            initialise();
        });
        initialise();

        mediaQuery.addEventListener('change', (event) => {
            if (!getStoredTheme()) applyTheme(event.matches ? 'dark' : 'light');
        });

        window.addEventListener('storage', (event) => {
            if (event.key === storageKey && ['light', 'dark'].includes(event.newValue)) {
                applyTheme(event.newValue);
            }
        });
    })();
</script>
