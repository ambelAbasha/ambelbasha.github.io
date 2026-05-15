document.addEventListener("DOMContentLoaded", function () {
    const footer = document.querySelector(".footer");

    // Ensure the footer element exists to avoid errors
    if (!footer) {
        console.warn("Footer element not found.");
        return;
    }

    // Set initial footer opacity (hidden by default)
    footer.style.opacity = 0;

    // Throttle function for optimized scroll handling
    function throttle(func, limit) {
        let lastFunc;
        let lastRan;
        return function () {
            const context = this;
            const args = arguments;
            if (!lastRan) {
                func.apply(context, args);
                lastRan = Date.now();
            } else {
                clearTimeout(lastFunc);
                lastFunc = setTimeout(function () {
                    if (Date.now() - lastRan >= limit) {
                        func.apply(context, args);
                        lastRan = Date.now();
                    }
                }, limit - (Date.now() - lastRan));
            }
        };
    }

    // Scroll event to detect user activity and show/hide footer
    const handleScroll = throttle(() => {
        // Show footer only when scrolled to the bottom
        if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight) {
            footer.style.opacity = 1; // Make footer visible
        } else {
            footer.style.opacity = 0; // Hide footer
        }
    }, 100); // Throttle scroll events to fire every 100ms

    window.addEventListener("scroll", handleScroll);
});
