import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/js/app.js",
        "resources/css/pharmacy-map.css",
        "resources/js/pharmacy-map.js",
      ],
      ssr: "resources/js/ssr.js",
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  ssr: {
  },
});