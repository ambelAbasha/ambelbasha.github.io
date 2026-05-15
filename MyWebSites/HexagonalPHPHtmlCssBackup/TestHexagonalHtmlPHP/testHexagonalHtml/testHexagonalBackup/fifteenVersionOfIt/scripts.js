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

    const { numRowsInView, numCols } = calculateGridDimensions();

    // Add extra rows for top and bottom
    const numRows = numRowsInView + 2;

    // Define shifts for even and odd rows
    const evenRowShift = -100; // Shift for even rows (adjusted for additional spacing)
    const oddRowShift = 33; // Shift for odd rows (adjusted for additional spacing)

    // Calculate the vertical and horizontal spacing
    const verticalSpacing = -24; // Reduced vertical spacing between hexagons (adjusted by -25 pixels)

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
                    hexagon.classList.add('top-left');
                } else if (row === -1 && col === numCols - 1) {
                    hexagon.classList.add('top-right');
                } else if (row === numRowsInView && col === 0) {
                    hexagon.classList.add('bottom-left');
                } else if (row === numRowsInView && col === numCols - 1) {
                    hexagon.classList.add('bottom-right');
                } else if (row === -1) {
                    hexagon.classList.add('top');
                } else if (row === numRowsInView) {
                    hexagon.classList.add('bottom');
                } else if (col === 0) {
                    hexagon.classList.add('left');
                } else if (col === numCols - 1) {
                    hexagon.classList.add('right');
                }
            }

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

            hexContainer.appendChild(hexagon);

            hexagonsCreated++; // Increment the count of created hexagons

            // Append hexagon HTML to newHexagonsHTML
            newHexagonsHTML += hexagon.outerHTML;
        }
    }

    // Store hexagons HTML in local storage
    localStorage.setItem('hexagonsHTML', newHexagonsHTML);

    // Clone and position the top row hexagons at the bottom
    cloneTopHexagonsToBottom();

    // Add additional row of hexagons at the bottom
    addBottomRow(numCols);
}

// Function to clone top row hexagons to the bottom
function cloneTopHexagonsToBottom() {
    const hexagons = hexContainer.querySelectorAll('.hexagon');
    const topHexagons = Array.from(hexagons).filter(hex => hex.style.transform.includes('translate('));
    const topRowHexagons = topHexagons.slice(0, 7); // Get the first 7 hexagons from the top

    const { numRowsInView, numCols } = calculateGridDimensions();

    // Define shifts and spacing similar to createHexagons() function
    const evenRowShift = -100;
    const oddRowShift = 33;
    const verticalSpacing = -24;

    const startRow = numRowsInView + 1; // Start just after the visible rows

    topRowHexagons.forEach((hexagon, index) => {
        const newHexagon = hexagon.cloneNode(true);
        const col = index;
        const row = startRow;

        let x = col * (hexagonSize * 1.51 + additionalSpacing);
        let y = row * (hexagonHeight * 0.75);

        if (row % 2 === 0) {
            x += evenRowShift;
        } else {
            x += oddRowShift;
        }

        y += verticalSpacing * row;

        newHexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;

        hexContainer.appendChild(newHexagon);
    });
}

// Function to add a row of hexagons at the bottom of the container
function addBottomRow(numCols) {
    const { numRowsInView } = calculateGridDimensions();

    // Define shifts and spacing similar to createHexagons() function
    const evenRowShift = -100;
    const oddRowShift = 33;
    const verticalSpacing = -24;

    const row = numRowsInView + 2; // Position the row below the last visible row

    for (let col = 0; col < numCols; col++) {
        const hexagon = document.createElement('div');
        hexagon.classList.add('hexagon');

        // Add additional classes based on position for styling
        if (col === 0) {
            hexagon.classList.add('bottom-left');
        } else if (col === numCols - 1) {
            hexagon.classList.add('bottom-right');
        }

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

        hexContainer.appendChild(hexagon);
    }
}

// Function to add more elements to the bottom of the page
function addMoreElements() {
    const { numRowsInView, numCols } = calculateGridDimensions();

    // Number of additional elements to add
    const additionalElementsCount = 120;

    // Define shifts and spacing similar to createHexagons() function
    const evenRowShift = -100;
    const oddRowShift = 33;
    const verticalSpacing = -24;

    // Determine starting row for new elements
    const startRow = numRowsInView + 1; // Start just after the visible rows

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
                    hexagon.classList.add('top-left');
                } else if ( row === -1 && col === numCols - 1) {
                    hexagon.classList.add('top-right');
                } else if (row === numRowsInView && col === 0) {
                    hexagon.classList.add('bottom-left');
                } else if (row === numRowsInView && col === numCols - 1) {
                    hexagon.classList.add('bottom-right');
                } else if (row === -1) {
                    hexagon.classList.add('top');
                } else if (row === numRowsInView) {
                    hexagon.classList.add('bottom');
                } else if (col === 0) {
                    hexagon.classList.add('left');
                } else if (col === numCols - 1) {
                    hexagon.classList.add('right');
                }
            }

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

            hexContainer.appendChild(hexagon);

            hexagonsCreated++; // Increment the count of created elements
        }
    }
}

// Call the function to initially create hexagons
createHexagons();
