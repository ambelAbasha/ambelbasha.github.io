const burger = document.getElementById("landing-burger-menu");
const menu   = document.getElementById("menu");
const wrap   = burger.closest(".burger-wrapper");
let isOpen   = false;

burger.addEventListener("click", () => {
  isOpen = !isOpen;

  wrap.classList.remove("animate-cw", "animate-ccw");
  void wrap.offsetWidth;
  wrap.classList.add(isOpen ? "animate-cw" : "animate-ccw");

  burger.classList.toggle("open", isOpen);
  menu.classList.toggle("open", isOpen);

  burger.setAttribute("aria-expanded", isOpen);
  menu.setAttribute("aria-hidden", !isOpen);
  wrap.style.borderRadius = "50%";

  setTimeout(() => { wrap.classList.remove("animate-cw", "animate-ccw"); }, 4000);
});

/* Mouse-follow glow */
menu.addEventListener("mousemove", e => {
  const r = menu.getBoundingClientRect();
  menu.style.setProperty("--mx", `${(e.clientX - r.left).toFixed(1)}px`);
  menu.style.setProperty("--my", `${(e.clientY - r.top).toFixed(1)}px`);
});

menu.addEventListener("mouseleave", () => {
  menu.style.setProperty("--mx", "-120px");
  menu.style.setProperty("--my", "-120px");
});

/* Close on outside click */
document.addEventListener("click", e => {
  if (isOpen && !menu.contains(e.target) && !burger.contains(e.target)) {
    isOpen = false;
    burger.classList.remove("open");
    menu.classList.remove("open");
    burger.setAttribute("aria-expanded", false);
    menu.setAttribute("aria-hidden", true);
  }
});

/* ── Active section tracking ──────────────────────────────────── */
const menuItems = document.querySelectorAll(".menu-item[data-section]");

function setActive(sectionId) {
  menuItems.forEach(el => {
    el.classList.toggle("active", el.dataset.section === sectionId);
  });
}

/* Start with home active */
setActive("home");

/* Map each section element to its data-section key */
const sectionMap = [
  { el: document.getElementById("footer-container"), key: "contact"  },
  { el: document.getElementById("skills"),           key: "skills"   },
  { el: document.getElementById("about"),            key: "about"    },
];

const sectionIO = new IntersectionObserver(entries => {
  /* Find the topmost visible section */
  const visible = entries
    .filter(e => e.isIntersecting)
    .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top);

  if (visible.length) {
    const el = visible[0].target;
    const match = sectionMap.find(s => s.el === el);
    if (match) setActive(match.key);
  }
}, { threshold: 0.25 });

sectionMap.forEach(({ el }) => { if (el) sectionIO.observe(el); });

/* Fall back to home when above all tracked sections */
const homeIO = new IntersectionObserver(entries => {
  if (entries[0].isIntersecting) setActive("home");
}, { threshold: 0.1 });

const homeEl = document.getElementById("home");
if (homeEl) homeIO.observe(homeEl);
