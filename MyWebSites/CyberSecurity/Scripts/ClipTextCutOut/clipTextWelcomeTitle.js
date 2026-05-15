// Ensure the window is fully loaded before executing the code
window.addEventListener('load', function () {
    var titleElement = document.querySelector('.welcome-title'); // Get the .welcome-title element
    
    // Apply a linear gradient background to the text (this will be clipped to the text)
    titleElement.style.background = 'linear-gradient(' +
        'to bottom, ' +
        '#00f8ec, ' +        // Solid color at the top
        'rgba(0, 248, 236, 0.726), ' +   // Semi-transparent
        'rgba(0, 248, 236, 0.603), ' +    // Semi-transparent
        'rgba(0, 248, 236, 0.473), ' +    // Semi-transparent
        'rgba(0, 248, 236, 0.205), ' +    // Semi-transparent
        'rgba(0, 248, 236, 0)' +          // Fully transparent at the bottom
    ')'; 

    titleElement.style.webkitBackgroundClip = 'text';  // For Safari
    titleElement.style.backgroundClip = 'text';        // Fallback for other browsers
    titleElement.style.webkitTextFillColor = 'transparent';  // Ensure the text is transparent so the background is visible

    // Add the "show" class after a short delay to trigger fade-in
    setTimeout(function () {
        titleElement.classList.add('show');
    }, 100); // Delay for fade-in effect (optional)
});
