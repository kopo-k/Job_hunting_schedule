// KO本体(3.5.1)の後で読み込む
function VM() {
  const self = this;

  // モーダルの表示フラグ
  self.showAdd  = ko.observable(false);

  // 開く/閉じる
  self.openAdd  = () => self.showAdd(true);
  self.closeAdd = () => self.showAdd(false);

  // ESCで閉じる（任意）
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && self.showAdd()) self.closeAdd();
  });
}

ko.applyBindings(new VM());