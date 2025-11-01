import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [laravel({
    input: ['resources/css/app.css', 'resources/js/app.js'],
    refresh: true,
  })],
  build: {
    cssMinify: 'esbuild', // <- évite LightningCSS (responsable du OOM)
    // minify: 'esbuild',  // (optionnel) force aussi le JS via esbuild
    // sourcemap: false,   // (optionnel) réduit la RAM
  },
})
