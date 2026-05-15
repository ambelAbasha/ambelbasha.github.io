document.addEventListener('DOMContentLoaded', () => {
  /* stamp --box-index on each skill box so CSS stagger delay works */
  document.querySelectorAll('.skills .skill-box').forEach((box, i) => {
    box.style.setProperty('--box-index', i);
  });

  const seen   = new WeakSet();
  const allEls = document.querySelectorAll('.about, .skills, .skills .skill-box');
  allEls.forEach(el => el.classList.add('hidden'));

  /* ── Entry observer — fly-in on first scroll-into-view ─────────────────── */
  const entryIO = new IntersectionObserver((entries) => {
    entries.forEach(({ target, isIntersecting }) => {
      if (!isIntersecting) return;
      seen.add(target);                        /* mark as genuinely seen */
      target.classList.remove('hidden', 'leaving');
      target.classList.add('visible');
      entryIO.unobserve(target);               /* fire once; re-armed after a leaving cycle */
    });
  /* rootMargin '0px' + threshold 0 — fires as soon as any pixel enters the
     viewport. Percentage rootMargins have a floating-point rounding bug in
     Chrome that can prevent the callback from ever firing for small elements
     (like the footer) that sit right on the boundary.                      */
  }, { rootMargin: '0px', threshold: 0 });

  allEls.forEach(el => entryIO.observe(el));

  /* ── Exit observer — collapse back to depth on reverse scroll ───────────── */
  /* rootMargin '0px 0px 80px 0px' extends the virtual viewport bottom by 80 px,
     so a section must slip 80 px past the fold before the observer fires —
     preventing premature collapse while it's still clearly in view.
     threshold: 0  →  fire only when the element is fully outside that zone.    */
  const sections = document.querySelectorAll('.about, .skills');

  const exitIO = new IntersectionObserver((entries) => {
    entries.forEach(({ target, isIntersecting, boundingClientRect }) => {
      if (isIntersecting) return;
      if (!seen.has(target)) return;           /* never been visible — ignore */

      /* top <= 0  →  element exited from above (user scrolled DOWN past it).
         Do not collapse; leave it for the entryIO unobserve to handle.        */
      if (boundingClientRect.top <= 0) return;

      /* top > 0  →  element slipped below the fold (user scrolled UP).
         Trigger depth-retreat departure.                                       */
      const boxes = target.querySelectorAll('.skill-box');

      target.classList.remove('visible');
      target.classList.add('leaving');
      boxes.forEach(b => { b.classList.remove('visible'); b.classList.add('leaving'); });

      setTimeout(() => {
        if (!target.classList.contains('leaving')) return; /* aborted if re-entered */
        target.classList.replace('leaving', 'hidden');
        boxes.forEach(b => {
          if (b.classList.contains('leaving')) b.classList.replace('leaving', 'hidden');
        });
        seen.delete(target);                   /* clear so next fly-in re-marks it */
        entryIO.observe(target);
        boxes.forEach(b => entryIO.observe(b));
      }, 1150);
    });
  }, { threshold: 0, rootMargin: '0px 0px 80px 0px' });

  sections.forEach(el => exitIO.observe(el));
});
