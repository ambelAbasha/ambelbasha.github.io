(function () {
  /*
   * Each section card becomes fully visible at ~65 % through its scroll zone.
   * prog = 0.65 → alpha = 1, scale ≈ 0.96, exit-fade not yet started.
   */
  const REVEAL_PROG = 0.65;

  /* zone element that drives each section's reveal card */
  const zoneIds = {
    web:      'wormhole-hero',
    database: 'database',
    cyber:    'cyber',
    linux:    'linux',
    metadata: 'metadata',
  };

  function targetScrollY(key) {
    const zoneEl = document.getElementById(zoneIds[key]);
    if (!zoneEl) return null;
    const scrollable = Math.max(1, zoneEl.offsetHeight - window.innerHeight);
    return zoneEl.offsetTop + scrollable * REVEAL_PROG;
  }

  /*
   * Close the burger menu.
   * Dispatching a click on document triggers menuToggler.js's outside-click
   * handler which both removes the open classes AND resets its isOpen flag.
   */
  function closeMenu() {
    document.dispatchEvent(new MouseEvent('click', { bubbles: false }));
  }

  /* Burger menu items */
  document.querySelectorAll('.menu-item[data-section]').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const top = targetScrollY(link.dataset.section);
      if (top !== null) window.scrollTo({ top, behavior: 'smooth' });
      closeMenu();
    });
  });

  /* Hero p-cards (href="#web", "#database", …) */
  document.querySelectorAll('.p-card[href^="#"]').forEach(card => {
    card.addEventListener('click', e => {
      e.preventDefault();
      const key = card.getAttribute('href').slice(1);
      const top = targetScrollY(key);
      if (top !== null) window.scrollTo({ top, behavior: 'smooth' });
    });
  });

})();
