// KO本体(3.5.1)の後で読み込む
function VM() {
  const self = this;

  // モーダルの表示フラグ
  self.showAdd  = ko.observable(false);

  // 開く/閉じる
  self.openAdd  = () => self.showAdd(true);
  self.closeAdd = () => self.showAdd(false);
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

  // フォームモデル（HTMLが参照する全部を用意）
  self.add = {
    name:            ko.observable(''),
    status_key:      ko.observable('consider'),
    position_title:  ko.observable(''),
    job_url:         ko.observable(''),
    employment_type: ko.observable(''),
    location_text:   ko.observable(''),
    memo:            ko.observable('')
  };
  // ESCで閉じる
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && self.showAdd()) self.closeAdd();
  });
  
  //新規登録
  self.goSignup = () => { window.location.href = '/signup'; }; 
}

ko.applyBindings(new VM());

