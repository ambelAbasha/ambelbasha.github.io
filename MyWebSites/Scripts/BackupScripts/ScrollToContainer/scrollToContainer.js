document.addEventListener("DOMContentLoaded", function () {
    // Select the link with href="#primary-container"
    const homeLink = document.querySelector('a[href="#primary-container"]');
    
    // Ensure the link exists before adding the event listener
    if (homeLink) {
        homeLink.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the default anchor click behavior
            
            // Select the target element
            const target = document.querySelector('#primary-container');
            
            // Ensure the target element exists
            if (target) {
                // Calculate the target scroll position
                const targetPosition = target.getBoundingClientRect().top + window.scrollY - 10;
                
                // Scroll smoothly to the target position
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth' // Enable smooth scrolling
                });
            } else {
                console.warn('Target element #primary-container not found.');
            }
        });
    } else {
        console.warn('Link with href="#primary-container" not found.');
    }
});
