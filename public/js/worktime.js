(() => {
  const toTwoDigits = (value) => {
    const num = Number(value);
    if (Number.isNaN(num) || num < 0) return "";
    return num.toString().padStart(2, "0");
  };

  const clamp = (value, min, max) => {
    const n = Number(value);
    if (Number.isNaN(n)) return "";
    return Math.min(Math.max(n, min), max);
  };

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

  const normalizeInput = (input, min, max) => {
    input.addEventListener('change', () => {
      if (input.value === '') return;
      const clamped = clamp(input.value, min, max);
      input.value = String(clamped);
    });
    input.addEventListener('blur', () => {
      if (input.value === '') return;
      input.value = String(clamp(input.value, min, max));
    });
    input.addEventListener('input', () => {
      const cleaned = input.value.replace(/[^0-9]/g, '');
      if (cleaned !== input.value) input.value = cleaned;
    });
  };

  normalizeInput(startHour, 0, 23);
  normalizeInput(endHour, 0, 23);
  normalizeInput(startMinute, 0, 59);
  normalizeInput(endMinute, 0, 59);

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
    const sh = toTwoDigits(startHour.value);
    const sm = toTwoDigits(startMinute.value);
    const eh = toTwoDigits(endHour.value);
    const em = toTwoDigits(endMinute.value);
    const summary = `開始 ${sh}:${sm} / 終了 ${eh}:${em}\n作業内容: ${workContent.value.trim()}`;
    alert(summary);
  });
})();


