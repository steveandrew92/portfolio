import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

const BUILD_DIR = 'build';

function vitePublicBase(mode, env) {
    if (mode === 'development') {
        return '/';
    }
    const raw = (env.ASSET_URL || env.APP_URL || '').trim();
    if (!raw) {
        return `/${BUILD_DIR}/`;
    }
    try {
        const pathname = new URL(raw).pathname.replace(/\/$/, '');
        const prefix = pathname || '';
        return `${prefix}/${BUILD_DIR}/`.replace(/\/{2,}/g, '/').replace(/^\/{2}/, '/');
    } catch {
        return `/${BUILD_DIR}/`;
    }
}

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    return {
        base: vitePublicBase(mode, env),
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
                fonts: [
                    bunny('Plus Jakarta Sans', {
                        weights: [400, 500, 600, 700],
                    }),
                ],
            }),
            tailwindcss(),
        ],
        server: {
            watch: {
                ignored: ['**/storage/framework/views/**'],
            },
        },
    };
});
