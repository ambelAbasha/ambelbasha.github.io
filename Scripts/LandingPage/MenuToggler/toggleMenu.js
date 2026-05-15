const burger = document.getElementById("landing-burger-menu");
const menu = document.getElementById("menu");
const wrap = burger.closest(".burger-wrapper");
let isOpen = false;

burger.addEventListener("click", () => {
  isOpen = !isOpen;

  // Debugging log
  console.log('Burger clicked, isOpen:', isOpen);

  // Reset animations
  wrap.classList.remove("animate-cw", "animate-ccw");
  void wrap.offsetWidth; // Force reflow

  // Apply animation direction
  wrap.classList.add(isOpen ? "animate-cw" : "animate-ccw");

  // Toggle burger ⇄ X and menu visibility
  burger.classList.toggle("open", isOpen);  // Make burger become X
  menu.classList.toggle("open", isOpen);    // Show/Hide the menu

  // Debugging log
  console.log('Menu visibility:', menu.classList.contains('open'));

  burger.setAttribute("aria-expanded", isOpen);
  menu.setAttribute("aria-hidden", !isOpen);

  // Ensure the burger wrapper remains round while animating
  wrap.style.borderRadius = "50%";

  // Allow retrigger after 4 seconds to reset animation states
  setTimeout(() => {
    wrap.classList.remove("animate-cw", "animate-ccw");
  }, 4000);
});
