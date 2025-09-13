(() => {
  const qs = (sel) => document.querySelector(sel);
  const toTwo = (n) => String(n).padStart(2, '0');

  const parseTime = (h, m) => {
    const hh = Number(h || 0);
    const mm = Number(m || 0);
    return hh * 60 + mm;
  };

  const fmtHM = (min) => {
    const h = Math.floor(min / 60);
    const m = min % 60;
    return `${toTwo(h)}:${toTwo(m)}`;
  };

  const safeJSON = (key) => {
    try { return JSON.parse(localStorage.getItem(key) || 'null'); } catch { return null; }
  };

  const work = safeJSON('worktime');
  const go = safeJSON('vehicle_go');
  const ret = safeJSON('vehicle_return');
  const tolls = safeJSON('tolls');

  const wtText = qs('#worktimeText');
  const goText = qs('#vehicleGoText');
  const reText = qs('#vehicleReturnText');
  const tgText = qs('#tollsGoText');
  const trText = qs('#tollsReturnText');
  const adjText = qs('#adjustedText');

  // 表示テキスト
  if (work && wtText) {
    const s = `${toTwo(work.startHour)}:${toTwo(work.startMinute)}`;
    const e = `${toTwo(work.endHour)}:${toTwo(work.endMinute)}`;
    wtText.textContent = `${s} 〜 ${e}`;
  }

  const fmtRoute = (data) => {
    if (!data) return '';
    const s = `${toTwo(data.startHour)}:${toTwo(data.startMinute)}`;
    const e = `${toTwo(data.endHour)}:${toTwo(data.endMinute)}`;
    const dep = data.departure || '';
    const arr = data.arrival || '';
    return `${s} 〜 ${e} / ${dep} → ${arr}`;
  };

  if (goText) goText.textContent = fmtRoute(go);
  if (reText) reText.textContent = fmtRoute(ret);

  const fmtToll = (entry, exit, fare) => {
    if (!entry && !exit && !fare) return '';
    const y = typeof fare === 'number' ? fare : Number(String(fare || '0').replace(/\D/g, ''));
    const yen = new Intl.NumberFormat('ja-JP').format(isNaN(y) ? 0 : y);
    return `${entry || ''} → ${exit || ''} / ${yen} 円`;
  };

  if (tgText) {
    if (!tolls) {
      tgText.textContent = '';
    } else if (tolls.goEntryIC || tolls.goExitIC || tolls.goFare) {
      tgText.textContent = fmtToll(tolls.goEntryIC, tolls.goExitIC, tolls.goFare);
    } else if (tolls.skipHighway) {
      tgText.textContent = '未使用';
    } else {
      tgText.textContent = '';
    }
  }
  if (trText) {
    if (!tolls) {
      trText.textContent = '';
    } else if (tolls.returnEntryIC || tolls.returnExitIC || tolls.returnFare) {
      trText.textContent = fmtToll(tolls.returnEntryIC, tolls.returnExitIC, tolls.returnFare);
    } else if (tolls.skipHighway) {
      trText.textContent = '未使用';
    } else {
      trText.textContent = '';
    }
  }

  // 重複自動調整
  // ルール: 労務時間と車両経費(行き/帰り)の時間帯が重複する部分を車両優先で切り出し、
  // 労務時間の方から重複分を差し引いて表示用に調整する。
  const intervals = [];
  if (work) {
    intervals.push({ type: 'work', s: parseTime(work.startHour, work.startMinute), e: parseTime(work.endHour, work.endMinute) });
  }
  if (go) {
    intervals.push({ type: 'vehicle', dir: 'go', s: parseTime(go.startHour, go.startMinute), e: parseTime(go.endHour, go.endMinute) });
  }
  if (ret) {
    intervals.push({ type: 'vehicle', dir: 'return', s: parseTime(ret.startHour, ret.startMinute), e: parseTime(ret.endHour, ret.endMinute) });
  }

  const clamp = (x, a, b) => Math.max(a, Math.min(b, x));

  const adjust = (workIv, vehIvs) => {
    if (!workIv) return [];
    let remaining = [{ s: workIv.s, e: workIv.e }];
    vehIvs.forEach((v) => {
      const next = [];
      remaining.forEach((r) => {
        const s = Math.max(r.s, v.s);
        const e = Math.min(r.e, v.e);
        const overlap = e > s;
        if (!overlap) {
          next.push(r);
          return;
        }
        // r: [r.s, r.e], remove [s, e]
        if (r.s < s) next.push({ s: r.s, e: s });
        if (e < r.e) next.push({ s: e, e: r.e });
      });
      remaining = next;
    });
    return remaining;
  };

  const workIv = intervals.find((iv) => iv.type === 'work');
  const vehIvs = intervals.filter((iv) => iv.type === 'vehicle');
  const adjusted = adjust(workIv, vehIvs);

  if (adjText) {
    if (!workIv) {
      adjText.textContent = '—';
    } else if (adjusted.length === 0) {
      adjText.textContent = '(重複により労務時間は 0 分)';
    } else {
      adjText.textContent = adjusted.map((iv) => `${fmtHM(iv.s)} 〜 ${fmtHM(iv.e)}`).join(' / ');
    }
  }
})();


