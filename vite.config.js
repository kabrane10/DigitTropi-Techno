// 
import { VitePWA } from 'vite-plugin-pwa';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/notification.js'],
            refresh: true,
        }),

        VitePWA({
            registerType: 'autoUpdate',
            manifest: false,
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
                runtimeCaching: [
                    {
                        urlPattern: /^https:\/\/api\./,
                        handler: 'NetworkFirst',
                        options: { cacheName: 'api-cache' }
                    }
                ]
            }
        })
    ],
});