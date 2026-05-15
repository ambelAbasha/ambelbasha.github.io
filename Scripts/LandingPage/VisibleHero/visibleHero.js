// JavaScript to add the 'visible' class to the hero section
window.addEventListener('load', function() {
  const hero = document.querySelector('.hero');
  setTimeout(() => {
    hero.classList.add('visible');
  }, 300); // Slight delay (300ms) before making it visible
});
