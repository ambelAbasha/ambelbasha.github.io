document.addEventListener('DOMContentLoaded', function () {
    const cube = document.querySelector('.cube');
    const cubeContainer = document.querySelector('.cube-container-3d');
    if (!cube || !cubeContainer) {
        console.error("Cube element not found.");
        return;
    }

    // Initial rotation values
    let currentX = 0, currentY = 0;
    let lastX = 0, lastY = 0;
    const sensitivity = 0.4;  // Increased sensitivity for more immediate rotation

    // Get the dimensions of the cube container to track mouse position
    const cubeWidth = cubeContainer.offsetWidth;
    const cubeHeight = cubeContainer.offsetHeight;

    // Variables for handling the rotation state
    let isRotating = false;
    let mouseDown = false;  // Track if the mouse button is held down

    // Function to update cube rotation based on mouse position
    function rotateCube(event) {
        if (!mouseDown) return;  // Don't rotate unless mouse is pressed

        // Get mouse position relative to the container
        const mouseX = event.clientX - cubeContainer.offsetLeft;
        const mouseY = event.clientY - cubeContainer.offsetTop;

        // Normalize the mouse position to the center of the container
        const centerX = cubeWidth / 2;
        const centerY = cubeHeight / 2;

        // Calculate the rotation on both axes based on the cursor position
        const deltaX = mouseX - centerX;
        const deltaY = mouseY - centerY;

        // Map the delta values to rotation angles, adjust the range for rotation
        currentX = (deltaX / centerX) * 180 * sensitivity;  // Horizontal rotation with sensitivity
        currentY = -(deltaY / centerY) * 180 * sensitivity;  // Vertical rotation (flipped for natural movement)

        // Apply the rotation to the cube
        cube.style.transform = `rotateX(${currentY}deg) rotateY(${currentX}deg)`;
    }

    // Function to start rotating the cube when mouse is pressed
    function startRotation(event) {
        mouseDown = true;
        // Add the move event listener for real-time rotation
        cubeContainer.addEventListener('mousemove', rotateCube);
    }

    // Function to stop rotating the cube when mouse clicks
    function stopRotation() {
        if (!mouseDown) return;  // Don't stop if mouse isn't pressed
        mouseDown = false;

        // Smooth stop transition
        cube.style.transition = 'transform 0.5s ease-out';  // Smooth transition for stopping
        cube.style.transform = `rotateX(${currentY}deg) rotateY(${currentX}deg)`;  // Stop at the last position

        // Remove the move event listener
        cubeContainer.removeEventListener('mousemove', rotateCube);
    }

    // Mouse down: Start rotation
    cubeContainer.addEventListener('mousedown', startRotation);

    // Mouse up: Stop rotation
    cubeContainer.addEventListener('mouseup', stopRotation);
    cubeContainer.addEventListener('mouseleave', stopRotation);  // Stop rotation if mouse leaves the container

    // Reset the rotation of the cube to show the front face when clicked
    function resetToFrontFace() {
        // Remove any ongoing transition and set to a smooth transition
        cube.style.transition = 'transform 0.5s ease-out';
        // Rotate to the position where the front face is directly facing the user
        cube.style.transform = 'rotateX(0deg) rotateY(0deg)';  // Reset rotation to show the front face
    }

    // Add a click event listener to the cube container to reset rotation
    cubeContainer.addEventListener('click', resetToFrontFace);

});
