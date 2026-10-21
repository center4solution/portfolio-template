// Chain — v6. No frameworks, no build step.
(() => {
  'use strict';
  const doc = document.documentElement;
  doc.classList.add('js');
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => [...el.querySelectorAll(s)];
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;

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
    const step = t => { const k = Math.min(1, (t - t0) / 1100); el.textContent = Math.round(end * (1 - Math.pow(1 - k, 3))); if (k < 1) requestAnimationFrame(step); };
    requestAnimationFrame(step);
  };
  const counters = $$('[data-count]');
  if (io) {
    const cio = new IntersectionObserver(es => es.forEach(en => { if (en.isIntersecting) { runCount(en.target); cio.unobserve(en.target); } }));
    counters.forEach(c => cio.observe(c));
  } else counters.forEach(runCount);

  // ---------- "Last block Ns ago" ----------
  const lastBlock = $('#last-block');
  if (lastBlock) {
    let s = 0;
    setInterval(() => { s = s >= 12 ? 0 : s + 1; lastBlock.textContent = s; }, 1000);
  }

  // ---------- Active nav link ----------
  const sections = ['overview', 'blocks', 'wallet', 'nodes', 'txns', 'send'].map(id => document.getElementById(id));
  const navLinks = $$('#nav a');
  if (sections[0]) {
    const spy = () => {
      let here = -1;
      sections.forEach((s, i) => { if (s && s.getBoundingClientRect().top < innerHeight * .35) here = i; });
      navLinks.forEach((a, i) => a.classList.toggle('is-here', i === here));
    };
    addEventListener('scroll', spy, { passive: true });
    spy();
  }

  // ---------- Page search: dims everything that doesn't match ----------
  const search = $('#chain-search');
  if (search) {
    const items = $$('[data-search]');
    search.addEventListener('input', () => {
      const q = search.value.trim().toLowerCase();
      let first = null;
      items.forEach(el => {
        const hit = !q || el.dataset.search.includes(q);
        el.classList.toggle('is-dim', !hit);
        el.classList.toggle('is-match', !!q && hit);
        if (q && hit && !first) first = el;
      });
      const empty = $('.tx-empty');
      if (empty) empty.hidden = !q || $$('.tx-row').some(r => !r.classList.contains('is-dim'));
    });
    search.addEventListener('keydown', e => {
      if (e.key === 'Enter') { const m = $('.is-match'); m?.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' }); }
      if (e.key === 'Escape') { search.value = ''; search.dispatchEvent(new Event('input')); }
    });
    addEventListener('keydown', e => {
      if (e.key === '/' && !/input|textarea/i.test(document.activeElement.tagName)) { e.preventDefault(); search.focus(); }
    });
  }

  // ---------- Node map <-> list ----------
  const target = $('#node-target');
  const mapNodes = $$('.node[data-node]');
  const listNodes = $$('.node-list li[data-node]');
  const lightNode = (i, on) => {
    mapNodes.forEach(n => n.classList.toggle('is-on', on && n.dataset.node === i));
    listNodes.forEach(l => l.classList.toggle('is-on', on && l.dataset.node === i));
    if (target) {
      const li = listNodes.find(l => l.dataset.node === i);
      target.textContent = on && li ? '◉ ' + li.querySelector('.node-name').firstChild.nextSibling.textContent.trim() : 'Hover a node';
    }
  };
  [...mapNodes, ...listNodes].forEach(el => {
    el.addEventListener('mouseenter', () => lightNode(el.dataset.node, true));
    el.addEventListener('mouseleave', () => lightNode(el.dataset.node, false));
  });
  mapNodes.forEach(n => n.addEventListener('click', () => {
    const li = listNodes.find(l => l.dataset.node === n.dataset.node);
    const link = li?.querySelector('a');
    if (link) window.open(link.href, '_blank', 'noopener');
    else li?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }));

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

  // ---------- Send form: validate, then confirm like a wallet ----------
  const form = $('#send-form');
  const modal = $('#confirm');
  if (form && modal) {
    let confirmed = false;
    const close = () => { modal.hidden = true; form.querySelector('button[type=submit]').focus(); };
    form.addEventListener('submit', e => {
      if (confirmed) return;
      e.preventDefault();
      let ok = true;
      $$('[required]', form).forEach(el => {
        const bad = !el.value.trim() || (el.type === 'email' && !/^\S+@\S+\.\S+$/.test(el.value));
        el.closest('.field').classList.toggle('is-bad', bad);
        if (bad && ok) { el.focus(); ok = false; }
      });
      if (!ok) return;
      $$('[data-c]', modal).forEach(dd => { dd.textContent = form.elements[dd.dataset.c].value.trim(); });
      modal.hidden = false;
      $('[data-confirm]', modal).focus();
    });
    $('[data-cancel]', modal).addEventListener('click', close);
    modal.addEventListener('click', e => { if (e.target === modal) close(); });
    addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.hidden) close(); });
    $('[data-confirm]', modal).addEventListener('click', () => {
      confirmed = true;
      const b = $('[data-confirm]', modal);
      b.disabled = true;
      b.innerHTML = '<span class="spinner"></span> Broadcasting…';
      form.submit();
    });
  }
})();
