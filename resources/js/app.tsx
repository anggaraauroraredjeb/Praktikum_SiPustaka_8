import '../css/app.css';
import { createInertiaApp } from '@inertiajs/react';
import type { ComponentType } from 'react';
import { createRoot } from 'react-dom/client';

const pages = import.meta.glob<{ default: ComponentType }>([
    './Pages/**/*.tsx',
    '../../Modules/**/resources/js/Pages/**/*.tsx',
]);

createInertiaApp({
    title: (title) => title ? `${title} · SiPustaka` : 'SiPustaka',
    resolve: async (name) => {
        const modulePage = name.replace('/', '/resources/js/Pages/');
        const candidates = [`./Pages/${name}.tsx`, `../../Modules/${modulePage}.tsx`];
        const pagePath = candidates.find((candidate) => candidate in pages);

        if (!pagePath) {
            throw new Error(`Halaman Inertia "${name}" tidak ditemukan.`);
        }

        return (await pages[pagePath]()).default;
    },
    setup: (options) => {
        if (!options.el) return;
        const App = options.App;
        createRoot(options.el).render(<App {...options.props} />);
    },
});