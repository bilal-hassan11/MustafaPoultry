import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/admin_assets/libs/flatpickr/flatpickr.min.css",
                "resources/admin_assets/css/bootstrap.min.css",
                "resources/admin_assets/css/app.min.css",
                "resources/admin_assets/css/icons.min.css",
                // 'resources/admin_assets/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
