// Define constants for hexagon size and spacing
const hexagonSize = 154; // Width of hexagon
const hexagonHeight = 172.5; // Height of hexagon
const additionalSpacing = 40; // Spacing between hexagons

// Function to calculate grid dimensions based on window size
function calculateGridDimensions() {
    const numRowsInView = Math.ceil(window.innerHeight / (hexagonHeight * 0.75)); // Adjusted vertical spacing
    const numCols = Math.ceil(window.innerWidth / (hexagonSize * 1.51 + additionalSpacing));
    return { numRowsInView, numCols };
}

// Function to create hexagon elements and position them
function createHexagons() {
    const hexContainer = document.getElementById('hexContainer');
    hexContainer.innerHTML = ''; // Clear hexContainer first

    const { numRowsInView, numCols } = calculateGridDimensions();
    const numRows = numRowsInView + 2; // Extra rows for top and bottom

    const evenRowShift = -100; // Shift for even rows
    const oddRowShift = 36; // Shift for odd rows
    const verticalSpacing = -52; // Vertical spacing between hexagons

    let hexagonsCreated = 0;

    for (let row = -1; row < numRows; row++) {
        for (let col = 0; col < numCols; col++) {
            if (hexagonsCreated >= initialHexagonCount) {
                return; // Exit if enough hexagons are created
            }

            const hexagon = document.createElement('div');
            hexagon.classList.add('hexagon');

            // Position calculations
            let x = col * (hexagonSize * 1.51 + additionalSpacing);
            let y = row * (hexagonHeight * 0.75);

            if (row % 2 === 0) {
                x += evenRowShift;
            } else {
                x += oddRowShift;
            }

            y += verticalSpacing * row;

            hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;
            hexContainer.appendChild(hexagon);

            hexagonsCreated++;
        }
    }
}

// Function to add more elements when scrolling
function addMoreElements() {
    const hexContainer = document.getElementById('hexContainer');

    const { numRowsInView, numCols } = calculateGridDimensions();
    const additionalElementsCount = 100; // Number of elements to add

    const evenRowShift = -100;
    const oddRowShift = 36;
    const verticalSpacing = -26;

    const startRow = numRowsInView;

    let hexagonsCreated = initialHexagonCount;

    for (let row = startRow; row < startRow + Math.ceil(additionalElementsCount / numCols); row++) {
        for (let col = 0; col < numCols; col++) {
            if (hexagonsCreated >= initialHexagonCount + additionalElementsCount) {
                return; // Exit if enough elements are created
            }

            const hexagon = document.createElement('div');
            hexagon.classList.add('hexagon');

            let x = col * (hexagonSize * 1.51 + additionalSpacing);
            let y = row * (hexagonHeight * 0.75);

            if (row % 2 === 0) {
                x += evenRowShift;
            } else {
                x += oddRowShift;
            }

            y += verticalSpacing * row;

            hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;
            hexContainer.appendChild(hexagon);

            hexagonsCreated++;
        }
    }
}

// Initial call to create hexagons
const initialHexagonCount = 200; // Number of hexagons to create initially
createHexagons();

// Event listener for scrolling to add more elements
window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
        addMoreElements();
    }
});
