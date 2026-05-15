document.addEventListener("DOMContentLoaded", function() {
    // Function to trigger animations for all necessary elements
    const elementsToAnimate = [
        document.querySelector('.primary-container'),
        document.querySelector('.header'),
        document.querySelector('.navbar-container'),
        document.querySelector('.portfolio-burger-menu'),
        document.querySelector('.portfolio-menu'),
        document.querySelector('.header-container'),
        document.querySelector('.picture-container'),
        document.querySelector('.presentation-content'),
        document.querySelector('.header-container-presentation'),
        document.querySelector('.central-container'),
        document.querySelector('.cube-container'),
        document.querySelector('.side-container'),
        document.querySelector('.lotus-container'),
        document.querySelector('.water-surface')
    ];

    // Add animations by applying the opacity and class dynamically
    elementsToAnimate.forEach((element, index) => {
        setTimeout(() => {
            if (element) {
                element.classList.add('animate-in');  // Add the animation class
                element.style.opacity = 1;  // Ensure opacity is set for the element
            }
        }, index * 500);  // Stagger each element's animation
    });
});
