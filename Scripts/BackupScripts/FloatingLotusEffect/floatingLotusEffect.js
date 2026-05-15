(function() {
    const waterSurface = document.querySelector('.water-surface');
    const lotusImageContainer = document.querySelector('.lotus-image-container');

    let lastScrollY = 0;
    let easeFactor = 0.08;  // Controls smoothness, lower = more smooth
    let waveAmplitudeX = 100;  // Increased horizontal wave amplitude
    let waveAmplitudeY = 25;   // Increased vertical wave amplitude
    let waveSpeedX = 0.01;  // Horizontal wave speed
    let waveSpeedY = 0.008; // Vertical wave speed
    let isAnimating = false; // Flag to control when to animate
    const movementThreshold = 1e-1; // Threshold for minimal movement (further increased)
    const scrollThreshold = 2;  // Minimum scroll change to trigger updates

    function lerp(start, end, t) {
        return start + (end - start) * t;
    }

    function updateMovement() {
        const scrollY = window.scrollY;

        // If the scrollY hasn't changed significantly, skip updating
        if (Math.abs(scrollY - lastScrollY) < scrollThreshold) {
            return; // Prevent unnecessary updates and stop infinite loop
        }

        // Smooth transition for scroll position
        lastScrollY = lerp(lastScrollY, scrollY, easeFactor);

        // Simulate water movement with sine and cosine waves
        let horizontalMovement = Math.sin(lastScrollY * waveSpeedX) * waveAmplitudeX;
        let verticalMovement = Math.cos(lastScrollY * waveSpeedY) * waveAmplitudeY;

        // Apply threshold to avoid extreme small values
        if (Math.abs(horizontalMovement) < movementThreshold) {
            horizontalMovement = 0; // Stop movement if it's too small
        }

        if (Math.abs(verticalMovement) < movementThreshold) {
            verticalMovement = 0; // Stop movement if it's too small
        }

        // Debug logging
        console.log('scrollY:', scrollY, 'horizontalMovement:', horizontalMovement, 'verticalMovement:', verticalMovement);

        // Apply movement to water surface (smooth wave-like motion)
        if (waterSurface) {
            waterSurface.style.transform = `translate(${horizontalMovement}px, ${verticalMovement}px)`;
        }

        // Lotus follows the water movement with less intensity (floating effect)
        if (lotusImageContainer) {
            const lotusHorizontalMovement = horizontalMovement * 0.7; // Slightly slower horizontal movement
            const lotusVerticalMovement = verticalMovement * 0.7;     // Slightly slower vertical movement

            lotusImageContainer.style.transform = `translate(${lotusHorizontalMovement}px, ${lotusVerticalMovement}px)`;
        }

        // Call the next frame if there's any significant scroll movement
        isAnimating = true;
    }

    // Bind the updateMovement function to scroll events
    window.addEventListener('scroll', function() {
        if (!isAnimating) {
            requestAnimationFrame(updateMovement);
            isAnimating = true;
        }
    });

    // Control when to stop the animation
    function stopAnimation() {
        isAnimating = false;
    }

    // If the user stops scrolling, you could disable animations after some time (optional)
    window.addEventListener('scroll', stopAnimation);
})();
