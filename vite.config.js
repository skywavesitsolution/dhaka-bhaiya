import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { VitePWA } from "vite-plugin-pwa";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        VitePWA({
            registerType: "autoUpdate",
            includeAssets: [
                "favicon.ico",
                "icons/icon-192.png",
                "icons/icon-512.png",
            ],
            manifest: {
                name: "Skywaves RMS Dashboard",
                short_name: "Skywaves RMS Dashboard",
                start_url: "/",
                display: "standalone",
                background_color: "#ffffff",
                theme_color: "#0d6efd",
                icons: [
                    {
                        src: "/icons/icon-192.png",
                        sizes: "192x192",
                        type: "image/png",
                    },
                    {
                        src: "/icons/icon-512.png",
                        sizes: "512x512",
                        type: "image/png",
                    },
                ],
            },
        }),
    ],
});
