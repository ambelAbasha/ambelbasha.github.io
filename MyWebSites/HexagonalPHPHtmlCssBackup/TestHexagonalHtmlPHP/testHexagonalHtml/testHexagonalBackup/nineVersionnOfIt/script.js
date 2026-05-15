// Get the hexContainer element from the HTML
const hexContainer = document.getElementById('hexContainer');

// Define hexagon parameters
const hexagonSize = 150; // Size of each hexagon
const hexagonHeight = 140; // Height for tighter vertical spacing
const additionalSpacing = 36; // Horizontal spacing between hexagons

// Fixed number of rows and columns
const numRows = 12; // Fixed number of rows
const numCols = 10; // Fixed number of columns

// Horizontal spacing between hexagons (additional 2px for symmetry on both sides)
const horizontalSpacing = 2; // Adjusted horizontal spacing

// Define shifts for even and odd rows
const evenRowShift = -100; // Shift for even rows
const oddRowShift = 30; // Shift for odd rows

// Function to create hexagon elements and position them
function createHexagons() {
    hexContainer.innerHTML = ''; // Clear hexContainer first
    let hexagonsCreated = 0; // Counter for hexagons created

    for (let row = 0; row < numRows; row++) {
        for (let col = 0; col < numCols; col++) {
            const hexagon = document.createElement('div');
            hexagon.classList.add('hexagon');
            hexContainer.appendChild(hexagon);

            // Calculate position based on grid coordinates
            let x = col * (hexagonSize * 1.5 + additionalSpacing + 2 * horizontalSpacing) + (row % 2 === 0 ? 0 : 2 * horizontalSpacing);
            let y = row * (hexagonHeight * 0.75); // Adjusted vertical spacing

            // Apply shifts based on row parity
            if (row % 2 === 0) {
                x += evenRowShift;
            } else {
                x += oddRowShift;
            }

            // Adjust vertical positioning with spacing
            y += hexagonSize / 2; // Center vertically

            hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;

            hexagonsCreated++; // Increment the count of created hexagons
        }
    }

    // Center the hexContainer content
    const containerWidth = numCols * (hexagonSize * 1.5 + additionalSpacing + 2 * horizontalSpacing) - additionalSpacing - 2 * horizontalSpacing;
    const containerHeight = numRows * hexagonHeight - (numRows - 1) * 0.25 * hexagonSize;
    
    hexContainer.style.position = 'absolute';
    hexContainer.style.top = '50%';
    hexContainer.style.left = '50%';
    hexContainer.style.transform = `translate(-50%, -50%)`;
    hexContainer.style.width = `${containerWidth}px`;
    hexContainer.style.height = `${containerHeight}px`;
}

// Call the function to initially create hexagons
createHexagons();

// Ensure hexagons are static upon refresh by setting fixed grid dimensions
window.addEventListener('resize', () => {
    createHexagons();
});
