// Smooth scroll for internal links
document.addEventListener('click', (e) => {
  const target = e.target.closest('a[href^="#"]');
  if (!target) return;
  const href = target.getAttribute('href');
  if (href.length <= 1) return;
  const el = document.querySelector(href);
  if (!el) return;
  e.preventDefault();
  el.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

// Header nav: hamburger toggle
const navToggle = document.querySelector('.nav-toggle');
const navMenu = document.getElementById('nav-menu');
if (navToggle && navMenu) {
  navToggle.addEventListener('click', () => {
    const expanded = navToggle.getAttribute('aria-expanded') === 'true';
    navToggle.setAttribute('aria-expanded', String(!expanded));
    navToggle.classList.toggle('active');
    navMenu.classList.toggle('show');
  });

  // Close menu when clicking a link (mobile)
  navMenu.addEventListener('click', (e) => {
    if (e.target.closest('a')) {
      navToggle.classList.remove('active');
      navMenu.classList.remove('show');
      navToggle.setAttribute('aria-expanded', 'false');
    }
  });
}

// Reveal on scroll
const revealElements = document.querySelectorAll('.reveal');
const io = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      io.unobserve(entry.target);
    }
  });
}, { threshold: 0.15 });

revealElements.forEach((el) => io.observe(el));

// Back to top button
const backToTop = document.getElementById('backToTop');
const onScroll = () => {
  if (!backToTop) return;
  const show = window.scrollY > 480;
  backToTop.classList.toggle('show', show);
};
window.addEventListener('scroll', onScroll, { passive: true });
if (backToTop) {
  backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// Set current year
const yearEl = document.getElementById('year');
if (yearEl) yearEl.textContent = new Date().getFullYear();

// Demo auth: simple email/password check on login.html
document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    const AUTH_EMAIL = 'test.user@example.com';
    const AUTH_PASSWORD = 'Test-1234';
    const messageEl = document.getElementById('authMessage');

    loginForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const email = /** @type {HTMLInputElement} */(document.getElementById('email')).value.trim();
      const password = /** @type {HTMLInputElement} */(document.getElementById('password')).value;

      if (!email || !password) {
        if (messageEl) messageEl.textContent = 'メールアドレスとパスワードを入力してください。';
        return;
      }

      const isValid = email.toLowerCase() === AUTH_EMAIL && password === AUTH_PASSWORD;
      if (!isValid) {
        if (messageEl) messageEl.textContent = 'メールアドレスまたはパスワードが正しくありません。';
        return;
      }

      try {
        sessionStorage.setItem('demo_auth', '1');
        // デモ名: メールのローカル部を名前化
        const demoName = 'テストユーザー';
        sessionStorage.setItem('demo_user_name', demoName);
      } catch (_) { }
      window.location.href = 'index.html';
    });
  }

  // Show user name on top page
  const userNameHolder = document.getElementById('userNameText');
  const userNavItem = document.getElementById('nav-user');
  const loginLink = document.getElementById('navLoginLink');
  try {
    const isAuthed = sessionStorage.getItem('demo_auth') === '1';
    const savedName = sessionStorage.getItem('demo_user_name');
    if (isAuthed && userNavItem && userNameHolder) {
      userNameHolder.textContent = savedName || 'ログインユーザー';
      userNavItem.style.display = '';
      if (loginLink) loginLink.style.display = 'none';
    }
  } catch (_) { }
});


