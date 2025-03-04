import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

// Cargar dinámicamente el plugin laravel-vite-plugin
export default defineConfig(async () => {
    const laravel = (await import('laravel-vite-plugin')).default;

    return {
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                ],
                refresh: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        includeAbsolute: false,  // Mantén esta configuración si es necesaria
                    }
                }
            })
        ],
        css: {
            preprocessorOptions: {
                scss: {}
            }
        },
        build: {
            chunkSizeWarningLimit: 1000,
            rollupOptions: {
                output: {
                    manualChunks(id) {
                        if (id.includes('node_modules')) {
                            return 'vendor';  // Agrupa todos los módulos de node_modules
                        }
                    }
                }
            }
        }
    };
});