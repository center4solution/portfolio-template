document.documentElement.classList.add('js');

const CALM = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const FINE = window.matchMedia('(pointer: fine)').matches;

document.addEventListener('DOMContentLoaded', () => {
  initBoot();
  initStarfield();
  initHeader();
  initClock();
  initPalette();
  initReveal();
  initScramble();
  initCounters();
  initDragScroll();
  initGlobe();
  initSystems();
  initVlogs();
  initBlogFilters();
  initCopyLinks();
  if (FINE && !CALM) { initCursorGlow(); initTilt(); initSpotlight(); }
});

/* ---------- Boot sequence (home page, once per session) ---------- */
function initBoot() {
  const boot = document.getElementById('boot');
  if (!boot) return;
  let seen = false;
  try { seen = sessionStorage.getItem('brs-booted') === '1'; sessionStorage.setItem('brs-booted', '1'); } catch (_) {}
  if (seen || CALM) { boot.remove(); return; }

  document.documentElement.style.setProperty('--boot', '1.6s');
  const log = boot.querySelector('.boot-log');
  const lines = ['> DEV//OS v4.0 — cold boot', '> mounting /code ............ ok', '> mounting /lens ............ ok', '> mounting /road ............ ok', '> calibrating globe ......... ok', '> welcome, visitor.'];
  let i = 0;
  const next = () => {
    if (i < lines.length) { log.textContent += lines[i++] + '\n'; setTimeout(next, 210); }
    else setTimeout(() => { boot.classList.add('done'); setTimeout(() => boot.remove(), 700); }, 250);
  };
  next();
  boot.addEventListener('click', () => boot.classList.add('done'));
}

/* ---------- Starfield background ---------- */
function initStarfield() {
  const c = document.getElementById('starfield');
  if (!c) return;
  const ctx = c.getContext('2d');
  let w, h, stars = [];
  const dpr = Math.min(devicePixelRatio || 1, 2);
  const resize = () => {
    w = c.width = innerWidth * dpr;
    h = c.height = innerHeight * dpr;
    const n = Math.min(260, Math.floor(innerWidth * innerHeight / 7000));
    stars = Array.from({ length: n }, () => ({ x: Math.random() * w, y: Math.random() * h, z: Math.random() * .8 + .2, t: Math.random() * 6.28 }));
  };
  resize();
  addEventListener('resize', resize);
  let scrollV = 0, lastY = scrollY;
  const draw = () => {
    scrollV += ((scrollY - lastY) - scrollV) * .1;
    lastY = scrollY;
    ctx.clearRect(0, 0, w, h);
    for (const s of stars) {
      s.t += .02;
      s.y -= (s.z * .15 + scrollV * s.z * .4) * dpr;
      if (s.y < 0) s.y += h; if (s.y > h) s.y -= h;
      const a = .35 + Math.sin(s.t) * .25 * s.z + s.z * .4;
      ctx.fillStyle = s.z > .85 ? `rgba(0,240,255,${a})` : `rgba(220,228,255,${a})`;
      ctx.fillRect(s.x, s.y, s.z * 1.8 * dpr, s.z * 1.8 * dpr);
    }
    if (!CALM) requestAnimationFrame(draw);
  };
  draw();
}

/* ---------- Header ---------- */
function initHeader() {
  const dock = document.getElementById('site-header');
  if (!dock) return;
  const onScroll = () => dock.classList.toggle('scrolled', scrollY > 30);
  onScroll();
  addEventListener('scroll', onScroll, { passive: true });

  const links = [...dock.querySelectorAll('.dock-nav a')];
  const targets = links.map(l => document.getElementById(l.getAttribute('href').split('#')[1] || '')).filter(Boolean);
  if (!targets.length || !('IntersectionObserver' in window)) return;
  const spy = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) links.forEach(l => l.classList.toggle('is-active', l.getAttribute('href').endsWith('#' + e.target.id)));
  }), { rootMargin: '-45% 0px -50% 0px' });
  targets.forEach(t => spy.observe(t));
}

function initClock() {
  const el = document.getElementById('clock');
  if (!el) return;
  const fmt = new Intl.DateTimeFormat('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZone: 'Asia/Kolkata', hour12: false });
  const tick = () => { el.textContent = fmt.format(new Date()) + ' IST'; };
  tick();
  setInterval(tick, 1000);
}

/* ---------- Command palette (Ctrl/⌘ + K) ---------- */
function initPalette() {
  const pal = document.getElementById('palette');
  if (!pal) return;
  const input = pal.querySelector('input');
  const items = [...pal.querySelectorAll('li')];
  let sel = 0;
  const visible = () => items.filter(li => !li.hidden);
  const mark = () => { const v = visible(); items.forEach(li => li.classList.remove('sel')); if (v[sel]) { v[sel].classList.add('sel'); v[sel].scrollIntoView({ block: 'nearest' }); } };
  const open = () => { pal.hidden = false; input.value = ''; filter(); input.focus(); };
  const close = () => { pal.hidden = true; };
  const filter = () => {
    const q = input.value.trim().toLowerCase();
    items.forEach(li => { li.hidden = q && !li.textContent.toLowerCase().includes(q); });
    sel = 0; mark();
  };

  document.querySelectorAll('[data-open-palette]').forEach(b => b.addEventListener('click', open));
  document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); pal.hidden ? open() : close(); }
    if (pal.hidden) return;
    const v = visible();
    if (e.key === 'Escape') close();
    else if (e.key === 'ArrowDown') { e.preventDefault(); sel = Math.min(sel + 1, v.length - 1); mark(); }
    else if (e.key === 'ArrowUp') { e.preventDefault(); sel = Math.max(sel - 1, 0); mark(); }
    else if (e.key === 'Enter' && v[sel]) { e.preventDefault(); v[sel].querySelector('a').click(); close(); }
  });
  input.addEventListener('input', filter);
  pal.addEventListener('click', e => { if (e.target === pal) close(); if (e.target.closest('a')) close(); });
}

/* ---------- Reveal / scramble / counters ---------- */
function initReveal() {
  const items = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) return items.forEach(el => el.classList.add('is-visible'));
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
  }), { threshold: 0.1 });
  items.forEach(el => io.observe(el));
}

function scramble(el) {
  const final = el.dataset.text || el.textContent;
  el.dataset.text = final;
  const glyphs = '!<>-_\\/[]{}—=+*^?#01ABCDEF';
  let frame = 0;
  const total = Math.min(40, final.length * 2 + 12);
  const tick = () => {
    const revealed = Math.floor((frame / total) * final.length);
    let out = '';
    for (let i = 0; i < final.length; i++) {
      if (i < revealed || final[i] === ' ') out += final[i].replace(/[<>&]/g, c => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c]));
      else out += `<span class="sc">${glyphs[Math.floor(Math.random() * glyphs.length)].replace(/[<>&]/g, c => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c]))}</span>`;
    }
    el.innerHTML = out;
    if (++frame <= total) requestAnimationFrame(tick);
    else el.textContent = final;
  };
  tick();
}

function initScramble() {
  const els = document.querySelectorAll('[data-scramble]');
  if (CALM || !('IntersectionObserver' in window)) return;
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) { setTimeout(() => scramble(e.target), e.target.classList.contains('ht-name') ? 400 : 0); io.unobserve(e.target); }
  }), { threshold: 0.6 });
  els.forEach(el => { el.dataset.text = el.textContent; io.observe(el); });
  document.querySelectorAll('.ht-name').forEach(el => el.addEventListener('mouseenter', () => scramble(el)));
}

function initCounters() {
  const els = document.querySelectorAll('[data-count]');
  const run = el => {
    const target = +el.dataset.count;
    if (CALM) { el.textContent = target; return; }
    const t0 = performance.now();
    const step = now => {
      const t = Math.min((now - t0) / 1600, 1);
      el.textContent = Math.round(target * (1 - Math.pow(1 - t, 4)));
      if (t < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };
  if (!('IntersectionObserver' in window)) return els.forEach(run);
  const io = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) { setTimeout(() => run(e.target), 600); io.unobserve(e.target); } }), { threshold: 0.5 });
  els.forEach(el => io.observe(el));
}

/* ---------- Mission log drag-to-scroll ---------- */
function initDragScroll() {
  document.querySelectorAll('[data-drag-scroll]').forEach(rail => {
    let down = false, startX = 0, startLeft = 0, moved = false;

    // Start at the newest end (right side)
    if (rail.hasAttribute('data-start-end')) {
      const toEnd = () => { rail.scrollLeft = rail.scrollWidth; };
      toEnd();
      addEventListener('load', toEnd); // again after fonts/images settle the width
    }
    rail.addEventListener('pointerdown', e => {
      if (e.pointerType !== 'mouse') return;
      down = true; moved = false; startX = e.clientX; startLeft = rail.scrollLeft;
      rail.classList.add('dragging');
    });
    addEventListener('pointermove', e => {
      if (!down) return;
      const dx = e.clientX - startX;
      if (Math.abs(dx) > 4) moved = true;
      rail.scrollLeft = startLeft - dx;
    });
    addEventListener('pointerup', () => { down = false; rail.classList.remove('dragging'); });
    rail.addEventListener('click', e => { if (moved) e.preventDefault(); }, true);
    // Vertical wheel scrolls the rail sideways while hovering it
    rail.addEventListener('wheel', e => {
      if (Math.abs(e.deltaY) <= Math.abs(e.deltaX)) return;
      const max = rail.scrollWidth - rail.clientWidth;
      if ((e.deltaY > 0 && rail.scrollLeft < max - 1) || (e.deltaY < 0 && rail.scrollLeft > 0)) {
        e.preventDefault();
        rail.scrollLeft += e.deltaY;
      }
    }, { passive: false });
  });
}

/* ---------- 3D globe (orthographic projection on canvas, no libraries) ---------- */
function initGlobe() {
  const canvas = document.getElementById('globe-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const places = JSON.parse(canvas.dataset.places || '[]');
  const target = document.getElementById('globe-target');
  const listItems = [...document.querySelectorAll('.loc-list li')];
  const rad = Math.PI / 180;

  // View state: centre longitude/latitude and zoom
  const home = { lon: 77.5, lat: 27, zoom: 5 };
  const view = { ...home };
  const goal = { ...home };
  let locked = -1, hovered = -1, dragging = false, last = null, idle = 0, size = 0, dpr = 1;

  // Pre-compute a dotted sphere surface
  const dots = [];
  for (let lat = -80; lat <= 80; lat += 4) {
    const step = 4 / Math.max(Math.cos(lat * rad), .15);
    for (let lon = -180; lon < 180; lon += step) dots.push([lat, lon]);
  }

  const resize = () => {
    dpr = Math.min(devicePixelRatio || 1, 2);
    const r = canvas.getBoundingClientRect();
    size = Math.min(r.width, r.height);
    canvas.width = r.width * dpr;
    canvas.height = r.height * dpr;
  };
  resize();
  addEventListener('resize', resize);

  const project = (lat, lon) => {
    const φ = lat * rad, λ = (lon - view.lon) * rad, φ0 = view.lat * rad;
    const cosc = Math.sin(φ0) * Math.sin(φ) + Math.cos(φ0) * Math.cos(φ) * Math.cos(λ);
    const R = size * .42 * view.zoom;
    const x = R * Math.cos(φ) * Math.sin(λ);
    const y = R * (Math.cos(φ0) * Math.sin(φ) - Math.sin(φ0) * Math.cos(φ) * Math.cos(λ));
    return { x: canvas.width / dpr / 2 + x, y: canvas.height / dpr / 2 - y, front: cosc > 0, depth: cosc };
  };

  const setTarget = i => {
    target.textContent = i < 0 ? 'TARGET: —' : `TARGET: ${places[i][0].toUpperCase()}`;
    listItems.forEach((li, k) => li.classList.toggle('lock', k === i));
  };

  const draw = t => {
    // Ease the view toward its goal; drift gently when idle
    if (!dragging && locked < 0 && !CALM && ++idle > 120) goal.lon = home.lon + Math.sin(t / 5000) * 14;
    for (const k of ['lon', 'lat', 'zoom']) view[k] += (goal[k] - view[k]) * .08;

    const W = canvas.width / dpr, H = canvas.height / dpr, R = size * .42 * view.zoom;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.clearRect(0, 0, W, H);

    // Atmosphere glow and sphere body
    const g = ctx.createRadialGradient(W / 2, H / 2, R * .7, W / 2, H / 2, R * 1.15);
    g.addColorStop(0, 'rgba(0,240,255,0)'); g.addColorStop(.6, 'rgba(0,240,255,.12)'); g.addColorStop(1, 'rgba(139,92,246,0)');
    ctx.fillStyle = g; ctx.beginPath(); ctx.arc(W / 2, H / 2, R * 1.15, 0, 7); ctx.fill();
    const body = ctx.createRadialGradient(W / 2 - R * .3, H / 2 - R * .3, R * .1, W / 2, H / 2, R);
    body.addColorStop(0, '#16204a'); body.addColorStop(1, '#070a1c');
    ctx.fillStyle = body; ctx.beginPath(); ctx.arc(W / 2, H / 2, R, 0, 7); ctx.fill();
    ctx.strokeStyle = 'rgba(0,240,255,.35)'; ctx.lineWidth = 1; ctx.stroke();

    // Dotted surface
    for (const [la, lo] of dots) {
      const p = project(la, lo);
      if (!p.front || p.x < -5 || p.x > W + 5 || p.y < -5 || p.y > H + 5) continue;
      ctx.fillStyle = `rgba(140,170,255,${.08 + p.depth * .22})`;
      ctx.fillRect(p.x, p.y, 1.4, 1.4);
    }

    // Graticule
    ctx.strokeStyle = 'rgba(139,92,246,.18)';
    for (let lon = -180; lon < 180; lon += 10) line(lat => [lat, lon], -90, 90);
    for (let lat = -80; lat <= 80; lat += 10) line(lon => [lat, lon], -180, 180);

    // Pins
    hovered = -1;
    let best = 14;
    const pts = places.map(([name, lat, lng, video], i) => ({ i, video, ...project(lat, lng) }));
    for (const p of pts) {
      if (!p.front) continue;
      if (pointer) { const d = Math.hypot(p.x - pointer.x, p.y - pointer.y); if (d < best) { best = d; hovered = p.i; } }
    }
    const pulse = (Math.sin(t / 300) + 1) / 2;
    for (const p of pts) {
      if (!p.front) continue;
      const hot = p.i === locked || p.i === hovered;
      const col = p.video ? '255,43,214' : '0,240,255';
      if (hot) {
        ctx.strokeStyle = `rgba(${col},${1 - pulse})`;
        ctx.beginPath(); ctx.arc(p.x, p.y, 6 + pulse * 16, 0, 7); ctx.stroke();
        ctx.strokeStyle = `rgba(${col},.9)`;
        ctx.beginPath(); ctx.moveTo(p.x - 18, p.y); ctx.lineTo(p.x - 8, p.y); ctx.moveTo(p.x + 8, p.y); ctx.lineTo(p.x + 18, p.y);
        ctx.moveTo(p.x, p.y - 18); ctx.lineTo(p.x, p.y - 8); ctx.moveTo(p.x, p.y + 8); ctx.lineTo(p.x, p.y + 18); ctx.stroke();
        ctx.font = '600 11px "JetBrains Mono", monospace';
        ctx.fillStyle = '#fff';
        ctx.fillText(places[p.i][0], p.x + 14, p.y - 12);
      }
      ctx.shadowColor = `rgb(${col})`; ctx.shadowBlur = hot ? 16 : 8;
      ctx.fillStyle = `rgb(${col})`;
      ctx.beginPath(); ctx.arc(p.x, p.y, hot ? 4.5 : 3, 0, 7); ctx.fill();
      ctx.shadowBlur = 0;
    }
    if (locked < 0) setTarget(hovered);
    canvas.style.cursor = hovered >= 0 && places[hovered][3] ? 'pointer' : '';
    requestAnimationFrame(draw);
  };

  function line(fn, from, to) {
    ctx.beginPath();
    let pen = false;
    for (let v = from; v <= to; v += 3) {
      const p = project(...fn(v));
      if (p.front) { pen ? ctx.lineTo(p.x, p.y) : ctx.moveTo(p.x, p.y); pen = true; } else pen = false;
    }
    ctx.stroke();
  }

  // Pointer: drag to rotate, hover to target, click a vlog pin to open it
  let pointer = null, downAt = null;
  const pos = e => { const r = canvas.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; };
  canvas.addEventListener('pointerdown', e => { dragging = true; last = pos(e); downAt = last; canvas.classList.add('dragging'); canvas.setPointerCapture(e.pointerId); locked = -1; });
  canvas.addEventListener('pointermove', e => {
    pointer = pos(e);
    if (!dragging) return;
    const k = 0.35 / view.zoom;
    goal.lon -= (pointer.x - last.x) * k;
    goal.lat = Math.max(-70, Math.min(70, goal.lat + (pointer.y - last.y) * k));
    view.lon = goal.lon; view.lat = goal.lat;
    last = pointer; idle = 0;
  });
  canvas.addEventListener('pointerup', e => {
    dragging = false; canvas.classList.remove('dragging');
    const p = pos(e);
    if (downAt && Math.hypot(p.x - downAt.x, p.y - downAt.y) < 5 && hovered >= 0 && places[hovered][3]) {
      window.open(places[hovered][3], '_blank', 'noopener');
    }
  });
  canvas.addEventListener('pointerleave', () => { pointer = null; });

  // Hovering a location in the list flies the globe to it
  listItems.forEach(li => {
    li.addEventListener('mouseenter', () => {
      const i = +li.dataset.index;
      locked = i; idle = 0;
      goal.lon = places[i][2]; goal.lat = places[i][1]; goal.zoom = 7;
      setTarget(i);
    });
    li.addEventListener('mouseleave', () => { locked = -1; Object.assign(goal, home); setTarget(-1); });
  });

  // Zoom: mouse wheel, pinch, and +/-/reset buttons. The chosen zoom becomes the new resting level.
  const zoomTo = z => { goal.zoom = home.zoom = Math.max(1, Math.min(10, z)); idle = 0; };
  canvas.addEventListener('wheel', e => { e.preventDefault(); zoomTo(goal.zoom * Math.exp(-e.deltaY * .0015)); }, { passive: false });
  const touches = new Map();
  let pinchFrom = 0, zoomFrom = 0;
  canvas.addEventListener('pointerdown', e => {
    touches.set(e.pointerId, pos(e));
    if (touches.size === 2) { const [a, b] = [...touches.values()]; pinchFrom = Math.hypot(a.x - b.x, a.y - b.y); zoomFrom = goal.zoom; dragging = false; }
  });
  canvas.addEventListener('pointermove', e => {
    if (!touches.has(e.pointerId)) return;
    touches.set(e.pointerId, pos(e));
    if (touches.size === 2 && pinchFrom) { const [a, b] = [...touches.values()]; zoomTo(zoomFrom * Math.hypot(a.x - b.x, a.y - b.y) / pinchFrom); }
  });
  const lift = e => { touches.delete(e.pointerId); if (touches.size < 2) pinchFrom = 0; };
  canvas.addEventListener('pointerup', lift);
  canvas.addEventListener('pointercancel', lift);
  document.querySelectorAll('.globe-zoom [data-zoom]').forEach(btn => btn.addEventListener('click', () => {
    const z = btn.dataset.zoom;
    if (z === 'reset') { locked = -1; home.lon = 77.5; home.lat = 27; Object.assign(goal, home); zoomTo(5); }
    else zoomTo(goal.zoom * (z === 'in' ? 1.4 : 1 / 1.4));
  }));

  requestAnimationFrame(draw);
}

/* ---------- Systems tabs ---------- */
function initSystems() {
  const tabs = [...document.querySelectorAll('.sys-tab')];
  tabs.forEach(tab => tab.addEventListener('click', () => {
    tabs.forEach(t => {
      const on = t === tab;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', String(on));
      const panel = document.getElementById(t.dataset.tab);
      panel.hidden = !on;
      if (on) panel.querySelectorAll('.bar i').forEach(b => { b.style.animation = 'none'; b.offsetWidth; b.style.animation = ''; });
    });
  }));
}

/* ---------- Media & misc ---------- */
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

function initCursorGlow() {
  const glow = document.querySelector('.cursor-glow');
  if (!glow) return;
  addEventListener('pointermove', e => {
    glow.style.setProperty('--cx', `${e.clientX}px`);
    glow.style.setProperty('--cy', `${e.clientY}px`);
  }, { passive: true });
}

function initTilt() {
  document.querySelectorAll('[data-tilt]').forEach(el => {
    el.addEventListener('mousemove', e => {
      const r = el.getBoundingClientRect();
      const x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
      el.style.transform = `perspective(900px) rotateY(${x * 8}deg) rotateX(${-y * 8}deg) translateY(-3px)`;
    });
    el.addEventListener('mouseleave', () => { el.style.transform = ''; });
  });
}

function initSpotlight() {
  document.querySelectorAll('[data-spotlight]').forEach(el => el.addEventListener('mousemove', e => {
    const r = el.getBoundingClientRect();
    el.style.setProperty('--mx', `${e.clientX - r.left}px`);
    el.style.setProperty('--my', `${e.clientY - r.top}px`);
  }));
}
