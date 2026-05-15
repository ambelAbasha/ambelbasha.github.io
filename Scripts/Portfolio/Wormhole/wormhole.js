(function () {
  const canvas = document.getElementById('wormhole-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  const RINGS      = 28;
  const NUM_STRUTS = 8;
  const BASE_SPEED = 0.15;

  let W, H, cx, cy;
  const rings = Array.from({ length: RINGS }, (_, i) => ({ phase: i / RINGS }));

  let scrollVel   = 0;
  let lastScrollY = window.scrollY;

  let mx = 0, my = 0;
  window.addEventListener('mousemove', e => {
    mx = (e.clientX / window.innerWidth  - 0.5) * 2;
    my = (e.clientY / window.innerHeight - 0.5) * 2;
  }, { passive: true });

  function resize() {
    W  = canvas.width  = window.innerWidth;
    H  = canvas.height = window.innerHeight;
    cx = W / 2;
    cy = H * 0.42;
  }

  /* ══════════════════════════════════════════════════════════════
     makeReveal — generic wormhole fly-in factory
     overlayEl : the fixed .wh-reveal-overlay element
     zoneEl    : the scroll zone whose height drives progress
                 (wormhole-hero for web, .wh-zone divs for others)
  ══════════════════════════════════════════════════════════════ */
  function makeReveal(overlayEl, zoneEl) {
    return function update() {
      if (!overlayEl || !zoneEl) return;

      const zoneTop    = zoneEl.offsetTop;
      const zoneH      = zoneEl.offsetHeight;
      const viewportH  = window.innerHeight;
      const scrollable = Math.max(1, zoneH - viewportH);
      const raw        = (window.scrollY - zoneTop) / scrollable;
      const prog       = Math.max(0, Math.min(1, raw));

      const ease     = 1 - Math.pow(1 - prog, 3);
      const tz       = (1 - ease) * -1400;
      /* "Stop" bump — card overshoots scale 1.0 by +0.045 as it arrives
         at the dwell zone (prog 0.40-0.90), peaking at prog ≈ 0.65.
         Gives a crisp snap-into-place feeling at the full-reveal point. */
      const dwell    = Math.max(0, Math.min(1, (prog - 0.40) / 0.50));
      const stopBump = ease > 0.82 ? Math.sin(Math.PI * dwell) * 0.045 : 0;
      const scale    = 0.03 + ease * 0.97 + stopBump;
      const alpha    = Math.min(1, prog * 3.0);

      /* fade out as scroll passes the bottom edge of this zone.
         60 px exit distance: the overlay reaches opacity:0 when the viewport
         has scrolled 60 px past the zone bottom. The footer margin-top is
         6rem ≈ 96 px, so the overlay is fully gone 36 px before the footer
         enters the viewport — guaranteeing it never covers the footer. */
      const zoneBtm  = zoneTop + zoneH;
      const beyondPx = Math.max(0, window.scrollY + viewportH - zoneBtm);
      const exitFade = Math.max(0, 1 - beyondPx / 60);

      const finalAlpha = alpha * exitFade;

      overlayEl.style.opacity    = finalAlpha;
      /* When fully hidden: visibility:hidden removes the overlay from paint;
         will-change:auto drops the GPU compositor layer Chrome allocates for
         will-change:transform,opacity elements — without this, the promoted
         layer can still render above the footer despite z-index:15 < z-index:50.
         When animating: clearing the inline style lets the CSS will-change
         property restore the compositor layer for smooth animation. */
      const hidden = finalAlpha < 0.01;
      overlayEl.style.visibility = hidden ? 'hidden' : '';
      overlayEl.style.willChange = hidden ? 'auto' : '';
      overlayEl.style.transform  =
        `translate(-50%, -50%) perspective(1200px) translateZ(${tz.toFixed(1)}px) scale(${scale.toFixed(4)})`;
      overlayEl.style.pointerEvents = finalAlpha > 0.85 ? 'auto' : 'none';

      const glow = (ease * exitFade * 28).toFixed(1);
      overlayEl.style.filter = `drop-shadow(0 0 ${glow}px rgba(15,164,175,0.50))`;
    };
  }

  /* ── Wire up all five section reveals ── */
  const reveals = [
    makeReveal(
      document.getElementById('wh-web-reveal'),
      document.getElementById('wormhole-hero')
    ),
    makeReveal(
      document.getElementById('wh-database-reveal'),
      document.getElementById('database')
    ),
    makeReveal(
      document.getElementById('wh-cyber-reveal'),
      document.getElementById('cyber')
    ),
    makeReveal(
      document.getElementById('wh-linux-reveal'),
      document.getElementById('linux')
    ),
    makeReveal(
      document.getElementById('wh-metadata-reveal'),
      document.getElementById('metadata')
    ),
  ];

  function updateAllReveals() {
    reveals.forEach(fn => fn());
  }

  window.addEventListener('scroll', () => {
    const dy    = window.scrollY - lastScrollY;          /* signed: +down, −up */
    scrollVel   = Math.max(-1.0, Math.min(1.0, dy * 0.022));
    lastScrollY = window.scrollY;
    updateAllReveals();
  }, { passive: true });

  /* ── Render loop ── */
  let prev = 0;
  function tick(ts) {
    const dt = Math.min((ts - prev) / 1000, 0.05);
    prev = ts;

    scrollVel *= 0.88;
    const speed = BASE_SPEED + scrollVel * 0.65;

    const vcx = cx + mx * W * 0.025;
    const vcy = cy + my * H * 0.018;

    ctx.clearRect(0, 0, W, H);

    /* ambient haze */
    const haze = ctx.createRadialGradient(vcx, vcy, 0, vcx, vcy, W * 0.38);
    haze.addColorStop(0,   'rgba(15,164,175,0.18)');
    haze.addColorStop(0.4, 'rgba(15,164,175,0.05)');
    haze.addColorStop(1,   'transparent');
    ctx.fillStyle = haze;
    ctx.fillRect(0, 0, W, H);

    /* bright core — exit light at tunnel end */
    const core = ctx.createRadialGradient(vcx, vcy, 0, vcx, vcy, W * 0.05);
    core.addColorStop(0, 'rgba(15,164,175,0.30)');
    core.addColorStop(1, 'transparent');
    ctx.fillStyle = core;
    ctx.fillRect(0, 0, W, H);

    /* 3-D tunnel struts — 8 radial ribs from vanishing point */
    const strutLen = Math.hypot(W, H) * 0.8;
    for (let s = 0; s < NUM_STRUTS; s++) {
      const angle = (s / NUM_STRUTS) * Math.PI * 2;
      const ex    = vcx + Math.cos(angle) * strutLen;
      const ey    = vcy + Math.sin(angle) * strutLen;

      const grd = ctx.createLinearGradient(vcx, vcy, ex, ey);
      grd.addColorStop(0,    'rgba(15,164,175,0)');
      grd.addColorStop(0.05, 'rgba(15,164,175,0.04)');
      grd.addColorStop(0.5,  'rgba(15,164,175,0.11)');
      grd.addColorStop(0.85, 'rgba(15,164,175,0.07)');
      grd.addColorStop(1,    'rgba(15,164,175,0)');

      ctx.beginPath();
      ctx.moveTo(vcx, vcy);
      ctx.lineTo(ex, ey);
      ctx.strokeStyle = grd;
      ctx.lineWidth   = 0.8;
      ctx.stroke();
    }

    /* rings */
    rings.forEach(ring => {
      ring.phase = ((ring.phase + dt * speed) % 1 + 1) % 1;
      const t   = ring.phase;
      const scl = Math.pow(t, 1.35);
      const rx  = W * 0.74 * scl;
      const ry  = H * 0.44 * scl;
      const a   = Math.sin(t * Math.PI) * 0.42;

      if (rx < 0.5) return;

      ctx.beginPath();
      ctx.ellipse(vcx, vcy, rx, ry, 0, 0, Math.PI * 2);
      ctx.strokeStyle = `rgba(15,164,175,${a.toFixed(3)})`;
      ctx.lineWidth   = 1;
      ctx.stroke();
    });

    requestAnimationFrame(tick);
  }

  window.addEventListener('resize', () => { resize(); updateAllReveals(); });
  resize();
  updateAllReveals();
  requestAnimationFrame(tick);

  /* ── Active-section tracking — highlights menu item for the visible card ── */
  const sectionZones = [
    { id: 'wormhole-hero', key: 'web' },
    { id: 'database',      key: 'database' },
    { id: 'cyber',         key: 'cyber' },
    { id: 'linux',         key: 'linux' },
    { id: 'metadata',      key: 'metadata' },
  ];

  function syncActiveMenu() {
    let activeKey = null;
    for (const { id, key } of sectionZones) {
      const el = document.getElementById(id);
      if (!el) continue;
      const scrollable = Math.max(1, el.offsetHeight - window.innerHeight);
      const prog = Math.max(0, Math.min(1, (window.scrollY - el.offsetTop) / scrollable));
      if (prog >= 0.40 && prog <= 0.92) { activeKey = key; break; }
    }
    document.querySelectorAll('.menu-item[data-section]').forEach(el => {
      el.classList.toggle('active', el.dataset.section === activeKey);
    });
  }

  /* ── Scroll stop — gently snaps to the reveal point when scrolling pauses ── */
  let stopSnapTimer = null;
  function scheduleSnap() {
    clearTimeout(stopSnapTimer);
    stopSnapTimer = setTimeout(() => {
      for (const { id } of sectionZones) {
        const el = document.getElementById(id);
        if (!el) continue;
        const scrollable = Math.max(1, el.offsetHeight - window.innerHeight);
        const prog = (window.scrollY - el.offsetTop) / scrollable;
        if (prog >= 0.53 && prog <= 0.77) {
          const target = el.offsetTop + scrollable * 0.65;
          if (Math.abs(target - window.scrollY) > 4)
            window.scrollTo({ top: target, behavior: 'smooth' });
          break;
        }
      }
    }, 200);
  }

  window.addEventListener('scroll', () => { syncActiveMenu(); scheduleSnap(); }, { passive: true });
  syncActiveMenu();

  /* ── 3-D reveal for timeline cards (legacy timeline — excluded from overlays) ── */
  const revealObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('revealed');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.timeline-card').forEach(el => {
    if (!el.closest('.wh-reveal-overlay')) revealObserver.observe(el);
  });
})();
