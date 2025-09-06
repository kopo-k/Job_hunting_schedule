// vite.config.js
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  publicDir: false,                // ← public の自動コピーを止める
  build: {
    outDir: 'public/assets/react', // ← js 配下ではなく、react 用の専用ディレクトリへ
    emptyOutDir: true,
    rollupOptions: {
      input: 'src/react/main.jsx',
      output: { entryFileNames: 'react-bundle.js' }
    }
  }
});
