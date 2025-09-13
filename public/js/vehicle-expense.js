(() => {
  const qs = (sel) => document.querySelector(sel);
  const qsa = (sel) => Array.from(document.querySelectorAll(sel));

  const toTwoDigits = (value) => String(value).padStart(2, '0');

  const yearSpan = document.querySelector('#year');
  if (yearSpan) yearSpan.textContent = String(new Date().getFullYear());

  const vehicleName = qs('#vehicleName');
  const startHour = qs('#startHour');
  const startMinute = qs('#startMinute');
  const endHour = qs('#endHour');
  const endMinute = qs('#endMinute');
  const departure = qs('#departure');
  const arrival = qs('#arrival');
  const okButton = qs('#okButton');

  if (!vehicleName || !startHour || !startMinute || !endHour || !endMinute || !departure || !arrival || !okButton) {
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

  const isFilled = () => {
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
    okButton.style.display = isFilled() ? '' : 'none';
  };

  [vehicleName, startHour, startMinute, endHour, endMinute, departure, arrival].forEach((el) => {
    el.addEventListener('input', updateOk);
    el.addEventListener('change', updateOk);
    el.addEventListener('blur', updateOk);
  });

  updateOk();

  okButton.addEventListener('click', () => {
    window.location.href = '/vehicle-expense-return.html';
  });
})();


