<!doctype html>
<meta charset="utf-8">
<title>新規登録</title>
<link rel="stylesheet" href="/assets/css/app.css">
<link rel="stylesheet" href="/assets/css/signup.css">

<main class="main-container">
  <div class="card">
    <h2 class="title">新規登録</h2>
    <p class="subtitle">新しいアカウントを作成してください</p>

    <form method="post" action="/auth/signup" class="signup-form">
      <!-- CSRF保護（要件13：セキュリティ） -->
      <?= \Form::csrf(); ?>
      
      <!-- お名前 -->
      <label class="field">
        <div class="field__label-row">
          <i class="label-icon" data-lucide="user" aria-hidden="true"></i>
          <span class="field__label">お名前</span>
        </div>
        <div class="input-wrap">
          <input class="input" type="text" name="name" placeholder="山田太郎" required>
        </div>
      </label>

      <!-- メール -->
      <label class="field">
        <div class="field__label-row">
          <i class="label-icon" data-lucide="mail" aria-hidden="true"></i>
          <span class="field__label">メールアドレス</span>
        </div>
        <div class="input-wrap">
          <input class="input" type="email" name="email" placeholder="example@email.com" required>
        </div>
      </label>

      <!-- パスワード -->
      <label class="field">
        <div class="field__label-row">
          <i class="label-icon" data-lucide="lock" aria-hidden="true"></i>
          <span class="field__label">パスワード</span>
        </div>
        <div class="input-wrap has-toggle">
          <input class="input" id="password" type="password" name="password" placeholder="パスワードを入力" required>
          <button type="button" class="icon-right-btn" aria-label="パスワードを表示" data-toggle="#password">
            <i class="icon-right" data-lucide="eye" aria-hidden="true"></i>
          </button>
        </div>
      </label>

      <!-- パスワード確認 -->
      <label class="field">
        <div class="field__label-row">
          <i class="label-icon" data-lucide="lock" aria-hidden="true"></i>
          <span class="field__label">パスワード確認</span>
        </div>
        <div class="input-wrap has-toggle">
          <input class="input" id="password_confirm" type="password" name="password_confirmation" placeholder="パスワードを再入力" required>
          <button type="button" class="icon-right-btn" aria-label="パスワードを表示" data-toggle="#password_confirm">
            <i class="icon-right" data-lucide="eye" aria-hidden="true"></i>
          </button>
        </div>
      </label>

      <div class="form-actions">
        <button class="btn--primary btn-full" type="submit">アカウント作成</button>
      </div>

      <p class="login-link">
        既にアカウントをお持ちの方は <a href="/login">ログイン</a>
      </p>
    </form>
  </div>
</main>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
<script src="/assets/js/signup.js" defer></script>
