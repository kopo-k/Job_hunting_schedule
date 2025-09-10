// KO本体(3.5.1)の後で読み込む
function VM() {
  const self = this;

  // モーダルの表示フラグ
  self.showAdd  = ko.observable(false);
  self.showEdit = ko.observable(false);

  // カードデータ
  self.cards = ko.observableArray([]);

  // 応募ステータス用オプション
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

  // keyに対応したカラーを返す
  self.getColumnColor = function(status) {
    const colors = {
      consider: '#64748b',
      entry: '#3b82f6', 
      es: '#f59e0b',
      first: '#10b981',
      second: '#8b5cf6',
      final: '#ef4444',
      naitei: '#059669',
      reject: '#6b7280'
    };
    return colors[status];
  };

  // ステータス別カード取得
  self.getCardsByStatus = function(status) {
    return ko.computed(function() {
      //statusが一緒のものだけを返す
      return self.cards().filter(function(card) { 
        return card.status_key() === status; 
      });
    });
  };

  // ステータス別カード数取得
  self.getCountByStatus = function(status) {
    return ko.computed(function() {
      return self.cards().filter(function(card) { 
        return card.status_key() === status; 
      }).length;
    });
  };

  // フォームモデル（HTMLが参照する全部を用意しないとエラーが発生する）
  self.add = {
    name:            ko.observable(''),
    status_key:      ko.observable('consider'),
    position_title:  ko.observable(''),
    job_url:         ko.observable(''),
    employment_type: ko.observable(''),
    location_text:   ko.observable(''),
    memo:            ko.observable('')
  };

  // 編集フォーム
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

  // 各フィールドに初期値をセット、前回の値が残る事故防止
  self.resetAddForm = function() {
    self.add.name('');
    self.add.status_key('consider');
    self.add.position_title('');
    self.add.job_url('');
    self.add.employment_type('');
    self.add.location_text('');
    self.add.memo('');
  };

  // 開く
  self.openAdd  = function() {
    self.resetAddForm();
    // 前回の値がリセットされた後に表示するため
    self.showAdd(true);
  };
  ///閉じる
  self.closeAdd = function() { 
    self.showAdd(false); 
  };
  
  //編集モーダル
  self.openEdit = function(card) {
    //カードの現在値を編集モーダルにコピー
    self.currentCard = card;
    self.edit.name(card.name());
    self.edit.status_key(card.status_key());
    self.edit.position_title(card.position_title() || '');
    self.edit.job_url(card.job_url() || '');
    self.edit.employment_type(card.employment_type() || '');
    self.edit.location_text(card.location_text() || '');
    self.edit.memo(card.memo() || '');
    //コピーしてから表示
    self.showEdit(true);
  };
  self.closeEdit = function() { 
    self.showEdit(false); 
  };

  // 応募追加
  self.addCard = function(formElement, event) {
    if (event) event.preventDefault();
    if (!self.add.name().trim()) return;

    const newCard = {
      id: ko.observable(Date.now()),
      name: ko.observable(self.add.name()),
      status_key: ko.observable(self.add.status_key()),
      position_title: ko.observable(self.add.position_title()),
      job_url: ko.observable(self.add.job_url()),
      employment_type: ko.observable(self.add.employment_type()),
      location_text: ko.observable(self.add.location_text()),
      memo: ko.observable(self.add.memo())
    };

    self.cards.push(newCard);
    self.closeAdd();
  };

  self.saveEdit = function(formElement, event) {
    if (event) event.preventDefault();
    // 企業名と対象の企業カードがないなら中断
    if (!self.edit.name().trim() || !self.currentCard) return;
    //編集内容を登録
    self.currentCard.name(self.edit.name());
    self.currentCard.status_key(self.edit.status_key());
    self.currentCard.position_title(self.edit.position_title());
    self.currentCard.job_url(self.edit.job_url());
    self.currentCard.employment_type(self.edit.employment_type());
    self.currentCard.location_text(self.edit.location_text());
    self.currentCard.memo(self.edit.memo());

    self.closeEdit();
  };

  self.deleteCard = function(card) {
    if (confirm('この応募を削除しますか？')) {
      self.cards.remove(card);
    }
  };

  // ドラッグ&ドロップ初期化
  self.initDragDrop = function() {
    $('.sortable-container').sortable({
      connectWith: '.sortable-container',
      items: '.kanban-card', // カードのみドラッグ可能に限定
      placeholder: 'kanban-card-placeholder',
      tolerance: 'pointer',
      helper: 'clone',
      start: function(event, ui) {
        ui.placeholder.height(ui.item.height());
      },
      stop: function(event, ui) {
        const cardId = ui.item.attr('data-id');
        const newStatus = ui.item.parent().attr('data-status');
        
        if (cardId && newStatus) {
          self.updateCardStatus(parseInt(cardId), newStatus);
        }
      }
    }).disableSelection();
  };

  // カードのステータス更新
  self.updateCardStatus = function(cardId, newStatus) {
    const card = self.cards().find(function(c) { 
      return c.id() === cardId; 
    });
    if (card) {
      card.status_key(newStatus);
    }
  };

  // ESCで閉じる
  window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      if (self.showAdd()) self.closeAdd();
      if (self.showEdit()) self.closeEdit();
    }
  });
  
  //新規登録
  self.goSignup = function() { 
    window.location.href = '/signup'; 
  };

  // 初期データ（空にして0件状態をテスト）
  self.cards([]);

  // ドラッグ&ドロップ初期化（DOMの準備完了後）
  setTimeout(function() {
    self.initDragDrop();
  }, 500);
}

// knockoutを有効化
ko.applyBindings(new VM());