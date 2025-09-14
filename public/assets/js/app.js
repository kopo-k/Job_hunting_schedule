/* app.js — Kanban + Knockout + Fuel API 接続（あなたのVM構造に合わせた完全版） */

// ===== ユーティリティ =====
function csrf() {
  const m = document.querySelector('meta[name="csrf-token"]');
  return m ? m.content : '';
}
function jsonHeaders() {
  const headers = { 'Content-Type': 'application/json' };
  const token = csrf();
  if (token) {
    headers['fuel_csrf_token'] = token; // FuelPHP用のCSRFトークンフィールド
  }
  return headers;
}
function toCardRow(row) {
  // サーバ→UI のフィールド対応
  return {
    id:            ko.observable(Number(row.id)),
    name:          ko.observable(row.name || ''),
    status_key:    ko.observable(row.key || 'consider'),
    position_title:ko.observable(row.position_title || ''),
    job_url:       ko.observable(row.website_url || ''),  // API: website_url → UI: job_url
    employment_type: ko.observable(row.employment_type || ''),
    location_text: ko.observable(row.location_text || ''),
    memo:          ko.observable(row.description || '')   // API: description → UI: memo
  };
}

// ===== ViewModel =====
function VM() {
  const self = this;

  // モーダル表示
  self.showAdd  = ko.observable(false);
  self.showEdit = ko.observable(false);

  // すべてのカード（列は filter で切る）
  self.cards = ko.observableArray([]);

  // 列表示用ラベル/色（既存ロジックを踏襲）
  self.statusOptions = ko.observableArray([
    { key: 'consider', label: '検討中' },
    { key: 'entry',    label: 'エントリー' },
    { key: 'es',       label: 'ES' },
    { key: 'first',    label: '一次' },
    { key: 'second',   label: '二次' },
    { key: 'final',    label: '最終' },
    { key: 'naitei',   label: '内々定' },
    { key: 'reject',   label: '不合格' }
  ]);
  self.getColumnColor = function(status) {
    const colors = {
      consider: '#64748b', entry: '#3b82f6', es: '#f59e0b', first: '#10b981',
      second: '#8b5cf6', final: '#ef4444', naitei: '#059669', reject: '#6b7280'
    };
    return colors[status];
  };

  // 列ビュー用のフィルタ
  self.getCardsByStatus = function(status) {
    return ko.computed(function() {
      return self.cards().filter(function(card){ return card.status_key() === status; });
    });
  };
  self.getCountByStatus = function(status) {
    return ko.computed(function(){
      return self.cards().filter(function(card){ return card.status_key() === status; }).length;
    });
  };

  // フォーム（追加/編集）
  self.add = {
    name:            ko.observable(''),
    status_key:      ko.observable('consider'),
    position_title:  ko.observable(''),
    job_url:         ko.observable(''),
    employment_type: ko.observable(''),
    location_text:   ko.observable(''),
    memo:            ko.observable('')
  };
  self.edit = {
    name:            ko.observable(''),
    status_key:      ko.observable('consider'),
    position_title:  ko.observable(''),
    job_url:         ko.observable(''),
    employment_type: ko.observable(''),
    location_text:   ko.observable(''),
    memo:            ko.observable('')
  };
  self.currentCard = null;

  // ===== 初期ロード =====
  self.loadCompanies = function() {
    return fetch('/api/companies')
      .then(r => {
        if (r.ok) {
          // レスポンスが空の場合（204など）は空配列を返す
          return r.status === 204 ? [] : r.json();
        } else {
          return Promise.reject(r);
        }
      })
      .then(rows => {
        // データが空の場合も正常処理
        const companies = Array.isArray(rows) ? rows : [];
        self.cards(companies.map(toCardRow));
        console.log(`${companies.length}件の企業データを読み込みました`);
      })
      .catch(error => {
        console.error('API error:', error);
        if (error.status) {
          alert(`データの取得に失敗しました (HTTP ${error.status})`);
        } else {
          alert('ネットワークエラーが発生しました');
        }
      });
  };

  // ===== 追加 =====
  self.resetAddForm = function() {
    self.add.name('');
    self.add.status_key('consider');
    self.add.position_title('');
    self.add.job_url('');
    self.add.employment_type('');
    self.add.location_text('');
    self.add.memo('');
  };
  self.openAdd = function() {
    self.resetAddForm(); // 必ずリセットしてから開く
    self.showAdd(true);
  };
  self.closeAdd = function(){ 
    self.resetAddForm(); // 閉じる時もリセット
    self.showAdd(false); 
  };

  self.addCard = function(formElement, event) {
    if (event) event.preventDefault();
    if (!self.add.name().trim()) return;

    const payload = {
      name: self.add.name().trim(),
      status_key: self.add.status_key(),
      website_url: self.add.job_url().trim(),          // UI→API
      position_title: self.add.position_title().trim(),
      employment_type: self.add.employment_type().trim(),
      location_text: self.add.location_text().trim(),
      description: self.add.memo().trim(),             // UI→API
      fuel_csrf_token: csrf()                          // CSRFトークン（要件13）
    };

    fetch('/api/companies', { method:'POST', headers: jsonHeaders(), body: JSON.stringify(payload) })
      .then(r => r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
      .then((result) => {
        // 新しいカードを手動でUIに追加
        const newCard = toCardRow({
          id: result.id,
          name: payload.name,
          key: payload.status_key,
          position_title: payload.position_title,
          website_url: payload.website_url,
          employment_type: payload.employment_type,
          location_text: payload.location_text,
          description: payload.description
        });
        self.cards.push(newCard);
        
        // フォームをリセットしてモーダルを閉じる
        self.resetAddForm();
        self.closeAdd();
        
        // D&D機能は一度だけ初期化済みなので再初期化不要
      })
      .catch(err => alert('作成に失敗しました: ' + (err.error || 'unknown')));
  };

  // ===== 編集 =====
  self.openEdit = function(card) {
    self.currentCard = card;
    self.edit.name(card.name());
    self.edit.status_key(card.status_key());
    self.edit.position_title(card.position_title() || '');
    self.edit.job_url(card.job_url() || '');
    self.edit.employment_type(card.employment_type() || '');
    self.edit.location_text(card.location_text() || '');
    self.edit.memo(card.memo() || '');
    self.showEdit(true);
  };
  self.closeEdit = function(){ self.showEdit(false); };

  self.saveEdit = function(formElement, event) {
    if (event) event.preventDefault();
    if (!self.edit.name().trim() || !self.currentCard) return;

    const id = self.currentCard.id();
    const payload = {
      name: self.edit.name().trim(),
      status_key: self.edit.status_key(),
      website_url: self.edit.job_url().trim(),
      position_title: self.edit.position_title().trim(),
      employment_type: self.edit.employment_type().trim(),
      location_text: self.edit.location_text().trim(),
      description: self.edit.memo().trim(),
      fuel_csrf_token: csrf()                          // CSRFトークン（要件13）
    };

    fetch(`/api/companies/${id}`, { method:'PUT', headers: jsonHeaders(), body: JSON.stringify(payload) })
      .then(r => r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
      .then(() => self.loadCompanies())
      .then(() => self.closeEdit())
      .catch(err => alert('更新に失敗しました: ' + (err.error || 'unknown')));
  };

  // ===== 削除 =====
  self.deleteCard = function(card) {
    if (!confirm('この応募を削除しますか？')) return;
    const id = card.id();
    fetch(`/api/companies/${id}`, { 
      method:'DELETE', 
      headers: jsonHeaders(),
      body: JSON.stringify({ fuel_csrf_token: csrf() }) // CSRFトークン（要件13）
    })
      .then(r => r.ok ? r.json() : r.json().then(e=>Promise.reject(e)))
      .then(() => { self.cards.remove(card); })
      .catch(err => alert('削除に失敗しました: ' + (err.error || 'unknown')));
  };

  // ===== D&D（列移動 → サーバ反映） =====
  self.initDragDrop = function() {
    if (!window.jQuery || !jQuery.fn.sortable) {
      console.warn('jQuery UI Sortable が見つかりません。D&Dはスキップします。');
      return;
    }
    
    // 既存のsortableを破棄
    try { 
      $('.sortable-container').sortable('destroy'); 
    } catch(e){}

    // Sortable初期化
    $('.sortable-container').sortable({
      connectWith: '.sortable-container',
      items: '.kanban-card',
      placeholder: 'kanban-card-placeholder',
      tolerance: 'pointer',
      helper: function(event, item) {
        // カスタムヘルパーでクローン問題を回避
        return item.clone().addClass('sortable-helper');
      },
      appendTo: 'body',
      zIndex: 1001,
      
      start: function(event, ui) {
        ui.placeholder.height(ui.item.outerHeight());
        ui.item.data('start-status', ui.item.closest('.sortable-container').attr('data-status'));
        ui.item.data('original-id', ui.item.attr('data-id'));
      },
      
      stop: function(event, ui) {
        const id = Number(ui.item.data('original-id'));
        const newContainer = ui.item.closest('.sortable-container');
        const newStatus = newContainer.attr('data-status');
        const oldStatus = ui.item.data('start-status');

        // 同じ列内での移動は無視
        if (oldStatus === newStatus || !id || !newStatus) {
          return;
        }

        console.log(`Moving card ${id} from ${oldStatus} to ${newStatus}`);
        
        // 即座にDOM操作を停止し、Knockout.jsに処理を移譲
        ui.item.remove(); // jQuery UIが作成した要素を削除
        
        // Knockout.jsでデータ更新
        const card = self.cards().find(c => Number(c.id()) === id);
        if (card) {
          const originalStatus = card.status_key();
          
          // サーバー更新
          fetch(`/api/companies/${id}/status`, {
            method: 'PUT',
            headers: jsonHeaders(),
            body: JSON.stringify({ 
              status_key: newStatus,
              fuel_csrf_token: csrf()
            })
          })
          .then(r => r.ok ? r.json() : Promise.reject(r))
          .then(result => {
            console.log('Status update successful:', result);
            // サーバー更新成功後にKnockout.jsデータを更新
            card.status_key(newStatus);
          })
          .catch(error => {
            console.error('Status update failed:', error);
            alert('ステータス変更に失敗しました');
            // エラー時は元のまま（何もしない）
          });
        }
      }
    }).disableSelection();
  };

  // ESCでモーダル閉じる
  window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      if (self.showAdd()) self.closeAdd();
      if (self.showEdit()) self.closeEdit();
    }
  });

  // 新規登録リンク（既存のヘルパ）
  self.goSignup = function(){ window.location.href = '/signup'; };

  // 初期化
  self.init = function() { 
    self.loadCompanies().then(() => {
      // データ読み込み完了後にD&D初期化（一度だけ）
      setTimeout(() => self.initDragDrop(), 100);
    });
  };
}

// knockoutを有効化 + 起動
document.addEventListener('DOMContentLoaded', function(){
  const vm = new VM();
  ko.applyBindings(vm);
  vm.init();
});
