document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', () => {
  const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  initTheme();
  initHeader();
  initReveal();
  initTerminal(calm);
  initVlogs();
  initBlogFilters();
  initCopyLinks();
  initTocPreview();
  initTravelMap();
});

function initTheme() {
  const btn = document.getElementById('theme-toggle');
  if (!btn) return;
  const root = document.documentElement;
  const isDark = () => root.dataset.theme === 'dark' ||
    (!root.dataset.theme && window.matchMedia('(prefers-color-scheme: dark)').matches);
  const paint = () => { btn.innerHTML = isDark() ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>'; };
  paint();
  btn.addEventListener('click', () => {
    root.dataset.theme = isDark() ? 'light' : 'dark';
    try { localStorage.setItem('brs-theme', root.dataset.theme); } catch (_) {}
    paint();
  });
}

function initHeader() {
  const header = document.getElementById('site-header');
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('mobile-menu');
  if (!header) return;

  // Hide on scroll down, show on scroll up
  let last = scrollY;
  addEventListener('scroll', () => {
    const y = scrollY;
    header.classList.toggle('scrolled', y > 20);
    header.classList.toggle('hide', y > last && y > 400 && menu.hidden);
    last = y;
  }, { passive: true });

  const setMenu = open => {
    menu.hidden = !open;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.innerHTML = open ? '<i class="fas fa-xmark"></i>' : '<i class="fas fa-bars"></i>';
  };
  toggle?.addEventListener('click', () => setMenu(menu.hidden));
  menu?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setMenu(false)));

  // Highlight the chapter currently in view
  const links = [...document.querySelectorAll('.bar-nav a')];
  const targets = links.map(l => document.getElementById(l.getAttribute('href').split('#')[1] || '')).filter(Boolean);
  if (!targets.length || !('IntersectionObserver' in window)) return;
  const spy = new IntersectionObserver(entries => entries.forEach(e => {
    if (e.isIntersecting) links.forEach(l => l.classList.toggle('is-active', l.getAttribute('href').endsWith('#' + e.target.id)));
  }), { rootMargin: '-45% 0px -50% 0px' });
  targets.forEach(t => spy.observe(t));
}

function initReveal() {
  const items = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) return items.forEach(el => el.classList.add('is-visible'));
  const io = new IntersectionObserver(entries => entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
  }), { threshold: 0.12 });
  items.forEach(el => io.observe(el));
}

// Types the command, then reveals each skill group line by line
function initTerminal(calm) {
  const term = document.querySelector('.terminal');
  if (!term) return;
  const cmd = term.querySelector('.cmd');
  const groups = [...term.querySelectorAll('.term-group, .term-ok')];
  if (calm || !('IntersectionObserver' in window)) return term.classList.add('typed');

  const text = cmd.textContent;
  cmd.textContent = '';
  const io = new IntersectionObserver(entries => {
    if (!entries[0].isIntersecting) return;
    io.disconnect();
    let i = 0;
    const type = () => {
      cmd.textContent = text.slice(0, ++i);
      if (i < text.length) return setTimeout(type, 55);
      groups.forEach((g, k) => setTimeout(() => { g.style.opacity = 1; g.style.transform = 'none'; }, 250 + k * 220));
    };
    setTimeout(type, 400);
  }, { threshold: 0.4 });
  io.observe(term);
}

function initVlogs() {
  document.querySelectorAll('.yt-lite').forEach(btn => btn.addEventListener('click', () => {
    const iframe = document.createElement('iframe');
    iframe.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(btn.dataset.id)}?autoplay=1`;
    iframe.title = btn.getAttribute('aria-label') || 'YouTube video';
    iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen = true;
    btn.replaceWith(iframe);
  }, { once: true }));
}

function initBlogFilters() {
  const wrap = document.getElementById('blog-filters');
  if (!wrap) return;
  const cards = document.querySelectorAll('[data-category]');
  wrap.addEventListener('click', e => {
    const btn = e.target.closest('[data-filter]');
    if (!btn) return;
    wrap.querySelectorAll('[data-filter]').forEach(b => b.classList.toggle('is-active', b === btn));
    cards.forEach(c => { c.hidden = btn.dataset.filter !== 'all' && c.dataset.category !== btn.dataset.filter; });
  });
}

function initCopyLinks() {
  document.querySelectorAll('[data-copy]').forEach(btn => btn.addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(btn.dataset.copy);
      btn.innerHTML = '<i class="fas fa-check"></i>';
      setTimeout(() => { btn.innerHTML = '<i class="fas fa-link"></i>'; }, 1500);
    } catch (_) { /* clipboard unavailable */ }
  }));
}

// Floating cover-image preview that follows the cursor over the contents list
function initTocPreview() {
  const img = document.querySelector('.toc-preview');
  if (!img || !window.matchMedia('(pointer: fine)').matches) return;
  document.querySelectorAll('.toc-row').forEach(row => {
    row.addEventListener('mouseenter', () => { img.src = row.dataset.preview; img.classList.add('on'); });
    row.addEventListener('mouseleave', () => img.classList.remove('on'));
    row.addEventListener('mousemove', e => {
      img.style.left = `${e.clientX + 24}px`;
      img.style.top = `${e.clientY - 80}px`;
    });
  });
}

function initTravelMap() {
  const el = document.getElementById('travel-map');
  if (!el) return;
  const load = () => {
    const css = document.createElement('link');
    css.rel = 'stylesheet';
    css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    css.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
    css.crossOrigin = '';
    document.head.appendChild(css);
    const js = document.createElement('script');
    js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    js.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
    js.crossOrigin = '';
    js.onload = () => drawMap(el);
    document.head.appendChild(js);
  };
  if (!('IntersectionObserver' in window)) return load();
  const io = new IntersectionObserver(entries => {
    if (entries.some(e => e.isIntersecting)) { io.disconnect(); load(); }
  }, { rootMargin: '300px' });
  io.observe(el);
}

function drawMap(el) {
  const places = JSON.parse(el.dataset.places || '[]');
  const map = L.map(el, { scrollWheelZoom: false });
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 18,
  }).addTo(map);

  const pin = color => L.divIcon({
    className: '',
    html: `<span style="display:block;width:18px;height:18px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);background:${color};border:2px solid #fff;box-shadow:0 2px 5px rgba(0,0,0,.4)"></span>`,
    iconSize: [18, 18],
    iconAnchor: [9, 18],
  });
  const withVideo = pin('#d9481c');
  const noVideo = pin('#0f766e');

  const points = places.map(([name, lat, lng, video]) => {
    const pop = document.createElement('div');
    pop.className = 'map-pop';
    const b = document.createElement('b');
    b.textContent = name;
    pop.appendChild(b);
    if (/^https:\/\//.test(video)) {
      const a = document.createElement('a');
      a.href = video;
      a.target = '_blank';
      a.rel = 'noopener';
      a.textContent = '▶ Watch the vlog';
      pop.appendChild(a);
    }
    L.marker([lat, lng], { icon: video ? withVideo : noVideo, title: name }).addTo(map).bindPopup(pop);
    return [lat, lng];
  });
  if (points.length) map.fitBounds(points, { padding: [30, 30] });
  else map.setView([22.5, 79], 5);
  map.once('focus click', () => map.scrollWheelZoom.enable());
}
