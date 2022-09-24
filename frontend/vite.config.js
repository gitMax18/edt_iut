import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    plugins: [vue()],
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@import "@/assets/style/_variables.scss";`,
            },
        },
    },
    resolve: {
        alias: [
            {
                find: "@",
                replacement: path.resolve(__dirname, "src"),
            },
        ],
    },
    server: {
        // proxy: {
        //     "/api": {
        //         target: "*",
        //         changeOrigin: true,
        //         secure: true,
        //         // ws: true,
        //     },
        // },
        // cors: false,
    },
});
