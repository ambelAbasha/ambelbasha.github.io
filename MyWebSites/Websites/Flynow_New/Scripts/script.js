// JavaScript for footer visibility on reaching the end of the page

window.addEventListener("mousemove", function (event) {
    var footer = document.querySelector(".footer");
    var threshold = 50; // Define how close to the bottom the mouse should be
    var viewportHeight = window.innerHeight; // Height of the viewport

    // If the mouse is within the threshold of the bottom, show the footer
    if (viewportHeight - event.clientY <= threshold) {
        footer.classList.add("visible"); // Make the footer visible
    } else {
        footer.classList.remove("visible"); // Hide the footer
    }
});
