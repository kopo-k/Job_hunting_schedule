<!doctype html>
<meta charset="utf-8">
<title>ログイン</title>
<link rel="stylesheet" href="/assets/css/app.css">
<link rel="stylesheet" href="/assets/css/login.css">

<main class="main-container">
  <div class="card">
    <h2 class="title">ログイン</h2>
    <p class="subtitle">アカウントにログインしてください</p>

    <form method="post" action="/auth/login" class="login-form">
      <!-- CSRF保護（要件13：セキュリティ） -->
      <?= \Form::csrf(); ?>
      
      <!-- メール -->
      <label class="field">
        <div class="field__label-row">
          <svg class="label-icon" viewBox="0 0 24 24" aria-hidden="true">
            <rect x="3" y="5" width="18" height="14" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"></rect>
            <path d="M3 7l9 6 9-6" fill="none" stroke="currentColor" stroke-width="2"></path>
          </svg>
          <span class="field__label">メールアドレス</span>
        </div>
        <div class="input-wrap">
          <input class="input input--soft" type="email" name="email" placeholder="example@email.com" required>
        </div>
      </label>

      <!-- パスワード -->
      <label class="field">
        <div class="field__label-row">
          <svg class="label-icon" viewBox="0 0 24 24" aria-hidden="true">
            <rect x="3" y="11" width="18" height="10" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4" fill="none" stroke="currentColor" stroke-width="2"></path>
          </svg>
          <span class="field__label">パスワード</span>
        </div>
        <div class="input-wrap has-toggle">
          <input class="input input--soft" id="login_password" type="password" name="password" placeholder="パスワードを入力" required>

          <!-- 右の目アイコン（eye と eye-off を重ねておき、CSSで片方だけ表示） -->
          <button type="button"
                  class="icon-right-btn"
                  aria-label="パスワードを表示"
                  aria-pressed="false"
                  data-toggle="#login_password">
            <!-- eye（初期表示） -->
            <svg class="icon-right icon-eye" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z" fill="none" stroke="currentColor" stroke-width="2"/>
              <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
            <!-- eye-off（初期はCSSで非表示） -->
            <svg class="icon-right icon-eye-off" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19c-7 0-11-7-11-7a21.77 21.77 0 0 1 5.06-5.94" fill="none" stroke="currentColor" stroke-width="2"/>
              <path d="M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a21.8 21.8 0 0 1-3.17 4.49" fill="none" stroke="currentColor" stroke-width="2"/>
              <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="2"/>
            </svg>
          </button>
        </div>
      </label>

      <div class="form-actions">
        <button class="btn--primary btn-full btn--xl" type="submit">ログイン</button>
      </div>

      <p class="login-link">
        アカウントをお持ちでない方は <a href="/signup">新規登録</a>
      </p>
    </form>
  </div>
</main>

<script src="/assets/js/login.js" defer></script>
