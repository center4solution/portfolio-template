document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initReveal();
  initVlogs();
  initBlogFilters();
  initCopyLinks();
  initTravelMap();
  initProgress();

  const calm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  initTyper(calm);
  initCounters(calm);
  initLightbox();
  if (!calm && window.matchMedia('(pointer: fine)').matches) {
    initTilt();
    initMagnetic();
    initSpotlight();
  }
});

function initProgress() {
  const bar = document.createElement('div');
  bar.className = 'progress-bar';
  document.body.appendChild(bar);
  const update = () => {
    const max = document.documentElement.scrollHeight - innerHeight;
    bar.style.transform = `scaleX(${max > 0 ? scrollY / max : 0})`;
  };
  update();
  addEventListener('scroll', update, { passive: true });
}

// Types and deletes each role in turn
function initTyper(calm) {
  const el = document.querySelector('.typer');
  if (!el || calm) return;
  const words = JSON.parse(el.dataset.words || '[]');
  let w = 0, i = words[0]?.length || 0, deleting = true;
  const tick = () => {
    const word = words[w];
    i += deleting ? -1 : 1;
    el.textContent = word.slice(0, i);
    let delay = deleting ? 45 : 90;
    if (!deleting && i === word.length) { deleting = true; delay = 1800; }
    else if (deleting && i === 0) { deleting = false; w = (w + 1) % words.length; delay = 300; }
    setTimeout(tick, delay);
  };
  setTimeout(tick, 2200);
}

function initCounters(calm) {
  const els = document.querySelectorAll('[data-count]');
  const run = el => {
    const target = +el.dataset.count;
    if (calm) { el.textContent = target; return; }
    const start = performance.now();
    const step = now => {
      const t = Math.min((now - start) / 1400, 1);
      el.textContent = Math.round(target * (1 - Math.pow(1 - t, 3)));
      if (t < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };
  if (!('IntersectionObserver' in window)) return els.forEach(run);
  const io = new IntersectionObserver(entries => entries.forEach(e => {
    if (e.isIntersecting) { run(e.target); io.unobserve(e.target); }
  }), { threshold: 0.6 });
  els.forEach(el => io.observe(el));
}

function initTilt() {
  document.querySelectorAll('[data-tilt]').forEach(card => {
    const zone = card.parentElement;
    zone.addEventListener('mousemove', e => {
      const r = zone.getBoundingClientRect();
      const x = (e.clientX - r.left) / r.width - 0.5;
      const y = (e.clientY - r.top) / r.height - 0.5;
      card.style.transform = `perspective(900px) rotateY(${x * 14}deg) rotateX(${-y * 14}deg)`;
    });
    zone.addEventListener('mouseleave', () => { card.style.transform = ''; });
  });
}

function initMagnetic() {
  document.querySelectorAll('.magnetic').forEach(btn => {
    btn.addEventListener('mousemove', e => {
      const r = btn.getBoundingClientRect();
      btn.style.transform = `translate(${(e.clientX - r.left - r.width / 2) * 0.25}px, ${(e.clientY - r.top - r.height / 2) * 0.35}px)`;
    });
    btn.addEventListener('mouseleave', () => { btn.style.transform = ''; });
  });
}

function initSpotlight() {
  document.querySelectorAll('[data-spotlight]').forEach(cell => {
    cell.addEventListener('mousemove', e => {
      const r = cell.getBoundingClientRect();
      cell.style.setProperty('--mx', `${e.clientX - r.left}px`);
      cell.style.setProperty('--my', `${e.clientY - r.top}px`);
    });
  });
}

function initLightbox() {
  const box = document.getElementById('lightbox');
  if (!box) return;
  const img = box.querySelector('img');
  const close = () => { box.hidden = true; img.src = ''; };
  document.querySelectorAll('[data-lightbox]').forEach(a => a.addEventListener('click', e => {
    e.preventDefault();
    img.src = a.href;
    img.alt = a.querySelector('img')?.alt || '';
    box.hidden = false;
  }));
  box.addEventListener('click', e => { if (e.target !== img) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !box.hidden) close(); });
}

function initHeader() {
  const header = document.getElementById('site-header');
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('mobile-menu');
  if (!header) return;

  const onScroll = () => {
    const top = window.scrollY < 40;
    header.classList.toggle('at-top', top);
    header.classList.toggle('scrolled', !top);
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  toggle?.addEventListener('click', () => {
    const open = menu.classList.toggle('hidden') === false;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.innerHTML = open ? '<i class="fas fa-xmark"></i>' : '<i class="fas fa-bars"></i>';
  });
  menu?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
    menu.classList.add('hidden');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.innerHTML = '<i class="fas fa-bars"></i>';
  }));

  // Highlight the nav link of the section in view
  const links = [...document.querySelectorAll('.nav-link')];
  const sections = links
    .map(l => document.getElementById((l.getAttribute('href').split('#')[1] || '')))
    .filter(Boolean);
  if (!sections.length || !('IntersectionObserver' in window)) return;
  const spy = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      links.forEach(l => l.classList.toggle('is-active', l.getAttribute('href').endsWith('#' + entry.target.id)));
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  sections.forEach(s => spy.observe(s));
}

function initReveal() {
  const items = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) {
    items.forEach(el => el.classList.add('is-visible'));
    return;
  }
  const io = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
  items.forEach(el => io.observe(el));
}

// Load the YouTube iframe only when the user presses play
function initVlogs() {
  document.querySelectorAll('.yt-lite').forEach(btn => {
    btn.addEventListener('click', () => {
      const iframe = document.createElement('iframe');
      iframe.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(btn.dataset.id)}?autoplay=1`;
      iframe.title = btn.getAttribute('aria-label') || 'YouTube video';
      iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
      iframe.allowFullscreen = true;
      iframe.className = 'w-full aspect-video';
      btn.replaceWith(iframe);
    }, { once: true });
  });
}

function initBlogFilters() {
  const wrap = document.getElementById('blog-filters');
  if (!wrap) return;
  const cards = document.querySelectorAll('[data-category]');
  wrap.addEventListener('click', e => {
    const btn = e.target.closest('.filter-btn');
    if (!btn) return;
    wrap.querySelectorAll('.filter-btn').forEach(b => b.classList.toggle('is-active', b === btn));
    const f = btn.dataset.filter;
    cards.forEach(c => { c.hidden = f !== 'all' && c.dataset.category !== f; });
  });
}

function initCopyLinks() {
  document.querySelectorAll('[data-copy]').forEach(btn => {
    btn.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(btn.dataset.copy);
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => { btn.innerHTML = '<i class="fas fa-link"></i>'; }, 1500);
      } catch (_) { /* clipboard not available */ }
    });
  });
}

// Lazy-load Leaflet only when the map scrolls near the viewport
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

  const icon = color => L.divIcon({
    className: '',
    html: `<span style="display:block;width:16px;height:16px;border-radius:50%;background:${color};border:3px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)"></span>`,
    iconSize: [16, 16],
    iconAnchor: [8, 8],
  });
  const withVideo = icon('#10b981');
  const noVideo = icon('#64748b');

  const points = places.map(([name, lat, lng, video]) => {
    const pop = document.createElement('div');
    pop.className = 'map-pop';
    const title = document.createElement('b');
    title.textContent = name;
    pop.appendChild(title);
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

  // Enable scroll-zoom only after the user interacts with the map
  map.once('focus click', () => map.scrollWheelZoom.enable());
}
