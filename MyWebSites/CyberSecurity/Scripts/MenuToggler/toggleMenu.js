// ================================
//  Main Nav & Submenu Toggles
// ================================

// Toggle main burger menu (and auto-close any open submenu)
function toggleMenu() {
  const menu = document.getElementById('menu');
  const burger = document.getElementById('cyber-burger-menu');

  // Toggle main navigation panel
  const isOpen = menu.classList.toggle('show');
  burger.classList.toggle('open', isOpen);

  // If closing main nav, also close all submenus
  if (!isOpen) {
    closeSubmenus();
  }
}

// Helper: Close all submenus and remove their arrow indicators
function closeSubmenus() {
  const ids = ['home-menu', 'cyberSecurity-menu1', 'about-menu'];
  const btns = ['.home-link', '.portfolio-link', '.about-link'];

  // Hide each submenu by removing the .show class
  ids.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.classList.remove('show');  // Remove .show to hide submenu
  });

  // Remove .open arrow class from each trigger button
  btns.forEach(sel => {
    const btn = document.querySelector(sel);
    if (btn) btn.classList.remove('open');
  });
}

// Generic toggler for submenus
function toggleSubmenu(menuId, btnSelector) {
  // First close all others
  closeSubmenus();

  const menu = document.getElementById(menuId);
  const btn  = document.querySelector(btnSelector);

  // Determine new open/closed state
  const shouldOpen = !menu.classList.contains('show');
  if (shouldOpen) {
    menu.classList.add('show');  // Show submenu
  } else {
    menu.classList.remove('show');  // Hide submenu
  }

  btn.classList.toggle('open', shouldOpen);
}

// Toggle Home submenu
function toggleHomeMenu() {
  toggleSubmenu('home-menu', '.home-link');
}

// Toggle Portfolio submenu
function togglePortfolioMenu() {
  toggleSubmenu('cyberSecurity-menu1', '.portfolio-link');
}

// Toggle About submenu
function toggleAboutMenu() {
  toggleSubmenu('about-menu', '.about-link');
}

// Close everything: main nav + submenus + arrows
function closeAllMenus() {
  // Close main nav
  document.getElementById('menu').classList.remove('show');
  document.getElementById('cyber-burger-menu').classList.remove('open');
  // Close submenus
  closeSubmenus();
}

// Ensure submenus are closed on initial load
closeSubmenus();

// Close all on Escape key
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    closeAllMenus();
  }
});

// Allow links to work and prevent closing submenu on link click
const submenuLinks = document.querySelectorAll('.cyber-menu a, #cyberSecurity-menu1 a, #about-menu a, #home-menu a');
submenuLinks.forEach(link => {
  link.addEventListener('click', function(e) {
    // This stops the click event from closing the submenu
    e.stopPropagation();  // Prevent event bubbling, but let the link navigate
    
    // Optionally close submenus here, if desired, after the link is clicked
    // If you want submenus to stay open, leave this empty or comment it out:
    // closeSubmenus();
  });
});
