/* ========================================================
   Footer Navigation Hub
   Single icon expands link list with staggered animation
   ======================================================== */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('footer-nav-toggle');
    const list   = document.querySelector('.footer-links');
    if (!toggle || !list) return;

    function openNav() {
      list.classList.add('is-open');
      toggle.classList.add('is-active');
      toggle.setAttribute('aria-expanded', 'true');
    }

    function closeNav() {
      list.classList.remove('is-open');
      toggle.classList.remove('is-active');
      toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      list.classList.contains('is-open') ? closeNav() : openNav();
    });

    document.addEventListener('click', function (e) {
      if (!e.target.closest('.footer-nav-hub')) closeNav();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeNav();
    });
  });
})();
