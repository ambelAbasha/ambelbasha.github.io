document.addEventListener("DOMContentLoaded", function () {
    // Query necessary DOM elements
    const lotusContainer = document.querySelector('.lotus-container'); // Lotus container to trigger click
    const cubeContainer = document.querySelector('.cube-container'); // Cube container to hide along with side containers
    const leftContainer = document.querySelector('.left-container'); // Left side container
    const rightContainer = document.querySelector('.right-container'); // Right side container

    // Check for required elements
    if (!lotusContainer || !cubeContainer || !leftContainer || !rightContainer) {
        console.error("Lotus, Cube, or side containers not found!");
        return;
    }

    // Lotus click handling: Expand and hide other elements
    lotusContainer.addEventListener('click', function () {
        const isActive = lotusContainer.classList.contains('expanded');

        if (isActive) {
            // Revert to initial state when lotus is clicked again
            lotusContainer.classList.remove('expanded');
            lotusContainer.classList.remove('hidden');
            cubeContainer.classList.remove('visible');
            leftContainer.classList.remove('visible');
            rightContainer.classList.remove('visible');
        } else {
            // Expand lotus, hide other elements and show cube and side containers
            lotusContainer.classList.add('expanded');
            lotusContainer.classList.add('hidden');
            cubeContainer.classList.add('visible');
            leftContainer.classList.add('visible');
            rightContainer.classList.add('visible');
        }
    });

    // Cube click handling: Reset lotus and hide cube and side containers
    cubeContainer.addEventListener('click', function () {
        lotusContainer.classList.remove('expanded');
        lotusContainer.classList.remove('hidden');
        cubeContainer.classList.remove('visible');
        leftContainer.classList.remove('visible');
        rightContainer.classList.remove('visible');
    });

    // Optionally, you can also toggle the left and right containers visibility based on hovering over the central area if that's part of the desired effect.
});
