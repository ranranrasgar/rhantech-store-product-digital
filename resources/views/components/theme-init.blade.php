<script>
    (() => {
        const storageKey = 'rhantech-theme';
        let savedTheme = null;

        try {
            savedTheme = localStorage.getItem(storageKey);
        } catch (error) {
            // Storage can be unavailable in privacy-restricted browsers.
        }

        const theme = ['light', 'dark'].includes(savedTheme)
            ? savedTheme
            : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

        document.documentElement.classList.toggle('dark', theme === 'dark');
        document.documentElement.dataset.theme = theme;
        document.documentElement.style.colorScheme = theme;
    })();
</script>
