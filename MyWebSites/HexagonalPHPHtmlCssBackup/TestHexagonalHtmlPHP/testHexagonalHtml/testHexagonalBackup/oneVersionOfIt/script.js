const hexContainer = document.getElementById('hexContainer');
const hexagonCount = 100; // Number of hexagons

// Calculate grid dimensions
const hexagonSize = 148; // Slightly reduced size of each hexagon to fit tightly
const hexagonHeight = 172.5; // Adjusted height for better coverage
const additionalSpacing = 44; // Increased horizontal spacing between hexagons by 12 pixels
const sideLength = hexagonSize / 2; // Length of the side of the hexagon
const numRows = Math.ceil(window.innerHeight / (hexagonHeight * 0.75));
const numCols = Math.ceil(window.innerWidth / (hexagonSize * 1.5));

// Define shifts for even and odd rows
const evenRowShift = -100; // Shift for even rows
const oddRowShift = 28; // Shift for odd rows

// Position each hexagon in a non-overlapping grid covering the entire background
for (let row = 0; row < numRows; row++) {
    for (let col = 0; col < numCols; col++) {
        const hexagon = document.createElement('div');
        hexagon.classList.add('hexagon');
        hexContainer.appendChild(hexagon);

        // Calculate position based on grid coordinates
        let x = col * (hexagonSize + sideLength + additionalSpacing);
        let y = row * (hexagonHeight * 0.75);

        // Apply shifts based on row parity
        if (row % 2 === 0) {
            x += evenRowShift;
        } else {
            x += oddRowShift;
        }

        hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;
    }
}
