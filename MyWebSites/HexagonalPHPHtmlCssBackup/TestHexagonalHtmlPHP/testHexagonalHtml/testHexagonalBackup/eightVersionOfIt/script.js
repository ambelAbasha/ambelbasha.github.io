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
let numRows = Math.ceil(window.innerHeight / (hexagonHeight * 0.75)); // Adjusted vertical spacing
const numCols = Math.ceil(window.innerWidth / (hexagonSize * 1.5 + additionalSpacing));

// Add two more rows
numRows += 2;

// Define shifts for even and odd rows
const evenRowShift = -100; // Shift for even rows
const oddRowShift = 30; // Shift for odd rows

// Calculate the vertical and horizontal spacing
const verticalSpacing = -26; // Reduced vertical spacing between hexagons (adjust as needed)
const horizontalSpacing = 6; // Horizontal spacing between hexagons

// Counter for hexagons created
let hexagonsCreated = 0;

// Function to handle keydown event
function handleKeyPress(event) {
    // Check if the Enter key is pressed (key code 13)
    if (event.keyCode === 13) {
        event.preventDefault(); // Prevent the default action
        return false;
    }
}

// Add event listener to intercept keydown events
document.addEventListener('keydown', handleKeyPress);

// Function to create hexagon elements and position them
function createHexagons() {
    hexContainer.innerHTML = ''; // Clear hexContainer first

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
}

// Call the function to initially create hexagons
createHexagons();
