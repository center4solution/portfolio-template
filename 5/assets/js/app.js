// Transit Map — v5. No frameworks, no build step.
(() => {
  'use strict';
  const doc = document.documentElement;
  doc.classList.add('js');
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => [...el.querySelectorAll(s)];
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ---------- Day / night service ----------
  const themeBtn = $('[data-theme-toggle]');
  const isDark = () => doc.dataset.theme ? doc.dataset.theme === 'dark' : matchMedia('(prefers-color-scheme: dark)').matches;
  const syncThemeIcon = () => { if (themeBtn) themeBtn.innerHTML = `<i class="fas fa-${isDark() ? 'sun' : 'moon'}"></i>`; };
  themeBtn?.addEventListener('click', () => {
    doc.dataset.theme = isDark() ? 'light' : 'dark';
    try { localStorage.setItem('brs-theme', doc.dataset.theme); } catch (e) {}
    syncThemeIcon();
  });
  syncThemeIcon();

  // ---------- Mobile menu ----------
  const menuBtn = $('[data-menu]');
  const nav = $('#line-nav');
  menuBtn?.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    menuBtn.setAttribute('aria-expanded', open);
    menuBtn.innerHTML = `<i class="fas fa-${open ? 'xmark' : 'bars'}"></i>`;
  });
  $$('#line-nav a').forEach(a => a.addEventListener('click', () => {
    if (!nav.classList.contains('is-open')) return;
    nav.classList.remove('is-open');
    menuBtn.setAttribute('aria-expanded', 'false');
    menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
  }));

  // ---------- Reveal on scroll ----------
  const io = 'IntersectionObserver' in window ? new IntersectionObserver(entries => {
    entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
  }, { rootMargin: '0px 0px -8% 0px' }) : null;
  $$('.reveal').forEach(el => io ? io.observe(el) : el.classList.add('is-in'));

  // ---------- Counters ----------
  const counters = $$('[data-count]');
  const runCount = el => {
    const end = +el.dataset.count;
    if (reduced) { el.textContent = end; return; }
    const t0 = performance.now();
    const step = t => { const k = Math.min(1, (t - t0) / 1200); el.textContent = Math.round(end * (1 - Math.pow(1 - k, 3))); if (k < 1) requestAnimationFrame(step); };
    requestAnimationFrame(step);
  };
  if (io) {
    const cio = new IntersectionObserver(es => es.forEach(en => { if (en.isIntersecting) { runCount(en.target); cio.unobserve(en.target); } }));
    counters.forEach(c => cio.observe(c));
  } else counters.forEach(runCount);

  // ---------- Scroll: progress bar, station spy, train ----------
  const bar = $('.ride-progress span');
  const navItems = $$('#line-nav li');
  const stations = ['origin', 'route', 'travel', 'skills', 'board', 'contact'].map(id => document.getElementById(id));
  const route = $('#route-line');
  const train = route && $('.train', route);
  let ticking = false;

  const onScroll = () => {
    ticking = false;
    const max = doc.scrollHeight - innerHeight;
    if (bar) bar.style.width = (max > 0 ? scrollY / max * 100 : 0) + '%';

    if (stations[0]) {
      let here = -1;
      stations.forEach((s, i) => { if (s && s.getBoundingClientRect().top < innerHeight * .4) here = i; });
      navItems.forEach((li, i) => li.classList.toggle('is-here', i === here));
    }

    if (train) {
      const r = route.getBoundingClientRect();
      const k = Math.min(1, Math.max(0, (innerHeight * .5 - r.top) / r.height));
      train.style.top = (k * (r.height - 44)) + 'px';
    }
  };
  addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(onScroll); } }, { passive: true });
  addEventListener('resize', onScroll);
  onScroll();

  // ---------- YouTube lite embeds ----------
  $$('.yt-lite').forEach(btn => btn.addEventListener('click', () => {
    const f = document.createElement('iframe');
    f.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(btn.dataset.id)}?autoplay=1&rel=0`;
    f.title = btn.getAttribute('aria-label') || 'YouTube video';
    f.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
    f.allowFullscreen = true;
    btn.replaceChildren(f);
  }, { once: true }));

  // ---------- Blog filters (board rows + cards) ----------
  const filters = $('#blog-filters');
  filters?.addEventListener('click', e => {
    const chip = e.target.closest('[data-filter]');
    if (!chip) return;
    $$('.chip', filters).forEach(c => c.classList.toggle('is-active', c === chip));
    const f = chip.dataset.filter;
    $$('[data-category]').forEach(el => { el.hidden = f !== 'all' && el.dataset.category !== f; });
  });

  // ---------- Copy link ----------
  $$('[data-copy]').forEach(btn => btn.addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(btn.dataset.copy);
      btn.innerHTML = '<i class="fas fa-check"></i>';
      setTimeout(() => { btn.innerHTML = '<i class="fas fa-link"></i>'; }, 1600);
    } catch (e) { prompt('Copy this link:', btn.dataset.copy); }
  }));
})();
