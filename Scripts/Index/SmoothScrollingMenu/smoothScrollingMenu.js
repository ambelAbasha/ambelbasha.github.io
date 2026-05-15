// Smooth scroll for the menu (if you're using openMenu for anchor links)
function openMenu(event, targetId) {
  event.preventDefault();  // Prevent the default anchor link behavior

  // Smooth scroll to the targeted section
  const targetElement = document.querySelector(targetId);

  if (targetElement) {
    targetElement.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
    });
  }
}

// On page load, make sure the "about" section becomes visible smoothly
window.addEventListener("DOMContentLoaded", () => {
  const aboutSection = document.getElementById('about');
  if (aboutSection) {
    aboutSection.classList.add('visible'); // Add visible class to trigger smooth transition
  }
});
