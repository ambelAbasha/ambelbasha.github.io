// Function to check if an element is in view
function isInView(element, threshold = 100) {
  const rect = element.getBoundingClientRect();
  return (
    rect.top <= (window.innerHeight || document.documentElement.clientHeight) + threshold &&
    rect.bottom >= 0
  );
}

// Function to toggle visibility of elements based on their position in the viewport
function toggleVisibilityOnScroll() {
  const elements = document.querySelectorAll('.project-column'); // Select all project columns

  elements.forEach((element) => {
    if (isInView(element)) {
      element.classList.add('visible'); // Add the 'visible' class to make the element visible
    } else {
      element.classList.remove('visible'); // Remove the 'visible' class to hide the element
    }
  });
}

// Run the function on page load and scroll
window.addEventListener('scroll', toggleVisibilityOnScroll);
window.addEventListener('load', toggleVisibilityOnScroll);

// Optionally, you could add an initial check to make sure elements are visible on load
toggleVisibilityOnScroll();
