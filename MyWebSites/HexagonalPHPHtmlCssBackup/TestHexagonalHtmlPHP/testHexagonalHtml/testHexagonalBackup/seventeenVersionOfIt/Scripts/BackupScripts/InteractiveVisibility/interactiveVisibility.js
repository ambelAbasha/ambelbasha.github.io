document.addEventListener("DOMContentLoaded", function () {
    // Select elements from the DOM
    const primaryCentralArea = document.querySelector('.primary-central-area');
    const secondaryCentralArea = document.querySelector('.secondary-central-area');
    const thirdCentralArea = document.querySelector('.third-central-area');
    const cubeImageContainer = document.querySelector('.cube-container');
    const leftContainer = document.querySelector('.left-container');
    const rightContainer = document.querySelector('.right-container');
    const centralContainer = document.querySelector('.central-container');
    const scrollButton = document.querySelector('.scroll-button');

    // Ensure necessary elements exist
    if (!primaryCentralArea || !secondaryCentralArea || !thirdCentralArea || !cubeImageContainer) {
        console.warn("One or more central areas are missing in the DOM.");
        return;
    }

    let isScrolling = false;
    let hasTriggered = false;

    // Smooth scrolling to a target element
    function scrollToElement(element, offset = 0, duration = 1000) {
        if (isScrolling) return; // Prevent re-entrance
        isScrolling = true;

        const startPosition = window.scrollY;
        const targetPosition = element.getBoundingClientRect().top + window.pageYOffset + offset;
        const distance = targetPosition - startPosition;
        const startTime = performance.now();

        function animateScroll(currentTime) {
            const elapsedTime = currentTime - startTime;
            const progress = Math.min(elapsedTime / duration, 1);

            const easeInOutQuad = progress < 0.5
                ? 2 * progress * progress
                : 1 - Math.pow(-2 * progress + 2, 2) / 2;

            const scrollTo = startPosition + distance * easeInOutQuad;
            window.scrollTo(0, scrollTo);

            if (elapsedTime < duration) {
                requestAnimationFrame(animateScroll);
            } else {
                isScrolling = false; // Reset once scrolling is complete
            }
        }

        requestAnimationFrame(animateScroll);
    }

    // Trigger gentle appearance on hover
    function triggerAppearance() {
        if (hasTriggered) return; // Prevent multiple triggers
        hasTriggered = true;

        // Add gentle fade-out transition for original content
        primaryCentralArea.style.transition = "opacity 0.5s ease-out";
        primaryCentralArea.style.opacity = 0;

        setTimeout(() => {
            // Swap content in primary area with cube image after fade-out
            primaryCentralArea.innerHTML = cubeImageContainer.innerHTML;
            primaryCentralArea.classList.add('new-layout');

            // Add fade-in effect for new content
            primaryCentralArea.style.transition = "opacity 0.5s ease-in";
            primaryCentralArea.style.opacity = 1;

            // Animate additional elements
            cubeImageContainer?.classList.add('fade-in', 'visible');
            leftContainer?.classList.add('fade-in', 'visible');
            rightContainer?.classList.add('fade-in', 'visible');
        }, 500); // Wait for fade-out to complete
    }

    // Add hover event listener to central container
    if (centralContainer) {
        centralContainer.addEventListener('mouseover', triggerAppearance);
    }

    // Scroll button functionality
    if (scrollButton) {
        scrollButton.addEventListener('click', () => {
            scrollToElement(secondaryCentralArea, -window.innerHeight / 2, 1500);
        });
    }
});
