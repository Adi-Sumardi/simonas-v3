import '../css/app.css';
import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot, hydrateRoot } from 'react-dom/client';

// Auto-reload on 419 (CSRF expired). Guard against infinite loop:
// if a reload already happened within the last 5s, go to login instead.
router.on('invalid', (e) => {
    if (e.detail.response.status === 419) {
        e.preventDefault();
        const last = parseInt(sessionStorage.getItem('_csrf_reload') ?? '0');
        if (Date.now() - last > 5000) {
            sessionStorage.setItem('_csrf_reload', String(Date.now()));
            window.location.reload();
        } else {
            sessionStorage.removeItem('_csrf_reload');
            window.location.href = '/login';
        }
    }
});

const appName = import.meta.env.VITE_APP_NAME ?? 'SIMONAS';

createInertiaApp({
    title: (title) => (title ? `${title} — ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.tsx`,
            import.meta.glob('./Pages/**/*.tsx'),
        ),
    setup({ el, App, props }) {
        if (import.meta.env.SSR) {
            hydrateRoot(el, <App {...props} />);
        } else {
            createRoot(el).render(<App {...props} />);
        }
        // Fade out splash screen after React has painted the first frame
        requestAnimationFrame(() => requestAnimationFrame(() => {
            const splash = document.getElementById('app-splash');
            if (!splash) return;
            splash.classList.add('fade-out');
            setTimeout(() => splash.remove(), 380);
        }));
    },
    progress: {
        color: '#2563eb',
    },
});
