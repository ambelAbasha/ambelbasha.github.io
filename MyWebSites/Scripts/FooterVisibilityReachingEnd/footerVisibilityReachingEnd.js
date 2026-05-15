document.addEventListener("DOMContentLoaded", () => {
  const footer = document.querySelector(".footer-container");

  if (!footer) {
    console.warn("Footer container not found.");
    return;
  }

  // Apply smooth transition via inline style (or CSS)
  footer.style.transition = "transform 0.3s ease, opacity 0.3s ease";
  footer.style.position = "fixed";
  footer.style.bottom = "0";
  footer.style.left = "0";
  footer.style.width = "100%";
  footer.style.transform = "translateY(100%)"; // initially hidden
  footer.style.opacity = "0";

  const threshold = 30; // 30px from the bottom to show the footer

  window.addEventListener("mousemove", (e) => {
    const distanceFromBottom = window.innerHeight - e.clientY;
    const nearBottom = distanceFromBottom <= threshold;

    if (nearBottom) {
      footer.style.transform = "translateY(0)";
      footer.style.opacity = "1";
    } else {
      footer.style.transform = "translateY(100%)";
      footer.style.opacity = "0";
    }
  });
});
