// Get the hexContainer element from the HTML
const hexContainer = document.getElementById('hexContainer');

// Define hexagon parameters
const hexagonSize = 150; // Size of each hexagon
const hexagonHeight = 140; // Height for tighter vertical spacing
const additionalSpacing = 36; // Horizontal spacing between hexagons

// Calculate the number of rows and columns needed to cover the window
const numRows = Math.ceil(window.innerHeight / (hexagonHeight * 0.75)) + 1; // Adjusted vertical spacing and add one more row
const numCols = Math.ceil(window.innerWidth / (hexagonSize * 1.5 + additionalSpacing));

// Add four more rows to ensure enough hexagons to fill the page
const totalRows = numRows + 4;

// Define shifts for even and odd rows
const evenRowShift = -100; // Shift for even rows
const oddRowShift = 30; // Shift for odd rows

// Calculate the vertical and horizontal spacing
const verticalSpacing = -27; // Vertical spacing between hexagons
const horizontalSpacing = 2; // Horizontal spacing between hexagons (2px symmetrically on both sides)

// Function to create hexagon elements and position them
function createHexagons() {
    hexContainer.innerHTML = ''; // Clear hexContainer first
    let hexagonsCreated = 0; // Counter for hexagons created

    for (let row = -1; row < totalRows; row++) { // Start from -1 to add an extra row above the first row
        for (let col = 0; col < numCols; col++) {
            const hexagon = document.createElement('div');
            hexagon.classList.add('hexagon');
            hexContainer.appendChild(hexagon);

            // Calculate position based on grid coordinates with added spacing
            let x = col * (hexagonSize * 1.5 + additionalSpacing) + (col * 2); // Add 2px spacing on both sides
            let y = row * (hexagonHeight * 0.75); // Adjusted vertical spacing

            // Apply shifts based on row parity
            if (row % 2 === 0) {
                x += evenRowShift;
            } else {
                x += oddRowShift;
            }

            // Adjust vertical positioning with spacing
            y += verticalSpacing * row;

            hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;

            hexagonsCreated++; // Increment the count of created hexagons

            if (hexagonsCreated >= numCols * totalRows) {
                break; // Exit the loop if enough hexagons are created
            }
        }
    }

    // Center the hexContainer content
    const containerWidth = numCols * (hexagonSize * 1.5 + additionalSpacing) + (numCols * 2); // Adjust width with added spacing
    const containerHeight = totalRows * hexagonHeight - (totalRows - 1) * 0.25 * hexagonSize;

    hexContainer.style.position = 'absolute';
    hexContainer.style.top = '50%';
    hexContainer.style.left = '50%';
    hexContainer.style.transform = 'translate(-50%, -50%)';
    hexContainer.style.width = `${containerWidth}px`;
    hexContainer.style.height = `${containerHeight}px`;
}

// Call the function to initially create hexagons
createHexagons();

// Ensure hexagons are static upon refresh by setting fixed grid dimensions
window.addEventListener('resize', () => {
    createHexagons();
});
