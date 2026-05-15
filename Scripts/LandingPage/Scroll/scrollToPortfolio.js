// scroll-to-portfolio.js — scroll when skill-box clicked or portfolio link
function scrollToPortfolio() {
  const button = document.querySelector('a.portfolio-link');
  if (!button) return;
  button.scrollIntoView({ behavior: 'smooth', block: 'center' });
  button.focus();
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.skill-box').forEach(box => {
    box.addEventListener('click', scrollToPortfolio);
  });
});
