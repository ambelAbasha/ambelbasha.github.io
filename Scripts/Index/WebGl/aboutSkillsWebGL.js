/* ========================================================
   WebGL-style Canvas 2D effects
   About  → constellation nodes + connecting lines
   Skills → pulsing ring particles
   Footer → soft wave drift
   ======================================================== */
(function () {
  'use strict';

  const PALETTE = ['#0FA4AF', '#F5C74F', '#B8C46E', '#CFAF9D', '#024950'];

  function rand(a, b) { return Math.random() * (b - a) + a; }

  function pickColor() { return PALETTE[Math.floor(Math.random() * PALETTE.length)]; }

  function makeCanvas(parent) {
    const c = document.createElement('canvas');
    c.className = 'section-webgl-canvas';
    c.setAttribute('aria-hidden', 'true');
    parent.insertBefore(c, parent.firstChild);
    return c;
  }

  /* ── About Section — constellation ─────────────────── */
  function initAbout() {
    const section = document.getElementById('about');
    if (!section) return;

    const canvas = makeCanvas(section);
    const ctx    = canvas.getContext('2d');
    let W, H, nodes;

    function resize() {
      W = canvas.width  = section.offsetWidth;
      H = canvas.height = section.offsetHeight;
      buildNodes();
    }

    function buildNodes() {
      const n = Math.min(55, Math.floor((W * H) / 11000));
      nodes = Array.from({ length: n }, () => ({
        x: rand(0, W), y: rand(0, H),
        vx: rand(-0.28, 0.28), vy: rand(-0.28, 0.28),
        r:  rand(1.5, 3),
        color: pickColor(),
      }));
    }

    function tick() {
      ctx.clearRect(0, 0, W, H);

      const LINK = 140;
      for (let i = 0; i < nodes.length; i++) {
        for (let j = i + 1; j < nodes.length; j++) {
          const dx = nodes[i].x - nodes[j].x;
          const dy = nodes[i].y - nodes[j].y;
          const d  = Math.hypot(dx, dy);
          if (d < LINK) {
            ctx.beginPath();
            ctx.moveTo(nodes[i].x, nodes[i].y);
            ctx.lineTo(nodes[j].x, nodes[j].y);
            ctx.strokeStyle = `rgba(15,164,175,${(1 - d / LINK) * 0.22})`;
            ctx.lineWidth   = 0.6;
            ctx.stroke();
          }
        }
      }

      for (const n of nodes) {
        n.x += n.vx; n.y += n.vy;
        if (n.x < 0 || n.x > W) n.vx *= -1;
        if (n.y < 0 || n.y > H) n.vy *= -1;

        const g = ctx.createRadialGradient(n.x, n.y, 0, n.x, n.y, n.r * 6);
        g.addColorStop(0, n.color + 'aa');
        g.addColorStop(1, 'transparent');
        ctx.beginPath();
        ctx.arc(n.x, n.y, n.r * 6, 0, Math.PI * 2);
        ctx.fillStyle = g;
        ctx.fill();

        ctx.beginPath();
        ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
        ctx.fillStyle = n.color;
        ctx.fill();
      }

      requestAnimationFrame(tick);
    }

    resize();
    window.addEventListener('resize', resize);
    tick();
  }

  /* ── Skills Section — pulsing rings ─────────────────── */
  function initSkills() {
    const section = document.getElementById('skills');
    if (!section) return;

    const canvas = makeCanvas(section);
    const ctx    = canvas.getContext('2d');
    let W, H, pts;

    function resize() {
      W = canvas.width  = section.offsetWidth;
      H = canvas.height = section.offsetHeight;
      buildPts();
    }

    function buildPts() {
      const n = Math.min(38, Math.floor((W * H) / 16000));
      pts = Array.from({ length: n }, () => ({
        x: rand(0, W), y: rand(0, H),
        vx: rand(-0.18, 0.18), vy: rand(-0.18, 0.18),
        r:  rand(1, 2.5),
        phase: rand(0, Math.PI * 2),
        speed: rand(0.025, 0.055),
        color: pickColor(),
      }));
    }

    function tick() {
      ctx.clearRect(0, 0, W, H);

      for (const p of pts) {
        p.x += p.vx; p.y += p.vy;
        if (p.x < 0 || p.x > W) p.vx *= -1;
        if (p.y < 0 || p.y > H) p.vy *= -1;
        p.phase += p.speed;

        const wave  = Math.sin(p.phase);
        const ring  = p.r + 5 + wave * 4;
        const alpha = Math.round((0.28 + wave * 0.12) * 255).toString(16).padStart(2, '0');

        ctx.beginPath();
        ctx.arc(p.x, p.y, ring, 0, Math.PI * 2);
        ctx.strokeStyle = p.color + alpha;
        ctx.lineWidth   = 0.9;
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = p.color;
        ctx.fill();
      }

      requestAnimationFrame(tick);
    }

    resize();
    window.addEventListener('resize', resize);
    tick();
  }

  function boot() {
    initAbout();
    initSkills();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
