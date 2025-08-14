import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

console.log(">>> VITE wird geladen für: ", __dirname);


export default defineConfig({
    server: {
        host: '0.0.0.0',

        hmr: {
            host: 'lager.local',
        },
        watch: {
            usePolling: true,
            interval: 250,                // 250–500 ms je nach System
            awaitWriteFinish: {
                stabilityThreshold: 200,
                pollInterval: 100,

            }
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.jsx',
            refresh: true,
        }),
        react(),
    ]


});
