// JavaScript for footer visibility on reaching the end of the page

window.addEventListener("scroll", function() {
    console.log("Scroll event triggered"); // Log to verify if the event is firing
    var footer = document.querySelector(".footer");
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
        footer.style.opacity = 1; // Make footer visible
    } else {
        footer.style.opacity = 0; // Hide footer
    }
});
