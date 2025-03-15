import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [vue()],
    resolve: {
        alias: {
            '@domain': path.resolve(__dirname, './'),
            '@domainComponents': path.resolve(__dirname, './src/components'),
            '@domainModels': path.resolve(__dirname, './src/models'),
            '@domainPages': path.resolve(__dirname, './src/pages'),
            '@domainStore': path.resolve(__dirname, './src/store'),
        },
    },
});
