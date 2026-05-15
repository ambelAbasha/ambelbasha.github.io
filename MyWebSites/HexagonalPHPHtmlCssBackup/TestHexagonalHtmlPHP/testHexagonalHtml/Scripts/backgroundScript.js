// Select the hexContainer element
const hexContainer = document.getElementById('hexContainer');

// Configuration variables
let initialHexagonCount = 200;
const hexagonSize = 150;
const hexagonHeight = 137;
const additionalSpacing = 40;

// Calculate grid dimensions based on window size
function calculateGridDimensions() {
    const numRowsInView = Math.ceil(window.innerHeight / (hexagonHeight * 0.75));
    const numCols = Math.ceil(window.innerWidth / (hexagonSize * 1.5 + additionalSpacing));
    return { numRowsInView, numCols };
}

// Function to create hexagon elements and position them
function createHexagons() {
    hexContainer.innerHTML = '';

    const { numRowsInView, numCols } = calculateGridDimensions();
    const numRows = numRowsInView + 2;

    const evenRowShift = -100;
    const oddRowShift = 33;
    const verticalSpacing = -24;

    let hexagonsCreated = 0;
    let newHexagonsHTML = '';

    for (let row = -1; row < numRows; row++) {
        for (let col = 0; col < numCols; col++) {
            if (hexagonsCreated >= initialHexagonCount) {
                localStorage.setItem('hexagonsHTML', newHexagonsHTML);
                return;
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
            newHexagonsHTML += hexagon.outerHTML;
        }
    }

    localStorage.setItem('hexagonsHTML', newHexagonsHTML);
    cloneTopHexagonsToBottom();
    addBottomRow(numCols);
}

// Function to clone top row hexagons to the bottom
function cloneTopHexagonsToBottom() {
    const hexagons = hexContainer.querySelectorAll('.hexagon');
    const topHexagons = Array.from(hexagons).filter(hex => hex.style.transform.includes('translate('));
    const topRowHexagons = topHexagons.slice(0, 7);

    const { numRowsInView, numCols } = calculateGridDimensions();

    const evenRowShift = -100;
    const oddRowShift = 33;
    const verticalSpacing = -24;

    const startRow = numRowsInView + 1;

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

    const evenRowShift = -100;
    const oddRowShift = 33;
    const verticalSpacing = -24;

    const row = numRowsInView + 2;

    for (let col = 0; col < numCols; col++) {
        const hexagon = document.createElement('div');
        hexagon.classList.add('hexagon');

        if (col === 0) {
            hexagon.classList.add('bottom-left');
        } else if (col === numCols - 1) {
            hexagon.classList.add('bottom-right');
        }

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
    }
}

// Event listener for window resize to adjust grid dimensions
window.addEventListener('resize', () => {
    createHexagons(); // Recreate hexagons on window resize
});

// Call the function to initially create hexagons
createHexagons();
