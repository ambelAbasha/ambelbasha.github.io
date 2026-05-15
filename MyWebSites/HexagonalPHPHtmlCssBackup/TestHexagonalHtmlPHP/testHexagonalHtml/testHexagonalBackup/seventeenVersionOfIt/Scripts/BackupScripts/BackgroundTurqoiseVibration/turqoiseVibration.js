window.onload = function () {
    // Create the background animation element
    const backgroundElement = document.createElement('div');
    backgroundElement.classList.add('background-animation');
    document.body.appendChild(backgroundElement);

    // Start the vibrant background animation
    startVibrantBackgroundAnimation(backgroundElement);
}

function startVibrantBackgroundAnimation(element) {
    // Initial position of the background animation
    let startPosition = 180; // Start 180px from the left

    // Apply initial position
    element.style.left = `${startPosition}px`;

    // Trigger the animation by adding the class
    element.classList.add('background-animation');
    
    // The animation will run for 8 seconds, and we will reset it to initial after that
    setTimeout(function () {
        element.style.transition = 'background 0.5s ease';  // Smooth transition
        element.style.background = 'linear-gradient(to bottom right, #383737, #000, #9c9c9c)'; // Reset to the original gradient
    }, 8000); // 8 seconds duration
}
