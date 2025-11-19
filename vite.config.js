import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  server: {
    host: '0.0.0.0',           // oder 'lager1.local'
    port: 5173,
    cors: true,                 // CORS aktivieren
    hmr: {
      host: 'lager2.local',     // Hostname, unter dem dein Browser zugreift
    },
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
})
