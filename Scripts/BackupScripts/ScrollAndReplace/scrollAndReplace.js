document.addEventListener("DOMContentLoaded", function() {
    const secondaryCentralArea = document.querySelector('.secondary-central-area');
    const primaryCentralArea = document.querySelector('.primary-central-area');
    const cubeImageContainer = document.querySelector('.cube-container');
    const leftContainer = document.querySelector('.left-container');
    const rightContainer = document.querySelector('.right-container');
    const cubeImageHTML = cubeImageContainer ? cubeImageContainer.innerHTML : '';

    let hasScrolledToBottom = false; // Flag to prevent repeated appends

    // IntersectionObserver to add 'visible' class to secondaryCentralArea when in view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                secondaryCentralArea?.classList.add('visible');
                observer.unobserve(entry.target); // Stop observing after making it visible
            }
        });
    }, {
        root: null,
        threshold: 0.1
    });

    if (secondaryCentralArea) observer.observe(secondaryCentralArea);

    // Debounce function to limit scroll event firing frequency
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Scroll event listener to check if the user has scrolled to the bottom
    const handleScroll = debounce(function() {
        const scrolledToBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight;

        if (scrolledToBottom && !hasScrolledToBottom) {
            hasScrolledToBottom = true; // Set flag to prevent repeated updates

            // Ensure primaryCentralArea displays the cube image only once
            primaryCentralArea.innerHTML = cubeImageHTML;
            primaryCentralArea.classList.add('new-layout');

            // Add 'visible' class to make the elements visible
            cubeImageContainer?.classList.add('visible');
            leftContainer?.classList.add('visible');
            rightContainer?.classList.add('visible');
        }
    }, 200); // Delay in milliseconds

    window.addEventListener('scroll', handleScroll);
});