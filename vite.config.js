import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: {
    host: true, // penting! biar bisa diakses dari luar
    hmr: {
      host: 'https://d9b1-114-122-108-216.ngrok-free.app/', // GANTI dengan domain ngrok kamu
    },
  },
  plugins: [
    laravel([
      'resources/css/app.css',
      'resources/js/app.js',
    ]),
  ],
});
