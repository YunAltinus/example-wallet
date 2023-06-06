import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    server: {
        host: "0.0.0.0",
        hmr: {
            host: "localhost",
        },
        watch: {
            // https://vitejs.dev/config/server-options.html#server-watch
            usePolling: true,
        },
    },
    plugins: [
        vue(),
        laravel({
            input: ["resources/css/app.css", "resources/css/effect.css", "resources/js/src/app.js"],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "@": "/resources/js/src",
            "@assets": path.resolve(__dirname, "public"),
            "@assets/img/flag": path.resolve(__dirname, "public/img/flag"),
        },
    },
});
