(() => {
  const toTwoDigits = (value) => String(value).padStart(2, "0");

  const qs = (sel) => document.querySelector(sel);
  const qsa = (sel) => Array.from(document.querySelectorAll(sel));

  const startHour = qs('#startHour');
  const startMinute = qs('#startMinute');
  const endHour = qs('#endHour');
  const endMinute = qs('#endMinute');
  const workContentRow = qs('#workContentRow');
  const workContent = qs('#workContent');
  const okButton = qs('#okButton');

  if (!startHour || !startMinute || !endHour || !endMinute || !workContentRow || !workContent || !okButton) return;

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

  const isTimeFilled = () => {
    const sh = startHour.value !== '';
    const sm = startMinute.value !== '';
    const eh = endHour.value !== '';
    const em = endMinute.value !== '';
    return sh && sm && eh && em;
  };

  const updateVisibility = () => {
    if (isTimeFilled()) {
      workContentRow.style.display = '';
    } else {
      workContentRow.style.display = 'none';
      okButton.style.display = 'none';
    }
    updateOkButton();
  };

  const updateOkButton = () => {
    const hasEnoughText = (workContent.value || '').trim().length >= 4;
    if (hasEnoughText && isTimeFilled()) {
      okButton.style.display = '';
    } else {
      okButton.style.display = 'none';
    }
  };

  const timeInputs = [startHour, startMinute, endHour, endMinute];
  timeInputs.forEach((el) => {
    el.addEventListener('input', updateVisibility);
    el.addEventListener('change', updateVisibility);
    el.addEventListener('blur', updateVisibility);
  });

  workContent.addEventListener('input', updateOkButton);

  const yearSpan = document.querySelector('#year');
  if (yearSpan) {
    yearSpan.textContent = String(new Date().getFullYear());
  }

  okButton.addEventListener('click', () => {
    window.location.href = '/vehicle-expense.html';
  });
})();


