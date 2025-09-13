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


