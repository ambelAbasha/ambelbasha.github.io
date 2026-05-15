document.addEventListener("DOMContentLoaded", function () {
    const lotusImage = document.querySelector('.floating-lotus'); // Lotus image element
    
    // Ensure the lotus image exists
    if (!lotusImage) {
        console.error("Lotus image not found!");
        return;
    }
    
    // Add hover event to toggle expanded class
    lotusImage.addEventListener('mouseenter', function () {
        lotusImage.classList.add('expanded'); // Expand the image on hover
    });
    
    lotusImage.addEventListener('mouseleave', function () {
        // Optionally, remove the expanded class if you want to reset on mouseout
        // lotusImage.classList.remove('expanded');
    });
});
