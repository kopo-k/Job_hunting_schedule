// vite.config.js
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  root: '.',
  publicDir: false,                 // ← これで public の自動コピーを止める
  build: {
    outDir: 'public/assets/js',     // 生成先は今のまま
    emptyOutDir: false,             // 既存(KOのvendor等)は消さない
    rollupOptions: {
      input: 'src/react/main.jsx',
      output: { entryFileNames: 'react-bundle.js' }
    }
  }
});
