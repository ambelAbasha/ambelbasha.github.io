document.addEventListener("DOMContentLoaded", function () {
  const footer = document.querySelector("footer");
  const footerContainer = document.querySelector(".footer-container");
  const threshold = 40; // px from bottom

  window.addEventListener("mousemove", (e) => {
    const distanceFromBottom = window.innerHeight - e.clientY;

    if (distanceFromBottom <= threshold) {
      footer.classList.add("visible");
      footerContainer.classList.add("visible");
    } else {
      footer.classList.remove("visible");
      footerContainer.classList.remove("visible");
    }
  });
});
