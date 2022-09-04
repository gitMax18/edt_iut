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
        //     "http://localhost:3000": {
        //         target: "http://localhost:8000",
        //         changeOrigin: true,
        //         secure: false,
        //         rewrite: (path) => path.replace("http://localhost:3000", ""),
        //     },
        // },
        cors : false
    },
});
