// Select the hexContainer element using the correct class
const hexContainer = document.querySelector('.hex-container'); // Updated to use the correct class

// Configuration variables
const hexagonSize = 154; // Width of the hexagon as defined in your CSS
const hexagonHeight = 170.5; // Height of the hexagon as defined in your CSS
const verticalSpacing = -38.1; // Set vertical spacing to -38.1 pixels directly
let currentPage = 1;
const hexagonsPerPage = 300; // Number of hexagons per page

// Calculate grid dimensions based on window size
function calculateGridDimensions() {
    const numRowsInView = Math.ceil(window.innerHeight / (hexagonHeight + verticalSpacing)); // Use hexagonHeight + verticalSpacing
    const numCols = Math.floor(window.innerWidth / (hexagonSize * 0.75)); // Adjust to cover width effectively
    return { numRowsInView, numCols };
}

function createHexagons(page = 1) {
    hexContainer.innerHTML = ''; // Clear container for new hexagons

    const { numRowsInView, numCols } = calculateGridDimensions();
    const numRows = numRowsInView + 1; // Add one extra row for a full fill
    let hexagonsCreated = 0;

    // Determine starting hexagon index for the current page
    const startHexagon = (page - 1) * hexagonsPerPage;
    const endHexagon = page * hexagonsPerPage;

    for (let row = 0; row < numRows; row++) {
        for (let col = -1; col < numCols; col++) { // Start from -1 to add the extra hexagon on the left
            if (hexagonsCreated >= endHexagon) {
                return; // Stop if we've reached the limit of hexagons for this page
            }
            if (hexagonsCreated >= startHexagon) {
                const hexagon = document.createElement('div');
                hexagon.classList.add('hexagon');

                // Calculate x and y position for hexagons
                let x = col * hexagonSize + 40; // Base x position + 40px shift
                let y = row * (hexagonHeight + verticalSpacing); // Base y position with added vertical spacing

                // Stagger odd rows
                if (row % 2 === 1) {
                    x += (hexagonSize / 2); // Offset odd rows to the right
                }

                // Apply the translate transformation without rotation
                hexagon.style.transform = `translate(${x}px, ${y}px)`; // Apply the translation
                hexContainer.appendChild(hexagon);
            }
            hexagonsCreated++;
        }
    }

    // Append additional bottom rows of hexagons
    appendBottomRows(numCols, numRowsInView);
}

// Function to append rows of hexagons at the bottom
function appendBottomRows(numCols, numRowsInView) {
    const evenRowShift = 1; // Horizontal shift for even rows
    const oddRowShift = -92.6; // Horizontal shift for odd rows
    const oddRowVerticalAdjustment = -6; // Vertical adjustment for odd rows

    for (let rowOffset = 1; rowOffset <= 2; rowOffset++) {
        const row = numRowsInView + rowOffset; // Calculate the current row based on the offset

        for (let col = -1; col < numCols; col++) { // Start from -1 to add the extra hexagon on the left
            const hexagon = document.createElement('div');
            hexagon.classList.add('hexagon');

            // Adjust position based on row type
            let x = col * hexagonSize + 40; // Base x position + 40px shift (20px original + 20px additional)
            let y = row * (hexagonHeight + verticalSpacing); // Base y position

            // Apply horizontal shift based on row type
            if (row % 2 === 0) {
                x += evenRowShift; // Shift even rows
            } else {
                x += oddRowShift; // Shift odd rows
                y += oddRowVerticalAdjustment; // Adjust vertical position for odd rows
            }

            // Rotate the hexagon by 45 degrees if needed
            hexagon.style.transform = `translate(${x}px, ${y}px) rotate(0deg)`; // Apply the translation without rotation
            hexContainer.appendChild(hexagon);
        }
    }
}

// Event listener for window resize to adjust grid dimensions
window.addEventListener('resize', () => {
    hexContainer.innerHTML = ''; // Clear container to avoid re-creating elements
    createHexagons(currentPage); // Recreate hexagons on window resize
});

// Event listener for scroll to handle pagination
window.addEventListener('scroll', () => {
    const { scrollTop, clientHeight, scrollHeight } = document.documentElement;
    if (scrollTop + clientHeight >= scrollHeight - 100) { // Near bottom of page
        currentPage++;
        createHexagons(currentPage);
    }
});

// Initial creation of hexagons
createHexagons(currentPage);
