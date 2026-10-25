// Orbit — v7. No frameworks, no build step.
(() => {
  'use strict';
  const doc = document.documentElement;
  doc.classList.add('js');
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => [...el.querySelectorAll(s)];
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ---------- Starfield: three depth layers, twinkle, parallax on scroll, a shooting star now and then ----------
  const canvas = $('#space');
  if (canvas && canvas.getContext) {
    const ctx = canvas.getContext('2d');
    let w, h, stars = [], shot = null;
    const dpr = Math.min(devicePixelRatio || 1, 2);
    const resize = () => {
      w = canvas.width = innerWidth * dpr;
      h = canvas.height = innerHeight * dpr;
      const n = Math.round(innerWidth * innerHeight / 3200);
      stars = Array.from({ length: n }, () => ({
        x: Math.random() * w, y: Math.random() * h,
        z: Math.random() < .7 ? 1 : Math.random() < .8 ? 2 : 3,
        p: Math.random() * Math.PI * 2,
      }));
    };
    const draw = t => {
      ctx.clearRect(0, 0, w, h);
      const sy = scrollY * dpr;
      for (const s of stars) {
        const y = ((s.y - sy * s.z * .06) % h + h) % h;
        const a = .45 + .55 * Math.sin(t / 900 * (s.z * .6) + s.p) ** 2;
        ctx.globalAlpha = a;
        ctx.fillStyle = s.z === 3 ? '#ffd9a8' : '#dfe6ff';
        ctx.fillRect(s.x, y, s.z * .7 * dpr, s.z * .7 * dpr);
      }
      if (!shot && Math.random() < .004) shot = { x: Math.random() * w, y: Math.random() * h * .4, life: 0 };
      if (shot) {
        shot.life += 1;
        const len = 120 * dpr, k = shot.life * 14 * dpr;
        const g = ctx.createLinearGradient(shot.x + k, shot.y + k * .5, shot.x + k - len, shot.y + (k - len) * .5);
        g.addColorStop(0, 'rgba(255,255,255,.9)'); g.addColorStop(1, 'rgba(255,255,255,0)');
        ctx.globalAlpha = 1 - shot.life / 45;
        ctx.strokeStyle = g; ctx.lineWidth = 1.5 * dpr;
        ctx.beginPath(); ctx.moveTo(shot.x + k, shot.y + k * .5); ctx.lineTo(shot.x + k - len, shot.y + (k - len) * .5); ctx.stroke();
        if (shot.life > 45) shot = null;
      }
      ctx.globalAlpha = 1;
      if (!reduced) requestAnimationFrame(draw);
    };
    resize();
    addEventListener('resize', resize);
    requestAnimationFrame(draw);
  }

  // ---------- Mobile menu ----------
  const menuBtn = $('[data-menu]');
  const nav = $('#nav');
  const setMenu = open => {
    nav.classList.toggle('is-open', open);
    menuBtn.setAttribute('aria-expanded', open);
    menuBtn.innerHTML = `<i class="fas fa-${open ? 'xmark' : 'bars'}"></i>`;
  };
  menuBtn?.addEventListener('click', () => setMenu(!nav.classList.contains('is-open')));
  $$('#nav a').forEach(a => a.addEventListener('click', () => menuBtn && setMenu(false)));

  // ---------- Mission elapsed time ----------
  const met = $('#met');
  if (met) {
    const since = Date.parse(met.dataset.since);
    const pad = n => String(n).padStart(2, '0');
    const tick = () => {
      const s = Math.floor((Date.now() - since) / 1000);
      met.textContent = `${Math.floor(s / 86400)}d ${pad(Math.floor(s / 3600) % 24)}:${pad(Math.floor(s / 60) % 60)}:${pad(s % 60)}`;
    };
    tick();
    setInterval(tick, 1000);
  }

  // ---------- Reveal on scroll ----------
  const io = 'IntersectionObserver' in window ? new IntersectionObserver(entries => {
    entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
  }, { rootMargin: '0px 0px -8% 0px' }) : null;
  $$('.reveal').forEach(el => io ? io.observe(el) : el.classList.add('is-in'));

  // ---------- Counters ----------
  const runCount = el => {
    const end = +el.dataset.count;
    if (reduced) { el.textContent = end; return; }
    const t0 = performance.now();
    const step = t => { const k = Math.min(1, (t - t0) / 1300); el.textContent = Math.round(end * (1 - Math.pow(1 - k, 3))); if (k < 1) requestAnimationFrame(step); };
    requestAnimationFrame(step);
  };
  const counters = $$('[data-count]');
  if (io) {
    const cio = new IntersectionObserver(es => es.forEach(en => { if (en.isIntersecting) { runCount(en.target); cio.unobserve(en.target); } }));
    counters.forEach(c => cio.observe(c));
  } else counters.forEach(runCount);

  // ---------- Mission log: planets orbit the origin, the console shows the selected one ----------
  const log = $('#orbit-log');
  if (log) {
    const planets = $$('.mplanet', log);
    const files = $$('.m-file', log);
    const dots = $$('.m-dots span', log);
    const tabs = $$('.year-strip button');
    const paths = $$('.orbit-paths ellipse', log);
    const beam = $('#beam');
    const orbits = planets.map(g => ({ g, rx: +g.dataset.rx, ry: +g.dataset.ry, a: +g.dataset.a * Math.PI / 180 }));
    let active = 0, hover = false, visible = false;

    const place = o => {
      const x = 500 + o.rx * Math.cos(o.a), y = 230 + o.ry * Math.sin(o.a);
      // Planets on the far side of the orbit look smaller and dimmer
      const depth = (Math.sin(o.a) + 1) / 2;
      o.g.setAttribute('transform', `translate(${x.toFixed(1)} ${y.toFixed(1)}) scale(${(.75 + depth * .35).toFixed(3)})`);
      o.g.style.opacity = (.55 + depth * .45).toFixed(2);
      return [x, y];
    };
    const select = i => {
      active = (i + planets.length) % planets.length;
      const c = planets[active].style.getPropertyValue('--c');
      planets.forEach((p, k) => p.classList.toggle('is-active', k === active));
      files.forEach((f, k) => f.classList.toggle('is-active', k === active));
      tabs.forEach((t, k) => { t.classList.toggle('is-active', k === active); t.setAttribute('aria-selected', k === active); });
      paths.forEach((p, k) => { p.classList.toggle('is-on', k === active); p.style.setProperty('--c', c); });
      dots.forEach((d, k) => { d.className = k < active ? 'is-done' : k === active ? 'is-active' : ''; });
      beam.style.setProperty('--c', c);
      const strip = tabs[active]?.parentElement;
      if (strip) strip.scrollLeft = tabs[active].offsetLeft - strip.offsetLeft - 40;
    };

    let last = performance.now();
    const frame = t => {
      const dt = Math.min(50, t - last) / 1000;
      last = t;
      orbits.forEach(o => {
        if (!reduced && visible && !hover) o.a += dt * 9000 / Math.pow(o.rx, 1.5) * .12; // inner orbits move faster
        const [x, y] = place(o);
        if (o.g.classList.contains('is-active')) { beam.setAttribute('x2', x.toFixed(1)); beam.setAttribute('y2', y.toFixed(1)); }
      });
      requestAnimationFrame(frame);
    };
    requestAnimationFrame(frame);

    planets.forEach((p, i) => {
      p.addEventListener('click', () => select(i));
      p.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); select(i); } });
    });
    tabs.forEach((t, i) => t.addEventListener('click', () => select(i)));
    $$('[data-step]', log).forEach(b => b.addEventListener('click', () => select(active + +b.dataset.step)));
    log.addEventListener('mouseenter', () => { hover = true; });
    log.addEventListener('mouseleave', () => { hover = false; });
    if (io) new IntersectionObserver(es => { visible = es[0].isIntersecting; }).observe(log);
    else visible = true;
    // Open on the latest completed mission (the last planet is the empty "next mission")
    select(planets.length - 2);
  }

  // ---------- Satellite tracking: tools fly their group's ground track, the table follows the locked group ----------
  const tracker = $('#tracker');
  if (tracker) {
    const sats = $$('.tsat', tracker).map(g => ({ g, grp: g.dataset.g, x: +g.dataset.x, amp: +g.dataset.amp, ph: +g.dataset.ph }));
    const tracks = $$('.ground-tracks polyline', tracker);
    const tables = $$('.sat-table', tracker);
    const btns = $$('.groups button', tracker);
    const utc = $('#track-utc');
    let seen = false;
    const lock = g => {
      tracks.forEach(t => t.classList.toggle('is-on', t.dataset.g === g));
      sats.forEach(s => s.g.classList.toggle('is-on', s.grp === g));
      tables.forEach(t => t.classList.toggle('is-active', t.dataset.g === g));
      btns.forEach(b => { const on = b.dataset.g === g; b.classList.toggle('is-active', on); b.setAttribute('aria-selected', on); });
    };
    btns.forEach(b => b.addEventListener('click', () => lock(b.dataset.g)));
    tracks.forEach(t => t.addEventListener('click', () => lock(t.dataset.g)));
    sats.forEach(s => s.g.addEventListener('click', () => lock(s.grp)));
    lock('0');

    if (io) new IntersectionObserver(es => { seen = es[0].isIntersecting; }).observe(tracker);
    else seen = true;
    let last = performance.now();
    const fly = t => {
      const dt = Math.min(50, t - last) / 1000;
      last = t;
      if (seen && !reduced) {
        sats.forEach(s => {
          s.x = (s.x + dt * 22) % 1000;
          const y = 300 + s.amp * Math.sin(s.x / 1000 * 2 * Math.PI + s.ph);
          s.g.setAttribute('transform', `translate(${s.x.toFixed(1)} ${y.toFixed(1)})`);
        });
      }
      requestAnimationFrame(fly);
    };
    requestAnimationFrame(fly);
    if (utc) setInterval(() => { utc.textContent = 'UTC ' + new Date().toISOString().slice(11, 19); }, 1000);
  }

  // ---------- Active nav link ----------
  const sections = ['launch', 'missions', 'orbits', 'starmap', 'transmissions', 'comms'].map(id => document.getElementById(id));
  const navLinks = $$('#nav a');
  const onScroll = () => {
    if (sections[0]) {
      let here = -1;
      sections.forEach((s, i) => { if (s && s.getBoundingClientRect().top < innerHeight * .35) here = i; });
      navLinks.forEach((a, i) => a.classList.toggle('is-here', i === here));
    }
  };
  addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // ---------- Landing sites: Earth above, telescope on the rocks (bottom right) ----------
  // The ground rolls toward you; satellites orbit underneath; a hidden focus line
  // lights up each place as it passes, and the telescope follows it.
  if ($('#earth-view')) {
    const PLACES = JSON.parse($('#earth-view').dataset.places);
    // Small deterministic random generator, so the scenes look the same on every load
    const rng = seed => () => { seed = (seed * 1664525 + 1013904223) >>> 0; return seed / 4294967296; };
    const gauss = r => (r() + r() + r() + r() - 2) / 2;

    // Sets up a canvas in "scene units" (w x h) that stays sharp on any screen
    const scene = (canvas, w, h) => {
      const ctx = canvas.getContext('2d');
      const fit = () => {
        const dpr = Math.min(devicePixelRatio || 1, 2);
        canvas.width = Math.round(canvas.clientWidth * dpr);
        canvas.height = Math.round(canvas.clientWidth * h / w * dpr);
        ctx.setTransform(canvas.width / w, 0, 0, canvas.height / h, 0, 0);
      };
      fit();
      addEventListener('resize', fit);
      // Mouse position in scene units
      const toScene = e => { const r = canvas.getBoundingClientRect(); return [(e.clientX - r.left) / r.width * w, (e.clientY - r.top) / r.height * h]; };
      return { ctx, toScene };
    };
    const onScreen = (el, cb) => new IntersectionObserver(es => cb(es[0].isIntersecting)).observe(el);
      const W = 1000, H = 400, EX = 304, EY = -687, ER = 900; // Earth sits lower left, so the horizon tilts up towards the sun
      const BAND = [-0.6, 0.85];           // angles that wrap; both ends are outside the frame
      const FOCUS = 0.2;                   // angle of the (hidden) focus point, about mid-frame
      const SPIN = 0.011;                  // Earth rotation, radians per second
      const box = document.getElementById('earth-view');
      const canvas = document.getElementById('earth-canvas');
      const { ctx, toScene } = scene(canvas, W, H);
      const nameEl = document.getElementById('earth-name'), hintEl = document.getElementById('earth-hint');
      const r = rng(11);
      const span = BAND[1] - BAND[0];
      const wrap = a => BAND[0] + ((a - BAND[0]) % span + span) % span;
      const at = (a, d) => [EX + (ER - d) * Math.sin(a), EY + (ER - d) * Math.cos(a)]; // point at angle a, depth d below the surface

      // Places spread evenly round the band, each with a little cluster of lights
      const slots = PLACES.map((_, i) => i).sort(() => r() - .5);
      const cities = PLACES.map((p, i) => ({
        p, x0: 40 + (slots[i] + .2 + r() * .6) / PLACES.length * 920, u: (i + r() * .6) / PLACES.length,
        dots: Array.from({ length: 14 }, () => [gauss(r) * 7, gauss(r) * 3.5, .4 + r() * .9]),
        x: 0, y: 0, k: 1, fade: 1, lit: 0,
      }));
      const specks = Array.from({ length: 2200 }, () => [r() * 1000, r(), r() ** 3 * .9 + .15]); // [x, u, alpha]
      // Ground projection: u = 0 sits on the horizon, u = 1 just below the frame.
      // u squared gives perspective (slow and bunched near the horizon, fast near you).
      const ROLL = .035;  // how fast the ground rolls toward you (share of the trip per second)
      const UF = .55;     // hidden focus line, part way down
      const horizonY = x => EY + Math.sqrt(Math.max(0, ER * ER - (x - EX) ** 2)); // lower edge of the Earth
      const ground = (x0, u) => {
        const x = 500 + (x0 - 500) * (.55 + .45 * u); // spread out as they come closer
        const edge = horizonY(x) - 6;
        return [x, -30 + (edge + 30) * u * u]; // from just above the frame down to the edge, speeding up as they come closer
      };
      const bgStars = Array.from({ length: 520 }, () => [r() * Math.PI * 2, ER + 30 + r() * 1500, r() ** 4 * 1.4 + .25, .3 + r() * .7]); // [angle, distance from Earth centre, size, alpha]
      const sats = Array.from({ length: 6 }, (_, k) => ({ name: 'SAT-' + (k + 1), h: 40 + k * 16 + r() * 20, a: BAND[0] + k / 6 * span, spd: .022 + r() * .012, lit: 0 }));

      // Static layers: sky, glare, atmosphere, Earth disk
      const bg = document.createElement('canvas');
      const paintBg = () => {
        bg.width = canvas.width; bg.height = canvas.height;
        const b = bg.getContext('2d');
        b.setTransform(canvas.width / W, 0, 0, canvas.height / H, 0, 0);
        b.fillStyle = '#010208'; b.fillRect(0, 0, W, H);
        let g = b.createRadialGradient(40, 30, 0, 40, 30, 260);
        g.addColorStop(0, 'rgba(255,245,220,.55)'); g.addColorStop(.12, 'rgba(255,220,160,.18)'); g.addColorStop(1, 'rgba(255,200,120,0)');
        b.fillStyle = g; b.fillRect(0, 0, W, H);
        for (let k = 14; k > 0; k--) {
          b.strokeStyle = `rgba(${90 + k * 4},${170 + k * 3},255,${.018 + (14 - k) * .006})`;
          b.lineWidth = k * 2.2;
          b.beginPath(); b.arc(EX, EY, ER + k * 1.1, 0, Math.PI * 2); b.stroke();
        }
        g = b.createRadialGradient(EX - 300, EY + ER - 40, 50, EX, EY, ER);
        g.addColorStop(0, '#0d2447'); g.addColorStop(.12, '#081733'); g.addColorStop(.35, '#040b1d'); g.addColorStop(1, '#02050d');
        b.fillStyle = g; b.beginPath(); b.arc(EX, EY, ER, 0, Math.PI * 2); b.fill();
        g = b.createLinearGradient(EX - ER * .7, 0, EX + ER * .4, 0);
        g.addColorStop(0, 'rgba(200,235,255,.95)'); g.addColorStop(.45, 'rgba(120,200,255,.5)'); g.addColorStop(1, 'rgba(80,160,255,.12)');
        b.strokeStyle = g; b.lineWidth = 2; b.beginPath(); b.arc(EX, EY, ER, 0, Math.PI * 2); b.stroke();
        b.save(); b.beginPath(); b.arc(EX, EY, ER, 0, Math.PI * 2); b.clip();
        g = b.createLinearGradient(0, H, 420, H);
        g.addColorStop(0, 'rgba(255,190,120,.28)'); g.addColorStop(1, 'rgba(255,190,120,0)');
        b.strokeStyle = g; b.lineWidth = 16; b.beginPath(); b.arc(EX, EY, ER - 8, 0, Math.PI * 2); b.stroke();
        b.restore();
      };
      paintBg();
      addEventListener('resize', paintBg);

      const drawSat = (x, y, ang, lit) => {
        ctx.save(); ctx.translate(x, y); ctx.rotate(ang);
        if (lit > 0) { // highlight ring while over the focus point
          ctx.globalAlpha = lit; ctx.strokeStyle = '#7fe3ff'; ctx.lineWidth = 1.5; ctx.shadowColor = '#4cc9f0'; ctx.shadowBlur = 14;
          ctx.beginPath(); ctx.arc(0, 0, 24, 0, 7); ctx.stroke(); ctx.globalAlpha = 1;
        }
        ctx.shadowColor = 'rgba(150,190,255,.6)'; ctx.shadowBlur = 8;
        ctx.fillStyle = '#9aa6c2'; ctx.fillRect(-14, -.6, 28, 1.2);
        const pg = ctx.createLinearGradient(0, -5, 0, 5); pg.addColorStop(0, '#4d7fe6'); pg.addColorStop(.5, '#1b3c8f'); pg.addColorStop(1, '#3462c4');
        ctx.fillStyle = pg; ctx.fillRect(-27, -5, 15, 10); ctx.fillRect(12, -5, 15, 10);
        ctx.shadowBlur = 0;
        ctx.strokeStyle = 'rgba(180,205,255,.55)'; ctx.lineWidth = .4;
        [-23, -19, -15.5, 15.5, 19, 23].forEach(px => { ctx.beginPath(); ctx.moveTo(px, -5); ctx.lineTo(px, 5); ctx.stroke(); });
        const fg = ctx.createLinearGradient(-5, -6, 5, 6); fg.addColorStop(0, '#ffe7a0'); fg.addColorStop(.5, '#d39b3b'); fg.addColorStop(1, '#7d5016');
        ctx.fillStyle = fg; ctx.fillRect(-5, -6.5, 10, 13);
        ctx.fillStyle = '#eef1f8'; ctx.beginPath(); ctx.ellipse(0, -9, 4, 1.5, 0, 0, 7); ctx.fill();
        ctx.restore();
      };
      const label = (text, x, y, size, col) => {
        ctx.font = `700 ${size}px "Exo 2", sans-serif`; ctx.textAlign = 'center';
        ctx.lineWidth = 4; ctx.strokeStyle = '#010208'; ctx.strokeText(text, x, y);
        ctx.fillStyle = col; ctx.fillText(text, x, y);
      };

      let hover = -1, seen = false, last = performance.now(), status = '', sky = 0, tilt = 0;
      const ZOOM = 1.03; // slight zoom so the tilted view never shows empty corners
      const PIVOT = [905, 322]; // telescope mount (bottom right)
      let aimX = 0, aimY = 0;
      onScreen(box, v => { seen = v; });
      const frame = t => {
        const dt = Math.min(50, t - last) / 1000; last = t;
        if (seen || t < 500) {
          if (!reduced) {
            cities.forEach(c => { c.u += ROLL * dt; if (c.u > 1) c.u -= 1; });
            specks.forEach(s => { s[1] += ROLL * dt; if (s[1] > 1) s[1] -= 1; });
            sats.forEach(s => { s.a = wrap(s.a + s.spd * dt); });
          }
          // Camera: a slow, gentle roll around the middle of the frame
          if (!reduced) { tilt = Math.sin(t / 7000) * .02; }
          ctx.fillStyle = '#010208'; ctx.fillRect(0, 0, W, H);
          ctx.save();
          ctx.translate(W / 2, H / 2); ctx.rotate(tilt); ctx.scale(ZOOM, ZOOM); ctx.translate(-W / 2, -H / 2);
          ctx.drawImage(bg, 0, 0, W, H);
          // Star field turns round the Earth's centre (stars always sit outside the Earth)
          bgStars.forEach(([ang, dist, sz, al]) => {
            const x = EX + dist * Math.sin(ang + sky), y = EY - dist * Math.cos(ang + sky);
            if (x < -20 || x > W + 20 || y < -20 || y > H) return;
            ctx.globalAlpha = al; ctx.fillStyle = '#dfe8ff'; ctx.beginPath(); ctx.arc(x, y, sz, 0, 7); ctx.fill();
          });
          ctx.globalAlpha = 1;

          // What is over the focus point right now (with a short afterglow)
          const near = a => Math.abs(a - FOCUS);
          let focusCity = -1, focusSat = -1;
          cities.forEach((c, i) => { if (Math.abs(c.u - UF) < .012) focusCity = i; c.lit = Math.max(0, i === focusCity ? 1 : c.lit - dt * .6); });
          sats.forEach((s, k) => { if (near(s.a) < .02) focusSat = k; s.lit = Math.max(0, k === focusSat ? 1 : s.lit - dt * .8); });

          // City specks and clusters ride the surface
          ctx.globalCompositeOperation = 'lighter';
          specks.forEach(([x0, u, al]) => { const [x, y] = ground(x0, u); if (y > 0 && y < H && x > 0 && x < W) { const k = .5 + u * 1.2; ctx.fillStyle = `rgba(255,190,110,${(al * .55 * Math.min(1, u * 6, (1 - u) * 12)).toFixed(3)})`; ctx.fillRect(x, y, .9 * k, .9 * k); } });
          const sp = sats.map(s => at(s.a, -s.h));
          cities.forEach((c, i) => {
            [c.x, c.y] = ground(c.x0, c.u);
            c.k = .45 + c.u * .95; c.fade = Math.min(1, c.u * 6, (1 - c.u) * 12);
            if (c.x < -30 || c.x > W + 30 || c.y < -20 || c.y > H + 10) return;
            ctx.globalAlpha = c.fade;
            // faint link to the nearest satellite
            let best = 0, bd = Infinity;
            sp.forEach(([x, y], k) => { const dd = Math.hypot(x - c.x, y - c.y); if (dd < bd) { bd = dd; best = k; } });
            ctx.strokeStyle = 'rgba(76,201,240,.1)'; ctx.lineWidth = .6;
            ctx.beginPath(); ctx.moveTo(...sp[best]); ctx.lineTo(c.x, c.y); ctx.stroke();
            const media = !!c.p[3], glowUp = Math.max(c.lit, i === hover ? 1 : 0);
            const rad = ((media ? 14 : 10) + glowUp * 18) * c.k;
            const g = ctx.createRadialGradient(c.x, c.y, 0, c.x, c.y, rad);
            g.addColorStop(0, glowUp ? `rgba(160,255,235,${.35 + glowUp * .4})` : media ? 'rgba(255,170,70,.55)' : 'rgba(255,190,110,.38)');
            g.addColorStop(1, 'rgba(255,150,60,0)');
            ctx.fillStyle = g; ctx.beginPath(); ctx.arc(c.x, c.y, rad, 0, 7); ctx.fill();
            c.dots.forEach(([dx, dy, s]) => { ctx.fillStyle = media ? 'rgba(255,215,140,.9)' : 'rgba(255,225,170,.75)'; ctx.fillRect(c.x + dx * c.k, c.y + dy * c.k, s * c.k, s * c.k); });
            ctx.fillStyle = glowUp ? '#fff' : '#ffeccc'; ctx.beginPath(); ctx.arc(c.x, c.y, (1.5 + glowUp * 1.5) * c.k, 0, 7); ctx.fill();
            ctx.globalAlpha = 1;
          });

          // Link beam when a satellite and a place are both at the focus point
          const linked = focusCity >= 0 && focusSat >= 0;
          if (linked) {
            const c = cities[focusCity], [sx, sy] = sp[focusSat];
            ctx.strokeStyle = 'rgba(170,250,240,.95)'; ctx.lineWidth = 2.5; ctx.shadowColor = '#5eead4'; ctx.shadowBlur = 16;
            ctx.beginPath(); ctx.moveTo(sx, sy); ctx.lineTo(c.x, c.y); ctx.stroke(); ctx.shadowBlur = 0;
          }
          ctx.globalCompositeOperation = 'source-over';

          sp.forEach(([x, y], k) => { if (x > -40 && x < W + 40 && y < H + 40) drawSat(x, y, -sats[k].a, sats[k].lit); });

          // Labels for whatever is lit
          cities.forEach((c, i) => { const v = Math.max(c.lit, i === hover ? 1 : 0); if (v > .05) { ctx.globalAlpha = v; label(c.p[0], c.x, c.y + 26, 15, c.p[3] ? '#ffd27a' : '#fff'); ctx.globalAlpha = 1; } });
          sats.forEach((s, k) => { if (s.lit > .05) { ctx.globalAlpha = s.lit; label(s.name, sp[k][0], sp[k][1] - 28, 12, '#7fe3ff'); ctx.globalAlpha = 1; } });

          ctx.restore();

          // ---- Foreground (does not sway): light beam, rocks, tripod and telescope ----
          // Aim at the brightest lit place, or at the focus point when nothing is lit
          const litI = cities.reduce((bI, c, k) => (c.lit > .25 && (bI < 0 || c.lit > cities[bI].lit) ? k : bI), -1);
          const [tx, ty] = litI >= 0 ? [cities[litI].x, cities[litI].y] : at(FOCUS, 70);
          if (!aimX) { aimX = tx; aimY = ty; }
          const ease = reduced ? 1 : Math.min(1, dt * 2.5);
          aimX += (tx - aimX) * ease; aimY += (ty - aimY) * ease;
          // Keep the telescope pointing left and a little up (3 to 60 degrees above the horizon)
          let aimAng = Math.atan2(aimY - PIVOT[1], aimX - PIVOT[0]);
          if (aimAng > 0) aimAng = -Math.PI + .05; // target below the horizon: stay level
          const th = Math.min(-Math.PI + 1.05, Math.max(-Math.PI + .05, aimAng));
          const lens = [PIVOT[0] + Math.cos(th) * 122, PIVOT[1] + Math.sin(th) * 122];

          // Faint beam from the lens to what the telescope is looking at
          const nx = -Math.sin(th), ny = Math.cos(th);
          const bg2 = ctx.createLinearGradient(lens[0], lens[1], aimX, aimY);
          bg2.addColorStop(0, 'rgba(160,250,235,.16)'); bg2.addColorStop(1, 'rgba(160,250,235,.02)');
          ctx.fillStyle = bg2;
          ctx.beginPath();
          ctx.moveTo(lens[0] + nx * 8, lens[1] + ny * 8); ctx.lineTo(aimX + nx * 26, aimY + ny * 26);
          ctx.lineTo(aimX - nx * 26, aimY - ny * 26); ctx.lineTo(lens[0] - nx * 8, lens[1] - ny * 8);
          ctx.closePath(); ctx.fill();
          if (litI >= 0) { // soft ring where it is looking
            ctx.strokeStyle = 'rgba(160,250,235,.45)'; ctx.lineWidth = 1;
            ctx.beginPath(); ctx.arc(aimX, aimY, 22, 0, 7); ctx.stroke();
          }

          // Rocky ledge with warm light along the top edge
          const rockTop = [[700, 400], [735, 386], [770, 378], [806, 362], [842, 356], [872, 344], [905, 350], [934, 338], [962, 334], [1000, 328], [1000, 400]];
          const rg = ctx.createLinearGradient(0, 330, 0, 400);
          rg.addColorStop(0, '#3a2a22'); rg.addColorStop(.35, '#22180f'); rg.addColorStop(1, '#0b0705');
          ctx.fillStyle = rg; ctx.beginPath(); ctx.moveTo(...rockTop[0]); rockTop.slice(1).forEach(q => ctx.lineTo(...q)); ctx.closePath(); ctx.fill();
          ctx.strokeStyle = 'rgba(255,170,110,.45)'; ctx.lineWidth = 1.5;
          ctx.beginPath(); ctx.moveTo(...rockTop[0]); rockTop.slice(1, -1).forEach(q => ctx.lineTo(...q)); ctx.stroke();
          // a few cracks and boulders for texture
          ctx.strokeStyle = 'rgba(0,0,0,.55)'; ctx.lineWidth = 1;
          [[780, 380, 800, 398], [858, 360, 850, 392], [945, 342, 965, 380], [985, 335, 975, 372]].forEach(([a1, b1, a2, b2]) => { ctx.beginPath(); ctx.moveTo(a1, b1); ctx.lineTo(a2, b2); ctx.stroke(); });

          // Tripod
          ctx.strokeStyle = '#c9ced8'; ctx.lineWidth = 2.6; ctx.lineCap = 'round';
          [[862, 372], [905, 380], [950, 366]].forEach(([fx2, fy2]) => { ctx.beginPath(); ctx.moveTo(PIVOT[0], PIVOT[1] + 6); ctx.lineTo(fx2, fy2); ctx.stroke(); });
          ctx.strokeStyle = '#8b93a3'; ctx.lineWidth = 1.2; // spreader
          ctx.beginPath(); ctx.moveTo(880, 352); ctx.lineTo(930, 350); ctx.stroke();
          ctx.lineCap = 'butt';
          ctx.fillStyle = '#2a2e38'; ctx.fillRect(PIVOT[0] - 7, PIVOT[1] - 2, 14, 10); // mount head

          // Telescope tube, drawn along the aim direction
          ctx.save(); ctx.translate(...PIVOT); ctx.rotate(th);
          const tg = ctx.createLinearGradient(0, -8, 0, 8);
          tg.addColorStop(0, '#ffffff'); tg.addColorStop(.45, '#dfe3ea'); tg.addColorStop(1, '#9aa1ae');
          ctx.fillStyle = tg;
          ctx.beginPath(); ctx.moveTo(-48, -5.5); ctx.lineTo(104, -7.5); ctx.lineTo(104, 7.5); ctx.lineTo(-48, 5.5); ctx.closePath(); ctx.fill();
          ctx.fillStyle = '#1a1c22'; ctx.fillRect(102, -10, 20, 20);          // dew shield / front cap
          ctx.fillStyle = '#3a3f4b'; ctx.fillRect(118, -10, 4, 20);
          ctx.fillStyle = '#9aa1ae'; ctx.fillRect(-14, -6.5, 4, 13); ctx.fillRect(40, -7, 4, 14); // tube rings
          ctx.fillStyle = '#1a1c22'; ctx.fillRect(-52, -4, 6, 8);              // back cap
          ctx.fillStyle = '#23262e'; ctx.fillRect(-40, -15, 6, 10);            // eyepiece
          ctx.fillStyle = '#cfd4dc'; ctx.fillRect(10, -13, 30, 3.5);           // finder scope
          ctx.restore();
          // A glint on the lens when something is in view
          if (litI >= 0) {
            const gl = ctx.createRadialGradient(lens[0], lens[1], 0, lens[0], lens[1], 10);
            gl.addColorStop(0, 'rgba(190,255,245,.8)'); gl.addColorStop(1, 'rgba(190,255,245,0)');
            ctx.fillStyle = gl; ctx.beginPath(); ctx.arc(lens[0], lens[1], 10, 0, 7); ctx.fill();
          }

          // Bottom bar
          if (hover < 0) {
            const lastCity = cities.reduce((b, c, i) => (c.lit > (b < 0 ? 0 : cities[b].lit) ? i : b), -1);
            const next = linked ? `Link established · ${sats[focusSat].name} ⇄ ${cities[focusCity].p[0]}`
              : lastCity >= 0 ? cities[lastCity].p[0] : focusSat >= 0 ? sats[focusSat].name + ' overhead' : 'Watching the ground roll in';
            if (next !== status) { status = next; nameEl.textContent = next; hintEl.innerHTML = lastCity >= 0 && cities[lastCity].p[3] ? '<span class="hint">▶ Has footage, click it to watch</span>' : 'Places pass by as the Earth turns'; }
          }
        }
        requestAnimationFrame(frame);
      };
      requestAnimationFrame(frame);

      canvas.addEventListener('mousemove', e => {
        const [vx, vy] = toScene(e);
        const dx = vx - W / 2, dy = vy - H / 2, cs = Math.cos(-tilt), sn = Math.sin(-tilt);
        const mx = W / 2 + (dx * cs - dy * sn) / ZOOM, my = H / 2 + (dx * sn + dy * cs) / ZOOM;
        let best = -1, bd = 16;
        cities.forEach((c, i) => { const d = Math.hypot(c.x - mx, c.y - my); if (d < bd) { bd = d; best = i; } });
        if (best === hover) return;
        hover = best; status = '';
        box.classList.toggle('is-pointer', best >= 0 && !!cities[best].p[3]);
        if (best >= 0) { nameEl.textContent = cities[best].p[0]; hintEl.innerHTML = cities[best].p[3] ? '<span class="hint">▶ Click to watch</span>' : ''; }
      });
      canvas.addEventListener('mouseleave', () => { hover = -1; status = ''; box.classList.remove('is-pointer'); });
      // Click opens the hovered place, or the one currently lit at the focus point
      canvas.addEventListener('click', () => {
        const i = hover >= 0 ? hover : cities.reduce((b, c, k) => (c.lit > .3 && (b < 0 || c.lit > cities[b].lit) ? k : b), -1);
        if (i >= 0 && cities[i].p[3]) window.open(cities[i].p[3], '_blank', 'noopener');
      });
  
  }

  // ---------- YouTube lite embeds ----------
  $$('.yt-lite').forEach(btn => btn.addEventListener('click', () => {
    const f = document.createElement('iframe');
    f.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(btn.dataset.id)}?autoplay=1&rel=0`;
    f.title = btn.getAttribute('aria-label') || 'YouTube video';
    f.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
    f.allowFullscreen = true;
    btn.replaceChildren(f);
  }, { once: true }));

  // ---------- Blog filters ----------
  const filters = $('#blog-filters');
  filters?.addEventListener('click', e => {
    const chip = e.target.closest('[data-filter]');
    if (!chip) return;
    $$('.chip', filters).forEach(c => c.classList.toggle('is-active', c === chip));
    const f = chip.dataset.filter;
    $$('[data-category]').forEach(el => { el.hidden = f !== 'all' && el.dataset.category !== f; });
  });

  // ---------- Copy buttons ----------
  $$('[data-copy]').forEach(btn => btn.addEventListener('click', async () => {
    const icon = btn.querySelector('i');
    const old = icon?.className;
    try {
      await navigator.clipboard.writeText(btn.dataset.copy);
      if (icon) { icon.className = 'fas fa-check'; setTimeout(() => { icon.className = old; }, 1500); }
    } catch (e) { prompt('Copy this:', btn.dataset.copy); }
  }));

  // ---------- Comms form: validate, then a 3-2-1 launch before it is sent ----------
  const form = $('#comms-form');
  const countdown = $('#countdown');
  if (form && countdown) {
    let launched = false;
    form.addEventListener('submit', e => {
      if (launched) return;
      e.preventDefault();
      let ok = true;
      $$('[required]', form).forEach(el => {
        const bad = !el.value.trim() || (el.type === 'email' && !/^\S+@\S+\.\S+$/.test(el.value));
        el.closest('.field').classList.toggle('is-bad', bad);
        if (bad && ok) { el.focus(); ok = false; }
      });
      if (!ok) return;
      launched = true;
      form.querySelector('button[type=submit]').disabled = true;
      if (reduced) { form.submit(); return; }
      const num = $('#count-num');
      let n = 3;
      countdown.hidden = false;
      const step = () => {
        if (n > 0) { num.textContent = n--; setTimeout(step, 800); return; }
        num.textContent = 'Liftoff';
        num.style.fontSize = 'clamp(3rem, 12vw, 6rem)';
        countdown.classList.add('liftoff');
        setTimeout(() => form.submit(), 900);
      };
      step();
    });
  }
})();
