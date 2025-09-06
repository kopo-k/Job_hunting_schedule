(function(){
  // CSRF
  function csrf() {
    var m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.content : '';
  }

  function AppVM() {
    var self = this;
    self.statuses   = ko.observableArray([]);
    self.companies  = ko.observableArray([]);
    self.newName    = ko.observable('');
    self.newStatusId= ko.observable(null);
    self.error      = ko.observable('');

    self.loadAll = function(){
      // 並列取得
      Promise.all([
        fetch('/api/statuses').then(r=>r.json()),
        fetch('/api/companies').then(r=>r.json())
      ]).then(function(results){
        var sts  = results[0] || [];
        var comps= results[1] || [];
        self.statuses(sts);
        self.companies(comps);
        if (sts.length && !self.newStatusId()) self.newStatusId(sts[0].id);
      }).catch(function(e){
        self.error('読み込みに失敗しました');
        console.error(e);
      });
    };

    self.addCompany = function(e){
      e.preventDefault();
      self.error('');
      var name = (self.newName() || '').trim();
      var status_id = parseInt(self.newStatusId(),10);
      if (!name) { self.error('会社名は必須です'); return; }

      fetch('/api/companies', {
        method: 'POST',
        headers: {
          'Content-Type':'application/json',
          'X-CSRF-Token': csrf()
        },
        body: JSON.stringify({ name: name, status_id: status_id })
      }).then(function(r){
        if (r.status === 201) return r.json();
        if (r.status === 409) throw new Error('同名の会社が存在します');
        if (r.status === 422) throw new Error('入力値が不正です');
        throw new Error('エラーが発生しました');
      }).then(function(){
        self.newName('');
        self.loadAll();
      }).catch(function(e){
        self.error(e.message || '追加に失敗しました');
      });
    };

    self.removeCompany = function(item){
      if (!confirm('削除しますか？')) return;
      fetch('/api/companies/' + item.id, {
        method: 'DELETE',
        headers: { 'X-CSRF-Token': csrf() }
      }).then(function(r){
        if (!r.ok) throw new Error('削除に失敗しました');
        self.loadAll();
      }).catch(function(e){
        self.error(e.message || '削除に失敗しました');
      });
    };
  }

  var el = document.getElementById('ko-app');
  if (el) {
    var vm = new AppVM();
    ko.applyBindings(vm, el);
    vm.loadAll();
  }
})();
