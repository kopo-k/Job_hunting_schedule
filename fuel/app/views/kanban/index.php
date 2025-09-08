<!doctype html>
<meta charset="utf-8">
<title>就活スケジューラー</title>
<link rel="stylesheet" href="/assets/css/app.css">

<header>
  <div class="brand brand--stack">
    <!-- 青いタイル -->
    <div class="brand-mark" aria-hidden="true">
      <!-- Lucideのchart-column相当（stroke=currentColor なのでCSSで白に） -->
      <svg class="brand-glyph" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
           fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 3v16a2 2 0 0 0 2 2h16"/>
        <path d="M18 17V9"/>
        <path d="M13 17V5"/>
        <path d="M8 17v-3"/>
      </svg>
    </div>

    <!-- タイトル群 -->
    <div class="brand-text">
      <div class="brand-ja">
        <span class="brand-ja-1">就活</span><br>
        <span class="brand-ja-2">スケジューラー</span>
      </div>
      <div class="brand-en">Job Hunting Scheduler</div>
    </div>
  </div>

  <div class="sub">新規登録</div>
</header>

<main>
  <div class="section-header">
    <div class="section-title">
      <h2>応募進捗管理</h2>
      <p class="page-sub">カンバンボードで応募状況を管理します<br>ドラッグで位置変更</p>
    </div>
    <div class="toolbar">
      <button data-bind="click: openAdd">＋ 応募を追加</button>
    </div>
  </div>

<!-- モーダル -->
  <div class="modal-backdrop" data-bind="visible: showAdd" style="display:none"></div>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle"
      data-bind="visible: showAdd" style="display:none">
    <div class="modal__header">
      <h3 id="addTitle">新しい応募を追加</h3>
      <button class="iconbtn" aria-label="閉じる" data-bind="click: closeAdd">✕</button>
    </div>

    <p class="modal__lead">応募の詳細情報を入力してください。</p>

    <form class="modal__body" data-bind="submit: submitAdd">
      <div class="modal__panel">
        <label class="field">
          <span>企業名 *</span>
          <input type="text" data-bind="value: add.name" placeholder="例: 株式会社テスト" required>
        </label>

        <div class="grid">
          <label class="field">
            <span>追加先ステータス *</span>
            <select data-bind="options: statusOptions,
                              optionsText: 'label',
                              optionsValue: 'key',
                              value: add.status_key"></select>
          </label>
          <label class="field">
            <span>職種</span>
            <input type="text" data-bind="value: add.position_title" placeholder="例: フロントエンド">
          </label>
        </div>

        <label class="field">
          <span>求人URL</span>
          <input type="url" data-bind="value: add.job_url" placeholder="https://example.com">
        </label>

        <label class="field field--employment">
          <span>雇用形態</span>
          <select data-bind="value: add.employment_type">
            <option value=""></option>
            <option>正社員</option>
            <option>契約社員</option>
            <option>インターン</option>
            <option>アルバイト</option>
          </select>
        </label>

        <label class="field">
          <span>勤務地</span>
          <input type="text" data-bind="value: add.location_text" placeholder="例: 東京本社 / オンライン">
        </label>

        <label class="field">
          <span>メモ</span>
          <textarea rows="3" data-bind="value: add.memo" placeholder="準備事項や注意点など"></textarea>
        </label>

        <div class="modal__footer">
          <button type="button" class="btn--ghost" data-bind="click: closeAdd">キャンセル</button>
          <button type="submit" class="btn--primary" data-bind="enable: add.name">追加</button>
        </div>
      </div>
    </form>
  </div>

</main>

<!-- DOM生成後にバインドするため、ページの最後に配置 -->
<script src="/assets/js/knockout-3.5.1.js"></script>
<script src="/assets/js/app.js"></script>