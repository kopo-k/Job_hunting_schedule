// 右の“目”で パスワード表示/非表示 を切り替える（表示制御は CSS に一本化）
(() => {

  //確認用
  console.log(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

  const BTN_SEL  = '.icon-right-btn';
  const ATTR_TGT = 'data-toggle';
  const CLASS_ON = 'is-on';

  function togglePassword(btn) {
    const sel = btn.getAttribute(ATTR_TGT);
    const input = document.querySelector(sel);
    if (!input) return;

    const show = input.type === 'password';   // 押した後は「表示」？
    input.type = show ? 'text' : 'password';

    // 表示状態のクラスだけを切り替える（アイコンの表示/非表示はCSSが担当）
    btn.classList.toggle(CLASS_ON, show);
    btn.setAttribute('aria-pressed', String(show));
  }

  function init() {
    document.querySelectorAll(BTN_SEL).forEach((btn) => {
      btn.addEventListener('click', () => togglePassword(btn));

      // 初期同期（パスワード欄の type が text なら is-on を付与）
      const sel  = btn.getAttribute(ATTR_TGT);
      const inpt = document.querySelector(sel);
      const on   = inpt && inpt.type === 'text';
      btn.classList.toggle(CLASS_ON, on);
      btn.setAttribute('aria-pressed', String(on));
    });
  }

  document.readyState === 'loading'
    ? document.addEventListener('DOMContentLoaded', init, { once: true })
    : init();
})();
