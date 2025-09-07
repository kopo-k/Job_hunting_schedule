<!doctype html><meta charset="utf-8">
<title>就活スケジューラー</title>
<style>
  body{font-family:system-ui, sans-serif;background:#f6f7fb;margin:0}
  header{display:flex;justify-content:space-between;align-items:center;padding:16px 24px;background:#fff;border-bottom:1px solid #e5e7eb}
  .brand{font-weight:700} .sub{font-size:12px;color:#6b7280}
  main{padding:24px}
  .toolbar{display:flex;gap:12px;align-items:center;margin:12px 0}
  button, input{padding:10px 12px;border:1px solid #e5e7eb;border-radius:10px;background:#fff}
  .board{display:grid;grid-template-columns:repeat(8, minmax(240px,1fr));gap:16px}
  .col{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:12px;min-height:360px}
  .col h3{display:flex;justify-content:space-between;align-items:center;margin:0 0 8px}
  .badge{font-size:12px;border-radius:999px;padding:2px 8px;border:1px solid #e5e7eb;background:#f9fafb}
  .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px;margin:10px 0;box-shadow:0 1px 2px rgba(0,0,0,.04);cursor:grab}
  .title{font-weight:600;margin-bottom:6px}
  .meta{display:flex;gap:8px;flex-wrap:wrap;font-size:12px;color:#6b7280}
  .droptarget{outline:2px dashed #93c5fd;outline-offset:-6px}
</style>

<header>
  <div>
    <div class="brand">就活スケジューラー</div>
    <div class="sub">Job Hunting Scheduler</div>
  </div>
  <div class="sub">ゲストユーザー</div>
</header>

<main>
  <h2>応募進捗管理</h2>
  <div class="toolbar">
    <button data-bind="click: reload">再読込</button>
    <button data-bind="click: openAdd">＋ 応募を追加</button>
  </div>

  <div class="board" data-bind="foreach: columns">
    <section class="col" data-bind="attr:{'data-key': key}, css:{droptarget: $parent.dropColumn()===key},
             event:{ dragover:$parent.onDragOver, drop:$parent.onDrop, dragleave:$parent.onDragLeave }">
      <h3>
        <span><span data-bind="text: label"></span></span>
        <span class="badge" data-bind="text: cards().length"></span>
      </h3>

      <div data-bind="foreach: cards">
        <article class="card" draggable="true"
                 data-bind="event:{ dragstart:$root.onDragStart, dragend:$root.onDragEnd }">
          <div class="title" data-bind="text: name"></div>
          <div class="meta">
            <span data-bind="visible: position_title, text: position_title"></span>
            <span data-bind="visible: employment_type, text: employment_type"></span>
            <span data-bind="visible: location_text, text: location_text"></span>
          </div>
        </article>
      </div>
    </section>
  </div>

  <pre id="out" style="margin-top:16px"></pre>
</main>

<script>window.CSRF='<?= \Security::fetch_token(); ?>';</script>
<script src="/assets/knockout-3.5.1.js"></script>
<script>
const api = {
  get:  url => fetch(url).then(r=>r.json()),
  post: (url, data) => fetch(url,{method:'POST',headers:{'X-CSRF-Token':CSRF,'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams(data)}).then(r=>r.json()),
  putJ: (url, data) => fetch(url,{method:'PUT', headers:{'X-CSRF-Token':CSRF,'Content-Type':'application/json'}, body:JSON.stringify(data)}).then(r=>r.json()),
};

function VM(){
  const self=this;
  self.columns = ko.observableArray([]);
  self.draggingId = ko.observable(null);
  self.dropColumn = ko.observable(null);

  self.reload = async ()=>{
    const [sts, comps] = await Promise.all([api.get('/api/statuses'), api.get('/api/companies')]);
    const byKey = {}; self.columns.removeAll();
    sts.forEach(s=>{ byKey[s.key]={ key:s.key, id:s.id, label:s.label_ja, cards: ko.observableArray([]) }; self.columns.push(byKey[s.key]); });
    comps.forEach(c=>{
      const colKey = (sts.find(s=>s.id===c.status_id)||{}).key || 'consider';
      byKey[colKey]?.cards.push(c);
    });
    log({statuses:sts.length, companies:comps.length});
  };

  // D&D（見た目だけ先に）
  self.onDragStart = function(_, ev){ self.draggingId(this.id); ev.dataTransfer.effectAllowed='move'; };
  self.onDragEnd   = function(){ self.draggingId(null); self.dropColumn(null); };
  self.onDragOver  = function(_, ev){ ev.preventDefault(); self.dropColumn(ev.currentTarget.dataset.key); };
  self.onDragLeave = function(_, ev){ if(ev.currentTarget.dataset.key===self.dropColumn()) self.dropColumn(null); };
  self.onDrop      = async function(_, ev){
    ev.preventDefault();
    const id = self.draggingId(), key = ev.currentTarget.dataset.key;
    if(!id || !key) return;
    const r = await api.putJ(`/api/companies/${id}/status`, {status_key: key});
    if(r.ok) self.reload(); else log(r);
    self.dropColumn(null);
  };

  self.openAdd = ()=> alert('次のステップでモーダル実装します');
}

const vm = new VM();
ko.applyBindings(vm);
vm.reload();

function log(x){ document.querySelector('#out').textContent = JSON.stringify(x,null,2); }
</script>
