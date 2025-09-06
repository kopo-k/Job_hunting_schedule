import React, { useMemo } from "react";
import { CalendarDays, Plus, Clock } from "lucide-react";

export default function CalendarWidget({ schedules = [] }) {
  // YYYY-MM-DD ごとにまとめる
  const grouped = useMemo(() => {
    const g = {};
    (schedules || []).forEach(ev => {
      const d = (ev.start_at || '').slice(0, 10); // 'YYYY-MM-DD'
      if (!g[d]) g[d] = [];
      g[d].push(ev);
    });
    // 日付昇順
    return Object.entries(g).sort(([a],[b]) => a.localeCompare(b));
  }, [schedules]);

  return (
    <div style={{border:'1px solid #eee', borderRadius:8, padding:12, margin:'16px 0'}}>
      <div style={{display:'flex', alignItems:'center', justifyContent:'space-between', marginBottom:8}}>
        <div style={{display:'flex', alignItems:'center', gap:8}}>
          <CalendarDays size={18} /> <strong>直近の予定</strong>
        </div>
        <a href="/applications/create" style={{textDecoration:'none', display:'inline-flex', alignItems:'center', gap:6}}>
          <Plus size={16}/> 追加
        </a>
      </div>

      {grouped.length === 0 && <div style={{color:'#777'}}>予定はありません。</div>}

      {grouped.map(([date, items]) => (
        <div key={date} style={{marginBottom:10}}>
          <div style={{fontWeight:600, margin:'6px 0'}}>{date}</div>
          <ul style={{paddingLeft:18, margin:0}}>
            {items
              .sort((a,b) => (a.start_at||'').localeCompare(b.start_at||''))
              .map(ev => (
              <li key={ev.id} style={{margin:'4px 0'}}>
                <span style={{display:'inline-flex', alignItems:'center', gap:6}}>
                  <Clock size={14}/>
                  <span>{(ev.start_at || '').slice(11,16)}{ev.end_at ? `〜${ev.end_at.slice(11,16)}` : ''}</span>
                </span>
                {' '}— {ev.title}
                {ev.location_text ? <span style={{color:'#666'}}>（{ev.location_text}）</span> : null}
              </li>
            ))}
          </ul>
        </div>
      ))}
    </div>
  );
}
