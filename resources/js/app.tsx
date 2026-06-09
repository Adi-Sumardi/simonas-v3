import '../css/app.css';
import { createInertiaApp, router } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot, hydrateRoot } from 'react-dom/client';

// Auto-reload on 419 (CSRF token expired) without logging out
router.on('invalid', (e) => {
    if (e.detail.response.status === 419) {
        e.preventDefault();
        window.location.reload();
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
    },
    progress: {
        color: '#2563eb',
    },
});
