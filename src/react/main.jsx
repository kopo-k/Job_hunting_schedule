import React, { useEffect, useState } from "react";
import { createRoot } from "react-dom/client";
import CalendarWidget from "./CalendarWidget";

function App() {
  const [schedules, setSchedules] = useState([]);
  const [error, setError] = useState("");

  useEffect(() => {
    fetch("/api/schedules")
      .then(r => r.json())
      .then(setSchedules)
      .catch(e => setError("予定の取得に失敗しました"));
  }, []);

  return (
    <>
      {error ? <div style={{color:'#c00'}}>{error}</div> : null}
      <CalendarWidget schedules={schedules}/>
    </>
  );
}

const el = document.getElementById("calendar-root");
if (el) {
  const root = createRoot(el);
  root.render(<App />);
}
