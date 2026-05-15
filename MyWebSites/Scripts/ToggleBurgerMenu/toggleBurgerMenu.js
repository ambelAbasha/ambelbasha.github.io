// toggleBurgerMenu.js

// match the HTML IDs
const burger = document.getElementById("webpages-burger-menu");
const menu   = document.getElementById("webpages-menu");
const wrap   = burger.closest(".burger-wrapper");
let   isOpen = false;

burger.addEventListener("click", () => {
  isOpen = !isOpen;

  // reset animations
  wrap.classList.remove("animate-cw", "animate-ccw");
  void wrap.offsetWidth; // force reflow

  // apply animation direction
  wrap.classList.add(isOpen ? "animate-cw" : "animate-ccw");

  // Toggle burger ⇄ X and menu visibility
  burger.classList.toggle("open", isOpen);
  menu.classList.toggle("show", isOpen);
  burger.setAttribute("aria-expanded", isOpen);
  menu.setAttribute("aria-hidden", !isOpen);

  // Keep the wrapper round
  wrap.style.borderRadius = "50%";

  // Reset after 4s
  setTimeout(() => {
    wrap.classList.remove("animate-cw", "animate-ccw");
  }, 4000);
});
