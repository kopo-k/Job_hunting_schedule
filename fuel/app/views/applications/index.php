<!-- fuel/app/views/applications/index.php -->
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>応募一覧</title>
  <?php if (!empty($csrf_token)): ?>
    <meta name="csrf-token" content="<?= e($csrf_token) ?>">
  <?php endif; ?>
  <style>
    body{font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans JP", sans-serif; margin:24px;}
    h1{margin-bottom:8px}
    h2{margin:24px 0 8px}
    small.muted{color:#666}
    ul{padding-left:18px}
    .chip{display:inline-block; width:12px; height:12px; border-radius:3px; vertical-align:middle; margin-right:6px; border:1px solid #ddd;}
    .grid{display:grid; grid-template-columns: 1fr 1fr; gap:16px}
    .card{border:1px solid #eee; border-radius:8px; padding:12px}
    .empty{color:#777}
    table{border-collapse:collapse; width:100%}
    th,td{border-bottom:1px solid #eee; padding:6px 8px; text-align:left}
    th{background:#fafafa}
  </style>
</head>
<body>

  <h1>応募一覧</h1>
  <small class="muted">
    ユーザーID: <?= e($current_user ?? '') ?>
  </small>

  <?php
    // ステータスID → ラベル/色の辞書（会社カード表示用）
    $status_map = [];
    if (!empty($statuses) && is_array($statuses)) {
      foreach ($statuses as $s) {
        $status_map[$s['id']] = [
          'key'  => $s['key'] ?? '',
          'name' => $s['label_ja'] ?? '',
          'color'=> $s['color_hex'] ?? '#CCCCCC',
        ];
      }
    }
  ?>

  <!-- ステータス（マスタ） -->
  <h2>ステータス（カンバン列）</h2>
  <?php if (!empty($statuses)): ?>
    <table>
      <thead>
        <tr><th>ID</th><th>キー</th><th>表示名</th><th>色</th></tr>
      </thead>
      <tbody>
        <?php foreach ($statuses as $s): ?>
          <tr>
            <td><?= e($s['id']) ?></td>
            <td><?= e($s['key'] ?? '') ?></td>
            <td><?= e($s['label_ja'] ?? '') ?></td>
            <td>
              <?php $hex = $s['color_hex'] ?? '#CCCCCC'; ?>
              <span class="chip" style="background: <?= e($hex) ?>"></span>
              <code><?= e($hex) ?></code>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="empty">ステータスは未登録です。</p>
  <?php endif; ?>

  <!-- 企業カード -->
  <h2>企業カード</h2>
  <?php if (!empty($companies)): ?>
    <div class="grid">
      <?php foreach ($companies as $c): ?>
        <?php
          $sid = $c['status_id'] ?? null;
          $st  = $sid !== null && isset($status_map[$sid]) ? $status_map[$sid] : ['name'=>'未設定','color'=>'#CCCCCC','key'=>''];
        ?>
        <div class="card">
          <div>
            <span class="chip" style="background: <?= e($st['color']) ?>"></span>
            <strong><?= e($c['name'] ?? '') ?></strong>
          </div>
          <div><small class="muted">Status: <?= e($st['name']) ?> (<?= e($st['key']) ?>)</small></div>
          <?php if (!empty($c['position_title'])): ?>
            <div>職種: <?= e($c['position_title']) ?></div>
          <?php endif; ?>
          <?php if (!empty($c['employment_type'])): ?>
            <div>雇用形態: <?= e($c['employment_type']) ?></div>
          <?php endif; ?>
          <?php if (!empty($c['location_text'])): ?>
            <div>勤務地: <?= e($c['location_text']) ?></div>
          <?php endif; ?>
          <?php if (!empty($c['website_url'])): ?>
            <div>URL: <a href="<?= e($c['website_url']) ?>" target="_blank" rel="noopener noreferrer"><?= e($c['website_url']) ?></a></div>
          <?php endif; ?>
          <?php if (!empty($c['description'])): ?>
            <div>メモ: <?= e($c['description']) ?></div>
          <?php endif; ?>
          <div><small class="muted">更新: <?= e($c['updated_at'] ?? '') ?></small></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="empty">企業カードはまだありません。</p>
  <?php endif; ?>

  <!-- 今後の予定 -->
  <h2>今後の予定</h2>
  <?php if (!empty($events)): ?>
    <ul>
      <?php foreach ($events as $ev): ?>
        <li>
          <?= e($ev['start_at'] ?? '') ?>
          <?php if (!empty($ev['end_at'])): ?>
            〜 <?= e($ev['end_at']) ?>
          <?php endif; ?>
          — <?= e($ev['title'] ?? '') ?>
          <?php if (!empty($ev['location_text'])): ?>
            <small class="muted">（<?= e($ev['location_text']) ?>）</small>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="empty">直近の予定はありません。</p>
  <?php endif; ?>

  <hr>
  <p><a href="/applications/create">新規作成</a></p>

  <hr>
<h2>（Knockout）企業の追加・一覧（非同期）</h2>

<div id="ko-app">
  <form data-bind="submit: addCompany" style="margin-bottom:12px">
    <input placeholder="会社名" data-bind="value: newName, valueUpdate:'input'">
    <select data-bind="options: statuses, optionsText: 'label_ja', optionsValue: 'id', value: newStatusId"></select>
    <button type="submit">追加</button>
    <span data-bind="visible: error" style="color:#d00; margin-left:8px" data-bind-text="error"></span>
  </form>

  <ul data-bind="foreach: companies">
    <li>
      <strong data-bind="text: name"></strong>
      <small>（status_id: <span data-bind="text: status_id"></span>）</small>
      <button data-bind="click: $parent.removeCompany">削除</button>
    </li>
  </ul>
</div>

<h2>カレンダー</h2>
<div id="calendar-root"></div>
<script type="module" src="/assets/js/react-bundle.js"></script>

<!-- KO は従来どおり -->
<script src="/assets/js/vendor/knockout-3.5.1.js"></script>
<script src="/assets/js/app.js"></script>

<!-- React バンドルは分離先から -->
<script type="module" src="/assets/react/react-bundle.js"></script>


<!-- Knockout 本体（ローカル配置） -->
<script src="/assets/js/vendor/knockout-3.5.1.js"></script>
<!-- KO アプリ -->
<script src="/assets/js/app.js"></script>


</body>
</html>
