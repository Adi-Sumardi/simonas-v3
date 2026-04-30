import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import ReactDOMServer from 'react-dom/server';

createServer((page) =>
    createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,
        title: (title) => (title ? `${title} — SIMONAS` : 'SIMONAS'),
        resolve: (name) =>
            // @ts-ignore
            import(`./Pages/${name}.tsx`),
        setup: ({ App, props }) => <App {...props} />,
    }),
);
