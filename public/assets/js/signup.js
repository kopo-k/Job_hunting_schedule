// グローバルを汚さない初期化をするため
(() => {
  const BTN_SEL  = '.icon-right-btn';
  const ATTR_TGT = 'data-toggle';
  const CLASS_ON = 'is-on';

  // Lucideの準備が整うまで待つ（createIcons と icons が揃うのを確認）
  function waitForLucide(timeoutMs = 5000) {
    return new Promise((resolve, reject) => {
      // 経過時間を測るため
      const t0 = Date.now();
      (function tick() {
        const ready = !!(window.lucide &&
                         typeof lucide.createIcons === 'function' &&
                         lucide.icons && Object.keys(lucide.icons).length);

        if (ready) return resolve();
        if (Date.now() - t0 > timeoutMs) return reject(new Error('Lucide not ready'));
        requestAnimationFrame(tick);
      })();
    });
  }

  // ドキュメント全体の data-lucide を初期置換（icons を必ず渡す）
  function initLucide() {
    lucide.createIcons({ icons: lucide.icons });
  }

  // ボタン内のアイコンを置換：
  // 1) <i data-lucide="..."> を差し込む
  // 2) その場で createIcons({ icons }) を呼び、今挿した要素だけSVG化させる
  function setIcon(btn, name) {
    btn.innerHTML = `<i class="icon-right" data-lucide="${name}"></i>`;
    // 直近に挿した<i>のみを対象に最小限置換
    lucide.createIcons({ icons: lucide.icons });
  }

  function togglePassword(btn) {
    const sel = btn.getAttribute(ATTR_TGT);
    const input = document.querySelector(sel);
    if (!input) return;

    const show = input.type === 'password'; // 切替後に表示
    input.type = show ? 'text' : 'password';
    setIcon(btn, show ? 'eye-off' : 'eye');
    // パスワード表示中ならボタンを“オン”見た目に、非表示なら“オフ”見た目
    btn.classList.toggle(CLASS_ON, show);
  }

  function initToggles() {
    document.querySelectorAll(BTN_SEL).forEach((btn) => {
      // 初期は eye に統一しておく
      setIcon(btn, 'eye');
      btn.addEventListener('click', () => togglePassword(btn));
    });
  }

  // 確実にiconをロードするための初期化
  async function init() {
    try {
      await waitForLucide();
      initLucide();   // ラベル側などの data-lucide を一括置換
      initToggles();  // 右目トグルを有効化
    } catch (e) {
      console.error('[Lucide] 初期化に失敗しました:', e);
    }
  }

  if (document.readyState === 'loading') {
    //once: true により、呼び出し後に自動でリスナーが解除され多重実行を防ぐ
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    //すでに DOM が使える状態（interactive / complete）なら、すぐに初期化する
    init();
  }
})();
