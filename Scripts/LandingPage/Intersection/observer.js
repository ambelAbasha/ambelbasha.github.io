document.addEventListener('DOMContentLoaded', () => {
  // 1) Collect all the selectors you want to animate
  const selectors = [
    '.section',
    '.about', '.about h2', '.about p',
    '.skills', '.skills h2', '.skills .skill-box',
    '.hero-content h1', '.picture-container', '.hero-subheading'
  ];
  const elements = document.querySelectorAll(selectors.join(','));

  // 2) Set initial hidden state
  elements.forEach(el => {
    el.classList.add('hidden');
  });

  // 3) Create the observer
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      const el = entry.target;
      if (entry.intersectionRatio >= 0.15) {
        // entering viewport → fade in
        el.classList.remove('hidden');
        el.classList.add('visible');
      } else {
        // leaving viewport → fade out
        el.classList.remove('visible');
        el.classList.add('hidden');
      }
    });
  }, {
    root: null,
    rootMargin: '0px 0px -15% 0px',  // trigger 15% before bottom
    threshold: [0, 0.15]
  });

  // 4) Observe each element
  elements.forEach(el => io.observe(el));
});
