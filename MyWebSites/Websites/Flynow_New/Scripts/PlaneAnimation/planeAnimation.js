// Select the image element
const plane = document.getElementById("animated-plane");

// Function to move the image from bottom-right to center smoothly
function moveImage() {
  const rect = document.getElementById("rect1");
  const rectWidth = rect.offsetWidth;
  const rectHeight = rect.offsetHeight;

  // Starting position (bottom-right corner)
  const startX = rectWidth - plane.offsetWidth;
  const startY = rectHeight - plane.offsetHeight;

  // Final position (center of the rectangle)
  const targetX = (rectWidth - plane.offsetWidth) / 2;
  const targetY = (rectHeight - plane.offsetHeight) / 2;

  let currentX = startX;
  let currentY = startY;
  const speed = 0.05; // Adjust this value for smoother/faster movement

  // Animation function using requestAnimationFrame
  function animate() {
    currentX += (targetX - currentX) * speed;
    currentY += (targetY - currentY) * speed;

    // Apply new position
    plane.style.transform = `translate(${currentX}px, ${currentY}px)`;

    // Check if the animation is near complete
    if (Math.abs(currentX - targetX) > 1 || Math.abs(currentY - targetY) > 1) {
      requestAnimationFrame(animate); // Continue animation
    }
  }

  animate(); // Start the animation
}

// Call the moveImage function after the page loads
window.onload = function () {
  moveImage();
};
