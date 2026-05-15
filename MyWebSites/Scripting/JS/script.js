// script.js
const elements = document.querySelectorAll('.element');

elements.forEach((element, index) => {
    element.addEventListener('click', (event) => {
        if (index === 0) { // If the first element is clicked
            event.preventDefault(); // Prevent default navigation
            alert(`You clicked element ${element.textContent}`);
            // Navigate to the link after the alert
            window.location.href = element.querySelector('a').href;
        } else {
            alert(`You clicked element ${element.textContent}`);
        }
    });
});
