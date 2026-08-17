import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    esbuild: {
        jsx: 'automatic',
    },
    resolve: {
        alias: {
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('xlsx')) {
                            return 'vendor-xlsx';
                        }
                        if (
                            id.includes('chart.js') ||
                            id.includes('react-chartjs-2') ||
                            id.includes('apexcharts') ||
                            id.includes('react-apexcharts')
                        ) {
                            return 'vendor-charts';
                        }
                        if (id.includes('lucide-react') || id.includes('@iconify')) {
                            return 'vendor-icons';
                        }
                        if (id.includes('@radix-ui')) {
                            return 'vendor-radix';
                        }
                    }
                },
            },
        },
    },
});
