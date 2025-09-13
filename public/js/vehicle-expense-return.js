(() => {
  const qs = (sel) => document.querySelector(sel);

  const toTwoDigits = (value) => String(value).padStart(2, '0');

  const yearSpan = document.querySelector('#year');
  if (yearSpan) yearSpan.textContent = String(new Date().getFullYear());

  const vehicleName = qs('#vehicleName');
  const noCompanyCar = qs('#noCompanyCar');
  const startHour = qs('#startHour');
  const startMinute = qs('#startMinute');
  const endHour = qs('#endHour');
  const endMinute = qs('#endMinute');
  const departure = qs('#departure');
  const arrival = qs('#arrival');
  const okButton = qs('#okButton');

  if (!vehicleName || !startHour || !startMinute || !endHour || !endMinute || !departure || !arrival || !okButton || !noCompanyCar) {
    return;
  }

  const fillSelect = (select, start, end) => {
    const frag = document.createDocumentFragment();
    const empty = document.createElement('option');
    empty.value = '';
    empty.textContent = '';
    frag.appendChild(empty);
    for (let i = start; i <= end; i++) {
      const opt = document.createElement('option');
      opt.value = String(i);
      opt.textContent = toTwoDigits(i);
      frag.appendChild(opt);
    }
    select.appendChild(frag);
  };

  fillSelect(startHour, 0, 23);
  fillSelect(endHour, 0, 23);
  fillSelect(startMinute, 0, 59);
  fillSelect(endMinute, 0, 59);

  const isAllFilled = () => {
    const v = (vehicleName.value || '').trim().length > 0;
    const sh = startHour.value !== '';
    const sm = startMinute.value !== '';
    const eh = endHour.value !== '';
    const em = endMinute.value !== '';
    const dep = (departure.value || '').trim().length > 0;
    const arr = (arrival.value || '').trim().length > 0;
    return v && sh && sm && eh && em && dep && arr;
  };

  const updateOk = () => {
    const allow = noCompanyCar.checked ? true : isAllFilled();
    okButton.style.display = allow ? '' : 'none';
    if (noCompanyCar.checked) {
      okButton.classList.remove('btn-primary');
      okButton.classList.add('btn-accent');
    } else {
      okButton.classList.add('btn-primary');
      okButton.classList.remove('btn-accent');
    }
  };

  [vehicleName, startHour, startMinute, endHour, endMinute, departure, arrival, noCompanyCar].forEach((el) => {
    el.addEventListener('input', updateOk);
    el.addEventListener('change', updateOk);
    el.addEventListener('blur', updateOk);
  });

  updateOk();

  okButton.addEventListener('click', () => {
    const sh = toTwoDigits(startHour.value);
    const sm = toTwoDigits(startMinute.value);
    const eh = toTwoDigits(endHour.value);
    const em = toTwoDigits(endMinute.value);
    const summary = `車両: ${vehicleName.value}\n開始 ${sh}:${sm} / 終了 ${eh}:${em}\n経路: ${departure.value} → ${arrival.value}`;
    alert(summary);
  });
})();


