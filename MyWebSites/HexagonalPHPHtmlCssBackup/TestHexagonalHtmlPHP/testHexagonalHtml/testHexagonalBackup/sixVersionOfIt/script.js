// Get the hexContainer element from the HTML
const hexContainer = document.getElementById('hexContainer');

// Number of hexagons to create
const hexagonCount = 100;

// Calculate grid dimensions and other parameters
const hexagonSize = 150; // Enlarged size of each hexagon
const hexagonHeight = 140; // Adjusted height for tighter vertical spacing
const additionalSpacing = 36; // Enlarged horizontal spacing between hexagons
const sideLength = hexagonSize * Math.sqrt(3) / 2; // Length of the side of the hexagon

// Calculate the number of rows and columns needed to cover the window
const numRows = Math.ceil(window.innerHeight / (hexagonHeight * 0.75)) + 2; // Adjusted to add two more rows
const numCols = Math.ceil(window.innerWidth / (hexagonSize * 1.5 + additionalSpacing));

// Define shifts for even and odd rows
const evenRowShift = -100; // Shift for even rows
const oddRowShift = 30; // Shift for odd rows

// Calculate the vertical and horizontal spacing
const verticalSpacing = -23; // Reduced vertical spacing between hexagons (adjusted by -23 pixels)
const horizontalSpacing = 6; // Horizontal spacing between hexagons

// Counter for hexagons created
let hexagonsCreated = 0;

// Position each hexagon in a non-overlapping grid covering the entire background
for (let row = -1; row < numRows; row++) { // Start from -1 to add an extra row above the first row
    for (let col = 0; col < numCols; col++) {
        if (hexagonsCreated >= hexagonCount) {
            break; // Exit the loop if enough hexagons are created
        }

        const hexagon = document.createElement('div');
        hexagon.classList.add('hexagon');
        hexContainer.appendChild(hexagon);

        // Calculate position based on grid coordinates
        let x = col * (hexagonSize * 1.5 + additionalSpacing);
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
    }
}
