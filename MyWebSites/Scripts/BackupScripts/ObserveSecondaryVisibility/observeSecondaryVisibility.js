// Define the function to observe the element
function observeSecondaryCentralArea() {
    const secondaryCentralArea = document.querySelector('.secondary-central-area');

    // Create the IntersectionObserver to observe when the element comes into view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add the 'visible' class when the element is in view
                secondaryCentralArea.classList.add('visible');
            }
        });
    }, {
        root: null,   // Use the viewport as the root
        threshold: 0.1 // Trigger when 10% of the element is visible
    });

    // Start observing the secondary central area
    observer.observe(secondaryCentralArea);
}

// Call the function after the DOM is loaded
document.addEventListener("DOMContentLoaded", function() {
    observeSecondaryCentralArea();
});
