import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import { resolve } from 'node:path'

const root = process.cwd()
// `mode` is always 'production' for `vite build`, watch or not — it does not
// reflect the `--watch` flag, so we check argv directly instead.
const isWatch = process.argv.includes('--watch') || process.argv.includes('-w')

export default defineConfig({
  base: '/assets/',
  plugins: [tailwindcss()],
  build: {
    watch: isWatch ? {
      include: [
        resolve(root, 'src/**'),
        resolve(root, 'site/templates/**/*.php'),
        resolve(root, 'site/snippets/**/*.php'),
      ],
    } : null,
    outDir: 'assets',
    emptyOutDir: false,
    rollupOptions: {
      input: 'src/main.js',
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: '[ext]/[name].[ext]',
      },
    },
  },
})
