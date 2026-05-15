document.addEventListener('DOMContentLoaded', function () {
    // Target the grid items (triggers)
    const gridItems = {
        1: document.querySelector('.grid-item-1'),
        5: document.querySelector('.grid-item-5'),
        11: document.querySelector('.grid-item-11'),
        15: document.querySelector('.grid-item-15')
    };

    // Target the hover text container and its content
    const hoverTextContainer = document.getElementById('hoverTextContainer');
    const hoverTextContent = document.getElementById('hoverTextContent');

    let animationCompleted = true; // Track if animation is completed (start with true to allow the first hover)

    // Elements to fade out when hovering over 1, 5, 11, or 15
    const fadeElements = {
        1: [2, 3, 4],
        5: [7, 8, 9],
        11: [12, 13, 14],
        15: [2, 3, 4, 7, 8, 9, 12, 13, 14]
    };

    /**
     * Fetch external content and inject it into the hover text container.
     * @param {number} index - The grid item index for which content is needed.
     */
    function loadHoverTextContent(index) {
        fetch('Scripts/HoverText/hover-text-content.html')
            .then(response => response.text())
            .then(html => {
                // Parse the fetched HTML content
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const content = doc.querySelector(`#content-${index}`);

                if (content) {
                    hoverTextContent.innerHTML = content.innerHTML;
                } else {
                    hoverTextContent.innerHTML = '<p>Content not found!</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching hover text content:', error);
            });
    }

    /**
     * Display hover text content with smooth transitions.
     * @param {HTMLElement} triggerElement - The element to trigger hover.
     * @param {number} index - The index to determine which content to load.
     */
    function showHoverTextContent(triggerElement, index) {
        triggerElement.addEventListener('mouseenter', function () {
            if (!animationCompleted) return; // Prevent multiple triggers before animation ends

            // Show the hover text container immediately (no fade out on switch)
            hoverTextContainer.style.display = 'flex';
            hoverTextContainer.style.opacity = '1'; // Fade-in effect

            // Load content from external HTML file
            loadHoverTextContent(index);

            // Fade out related elements when hover starts
            fadeOutGridElements(triggerElement);

            // Set animationCompleted to false while animation is ongoing
            animationCompleted = false;

            // Smooth transition for fading out after content is loaded
            setTimeout(function () {
                fadeInGridElements(triggerElement); // Fade in the grid elements when hover text is updated
                animationCompleted = true; // Reset the flag after the animation is complete
            }, 1000); // Adjust time for smoother transition
        });
    }

    /**
     * Fade out the grid elements related to the hovered item.
     * @param {HTMLElement} triggerElement - The hovered element.
     */
    function fadeOutGridElements(triggerElement) {
        const index = triggerElement.dataset.index;
        const relatedElements = fadeElements[index];
        if (relatedElements) {
            relatedElements.forEach(id => {
                const element = document.querySelector(`.grid-item-${id}`);
                if (element) {
                    element.classList.add('fade-out'); // Add fade-out class for smooth fade
                }
            });
        }
    }

    /**
     * Fade in the grid elements related to the hovered item.
     * @param {HTMLElement} triggerElement - The hovered element.
     */
    function fadeInGridElements(triggerElement) {
        const index = triggerElement.dataset.index;
        const relatedElements = fadeElements[index];
        if (relatedElements) {
            relatedElements.forEach(id => {
                const element = document.querySelector(`.grid-item-${id}`);
                if (element) {
                    element.classList.remove('fade-out'); // Remove fade-out class for smooth fade-in
                }
            });
        }
    }

    // Set up hover interactions for the trigger grid items with detailed text
    showHoverTextContent(gridItems[1], 1);
    showHoverTextContent(gridItems[5], 5);
    showHoverTextContent(gridItems[11], 11);
    showHoverTextContent(gridItems[15], 15);
});
