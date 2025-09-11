<!doctype html>
<meta charset="utf-8">
<title>就活スケジューラー</title>
<meta name="csrf-token" content="<?= \Security::fetch_token(); ?>"><!-- CSRF -->
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

  <a class="sub" data-bind="click: goSignup">新規登録</a>
</header>

<main>
  <div class="section-header">
    <div class="section-title">
      <h2>応募進捗管理</h2>
      <p class="page-sub">カンバンボードで応募状況を管理します<br>ドラッグで位置変更</p>
    </div>
    <div class="toolbar">
      <button class="btn--primary" data-bind="click: openAdd">＋ 応募を追加</button>
    </div>
  </div>

  <!-- カンバンボード -->
   <!-- カンバンボードのヘッダー部分 -->
  <div class="kanban-board" data-bind="foreach: statusOptions">
    <div class="kanban-column">
      <div class="kanban-header" data-bind="style: { backgroundColor: $root.getColumnColor($data.key) }">
        <span data-bind="text: label"></span>
        <span class="count" data-bind="text: $root.getCountByStatus($data.key)"></span>
      </div>
      
      <div class="kanban-content sortable-container" data-bind="attr: { 'data-status': key }">
        <!-- ko foreach: $root.getCardsByStatus($data.key) -->
         <!-- 企業カード -->
        <div class="kanban-card" data-bind="attr: { 'data-id': id }">
          <div class="card-header">
            <div class="card-company" data-bind="text: name"></div>
            <div class="card-actions">
              <button class="card-edit-btn" data-bind="click: $root.openEdit" title="編集">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="m18 2 4 4-11 11H7v-4z"/>
                  <path d="m14.5 5.5-3 3"/>
                </svg>
              </button>
              <button class="card-delete-btn" data-bind="click: $root.deleteCard" title="削除">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="3,6 5,6 21,6"/>
                  <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"/>
                  <line x1="10" y1="11" x2="10" y2="17"/>
                  <line x1="14" y1="11" x2="14" y2="17"/>
                </svg>
              </button>
            </div>
          </div>
          
          <!-- ko if: position_title -->
          <div class="card-position" data-bind="text: position_title"></div>
          <!-- /ko -->
          
          <!-- ko if: employment_type -->
          <div class="card-employment" data-bind="text: employment_type"></div>
          <!-- /ko -->
          
          <!-- ko if: location_text -->
          <div class="card-location" data-bind="text: location_text"></div>
          <!-- /ko -->
          
          <!-- ko if: memo -->
          <div class="card-memo" data-bind="text: memo"></div>
          <!-- /ko -->
        </div>
        <!-- /ko -->
        
        <!-- ko if: $root.getCountByStatus($data.key)() === 0 -->
        <div class="empty-column">
          <p>まだ応募がありません</p>
        </div>
        <!-- /ko -->
      </div>
    </div>
  </div>

<!-- 応募追加モーダル -->
  <div class="modal-backdrop" data-bind="visible: showAdd" style="display:none"></div>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="addTitle"
      data-bind="visible: showAdd" style="display:none">
    <div class="modal__header">
      <h3 id="addTitle">新しい応募を追加</h3>
      <button class="iconbtn" aria-label="閉じる" data-bind="click: closeAdd">✕</button>
    </div>

    <p class="modal__lead">応募の詳細情報を入力してください。</p>

    <form class="modal__body">
      <div class="modal__panel">
        <label class="field">
          <span>企業名 *</span>
          <input type="text" data-bind="value: add.name" placeholder="例: 株式会社テスト" required>
        </label>

       
        <div class="grid">
        <label class="field">
          <span>追加先ステータス *</span>
            <select data-bind="
              options: statusOptions,
              optionsText: 'label',
              optionsValue: 'key',
              value: add.status_key
            ">
            </select>
        </label>

          <label class="field">
            <span>職種</span>
            <input type="text" data-bind="value: add.position_title" placeholder="例: フロントエンド">
          </label>
        </div>

        <label class="field">
          <span>求人URL</span>
          <input type="url" data-bind="value: add.job_url" placeholder="例: https://example.com">
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
          <button type="submit" class="btn--primary" data-bind="click: addCard, enable: add.name">追加</button>
        </div>
      </div>
    </form>
  </div>

  <!-- カード編集モーダル -->
  <div class="modal-backdrop" data-bind="visible: showEdit" style="display:none"></div>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="editTitle"
      data-bind="visible: showEdit" style="display:none">
    <div class="modal__header">
      <h3 id="editTitle">応募情報の編集</h3>
      <button class="iconbtn" aria-label="閉じる" data-bind="click: closeEdit">✕</button>
    </div>

    <p class="modal__lead">応募の詳細情報を編集してください。</p>

    <form class="modal__body">
      <div class="modal__panel">
        <label class="field">
          <span>企業名 *</span>
          <input type="text" data-bind="value: edit.name" placeholder="例: 株式会社テスト" required>
        </label>

        <div class="grid">
        <label class="field">
          <span>現在のステータス *</span>
            <select data-bind="
              options: statusOptions,
              optionsText: 'label',
              optionsValue: 'key',
              value: edit.status_key
            ">
            </select>
        </label>

          <label class="field">
            <span>職種</span>
            <input type="text" data-bind="value: edit.position_title" placeholder="例: フロントエンド">
          </label>
        </div>

        <label class="field">
          <span>求人URL</span>
          <input type="url" data-bind="value: edit.job_url" placeholder="例: https://example.com">
        </label>

        <label class="field field--employment">
          <span>雇用形態</span>
          <select data-bind="value: edit.employment_type">
            <option value=""></option>
            <option>正社員</option>
            <option>契約社員</option>
            <option>インターン</option>
            <option>アルバイト</option>
          </select>
        </label>

        <label class="field">
          <span>勤務地</span>
          <input type="text" data-bind="value: edit.location_text" placeholder="例: 東京本社 / オンライン">
        </label>

        <label class="field">
          <span>メモ</span>
          <textarea rows="3" data-bind="value: edit.memo" placeholder="準備事項や注意点など"></textarea>
        </label>

        <div class="modal__footer">
          <button type="button" class="btn--ghost" data-bind="click: closeEdit">キャンセル</button>
          <button type="submit" class="btn--primary" data-bind="click: saveEdit, enable: edit.name">保存</button>
        </div>
      </div>
    </form>
  </div>

</main>

<!-- DOM生成後にバインドするため、ページの最後に配置 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="/assets/js/knockout-3.5.1.js"></script>
<script src="/assets/js/app.js"></script>