// JavaScript code to create and position hexagons
const hexContainer = document.getElementById('hexContainer');

// Number of hexagons to create initially
let initialHexagonCount = 200; // Initial number of hexagons

// Calculate grid dimensions and other parameters
const hexagonSize = 150; // Enlarged size of each hexagon
const hexagonHeight = 137; // Adjusted height for tighter vertical spacing (reduced by 3 pixels)
const additionalSpacing = 40; // Increased horizontal spacing between hexagons (by 2 pixels on each side)

// Calculate the number of rows and columns needed to cover the window
function calculateGridDimensions() {
    const numRowsInView = Math.ceil(window.innerHeight / (hexagonHeight * 0.75)); // Adjusted vertical spacing
    const numCols = Math.ceil(window.innerWidth / (hexagonSize * 1.5 + additionalSpacing));
    return { numRowsInView, numCols };
}

// Function to create hexagon elements and position them
function createHexagons() {
    // Clear hexContainer first to avoid appending duplicates
    hexContainer.innerHTML = '';

    // Check if hexagons HTML is stored in local storage
    let hexagonsHTML = localStorage.getItem('hexagonsHTML');

    if (hexagonsHTML) {
        // Use stored hexagons HTML
        hexContainer.innerHTML = hexagonsHTML;
    } else {
        const { numRowsInView, numCols } = calculateGridDimensions();

        // Add extra rows for top and bottom
        const numRows = numRowsInView + 2;

        // Define shifts for even and odd rows
        const evenRowShift = -100; // Shift for even rows (adjusted for additional spacing)
        const oddRowShift = 36; // Shift for odd rows (adjusted for additional spacing)

        // Calculate the vertical and horizontal spacing
        const verticalSpacing = -26; // Reduced vertical spacing between hexagons (adjusted by -25 pixels)

        // Counter for hexagons created
        let hexagonsCreated = 0;

        let newHexagonsHTML = '';

        for (let row = -1; row < numRows; row++) { // Start from -1 to add an extra row above the first row
            for (let col = 0; col < numCols; col++) {
                if (hexagonsCreated >= initialHexagonCount) {
                    // Store hexagons HTML in local storage
                    localStorage.setItem('hexagonsHTML', newHexagonsHTML);
                    return; // Exit the function if enough hexagons are created
                }

                const hexagon = document.createElement('div');
                hexagon.classList.add('hexagon');

                // Add additional classes based on position for styling
                if (row === -1 || row === numRowsInView || col === 0 || col === numCols - 1) {
                    if (row === -1 && col === 0) {
                        hexagon.classList.add('hexagon', 'center');
                    } else if (row === -1 && col === numCols - 1) {
                        hexagon.classList.add('hexagon', 'center');
                    } else if (row === numRowsInView && col === 0) {
                        hexagon.classList.add('hexagon', 'center');
                    } else if (row === numRowsInView && col === numCols - 1) {
                        hexagon.classList.add('hexagon', 'center');
                    } else if (row === -1) {
                        hexagon.classList.add('hexagon', 'top');
                    } else if (row === numRowsInView) {
                        hexagon.classList.add('hexagon', 'bottom');
                    } else if (col === 0) {
                        hexagon.classList.add('hexagon', 'left');
                    } else if (col === numCols - 1) {
                        hexagon.classList.add('hexagon', 'right');
                    }
                }

                hexContainer.appendChild(hexagon);

                // Calculate position based on grid coordinates
                let x = col * (hexagonSize * 1.51 + additionalSpacing);
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

                // Append hexagon HTML to newHexagonsHTML
                newHexagonsHTML += hexagon.outerHTML;
            }
        }

        // Store hexagons HTML in local storage
        localStorage.setItem('hexagonsHTML', newHexagonsHTML);
    }
}

// Function to add more elements to the bottom of the page
function addMoreElements() {
    const { numRowsInView, numCols } = calculateGridDimensions();

    // Number of additional elements to add
    const additionalElementsCount = 100;

    // Define shifts and spacing similar to createHexagons() function
    const evenRowShift = -100;
    const oddRowShift = 32;
    const verticalSpacing = -28;

    // Determine starting row for new elements
    const startRow = numRowsInView; // Start just after the visible rows

    // Calculate total rows needed for additional elements
    const totalRows = startRow + Math.ceil(additionalElementsCount / numCols);

    // Calculate hexagons already created
    let hexagonsCreated = initialHexagonCount;

    // Create additional elements
    for (let row = startRow; row < totalRows; row++) {
        for (let col = 0; col < numCols; col++) {
            if (hexagonsCreated >= initialHexagonCount + additionalElementsCount) {
                return; // Exit the function if enough elements are created
            }

            const hexagon = document.createElement('div');
            hexagon.classList.add('hexagon');

            // Add additional classes based on position for styling
            if (row === -1 || row === numRowsInView || col === 0 || col === numCols - 1) {
                if (row === -1 && col === 0) {
                    hexagon.classList.add('hexagon', 'center');
                } else if (row === -1 && col === numCols - 1) {
                    hexagon.classList.add('hexagon', 'center');
                } else if (row === numRowsInView && col === 0) {
                    hexagon.classList.add('hexagon', 'center');
                } else if (row === numRowsInView && col === numCols - 1) {
                    hexagon.classList.add('hexagon', 'center');
                } else if (row === -1) {
                    hexagon.classList.add('hexagon', 'top');
                } else if (row === numRowsInView) {
                    hexagon.classList.add('hexagon', 'bottom');
                } else if (col === 0) {
                    hexagon.classList.add('hexagon', 'left');
                } else if (col === numCols - 1) {
                    hexagon.classList.add('hexagon', 'right');
                }
            }

            hexContainer.appendChild(hexagon);

            // Calculate position based on grid coordinates
            let x = col * (hexagonSize * 1.51 + additionalSpacing);
            let y = row * (hexagonHeight * 0.75);

            // Apply shifts based on row parity
            if (row % 2 === 0) {
                x += evenRowShift;
            } else {
                x += oddRowShift;
            }

            // Adjust vertical positioning with spacing
            y += verticalSpacing * row;

            hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;

            hexagonsCreated++; // Increment the count of created elements
        }
    }
}

// Call the function to initially create hexagons
createHexagons();

// Call the function to add more elements when needed
addMoreElements();
