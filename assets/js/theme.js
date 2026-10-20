//===================================
//   Dark / light mode (Bootstrap 5.3 color modes)
//   Loaded in <head> so the theme is applied before the page is painted.
//   Uses the visitor's saved choice, otherwise follows the OS setting.
(function () {
    'use strict';

    const STORAGE_KEY = 'theme';
    const systemDark  = window.matchMedia('(prefers-color-scheme: dark)');

    function savedTheme() {
        try {
            const theme = localStorage.getItem(STORAGE_KEY);
            return theme === 'light' || theme === 'dark' ? theme : null;
        } catch (e) {
            return null;    // storage blocked (private mode etc.)
        }
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);
    }

    applyTheme(savedTheme() || (systemDark.matches ? 'dark' : 'light'));

    // Follow OS changes until the visitor picks a theme themselves
    systemDark.addEventListener('change', event => {
        if (!savedTheme()) {
            applyTheme(event.matches ? 'dark' : 'light');
        }
    });

    // Any element with [data-theme-toggle] switches the theme
    document.addEventListener('click', event => {
        if (!event.target.closest('[data-theme-toggle]')) {
            return;
        }
        const next = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        applyTheme(next);
        try {
            localStorage.setItem(STORAGE_KEY, next);
        } catch (e) {
            // not persisted, but the switch still works for this page
        }
    });
})();
