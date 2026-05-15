document.addEventListener("DOMContentLoaded", function () {
    // Select DOM elements
    const primaryCentralArea = document.querySelector('.primary-central-area');
    const headerContainer = document.querySelector('.header-container');

    // Ensure necessary elements exist
    if (!primaryCentralArea || !headerContainer) {
        console.warn("Required elements are missing in the DOM.");
        return;
    }

    // Header scrolling and fading logic
    function handleHeaderScroll() {
        const scrollPosition = window.scrollY;
        const primaryHeight = primaryCentralArea.offsetHeight;
        const headerHeight = headerContainer.offsetHeight;
        const centralAreaBottom = primaryCentralArea.offsetTop + primaryHeight;

        // Adjust header position based on scroll
        let newTopPosition = Math.min(scrollPosition * 0.5, centralAreaBottom - headerHeight - 1);
        headerContainer.style.top = `${newTopPosition}px`;

        // Fade header out when it passes the bottom of the primary container
        if (scrollPosition + headerHeight < centralAreaBottom) {
            headerContainer.style.opacity = "1";
            headerContainer.style.display = "block";
        } else {
            fadeOut(headerContainer);
        }
    }

    // Fade-out animation for the header
    function fadeOut(element) {
        let opacity = 1;
        const fadeDuration = 1000; // 1 second fade-out
        const fadeStep = 16 / fadeDuration;

        function fade() {
            opacity -= fadeStep;
            if (opacity <= 0) {
                opacity = 0;
                element.style.opacity = opacity.toString();
                element.style.display = "none";
            } else {
                element.style.opacity = opacity.toString();
                requestAnimationFrame(fade);
            }
        }

        requestAnimationFrame(fade);
    }

    // Attach scroll listener for the header logic
    window.addEventListener('scroll', handleHeaderScroll);
});
