const hexContainer = document.getElementById('hexContainer');

let initialHexagonCount = 200;
const hexagonSize = 150;
const hexagonHeight = 137;
const additionalSpacing = 40;

function calculateGridDimensions() {
    const numRowsInView = Math.ceil(window.innerHeight / (hexagonHeight * 0.75));
    const numCols = Math.ceil(window.innerWidth / (hexagonSize * 1.5 + additionalSpacing));
    return { numRowsInView, numCols };
}

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

            if (row === -1 || row === numRowsInView || col === 0 || col === numCols - 1) {
                if (row === -1 && col === 0) hexagon.classList.add('top-left');
                else if (row === -1 && col === numCols - 1) hexagon.classList.add('top-right');
                else if (row === numRowsInView && col === 0) hexagon.classList.add('bottom-left');
                else if (row === numRowsInView && col === numCols - 1) hexagon.classList.add('bottom-right');
                else if (row === -1) hexagon.classList.add('top');
                else if (row === numRowsInView) hexagon.classList.add('bottom');
                else if (col === 0) hexagon.classList.add('left');
                else if (col === numCols - 1) hexagon.classList.add('right');
            }

            let x = col * (hexagonSize * 1.51 + additionalSpacing);
            let y = row * (hexagonHeight * 0.75);
            if (row % 2 === 0) x += evenRowShift;
            else x += oddRowShift;
            y += verticalSpacing * row;

            hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;

            // Add number label
            const numberLabel = document.createElement('div');
            numberLabel.classList.add('hex-number');
            numberLabel.textContent = hexagonsCreated + 1;
            hexagon.appendChild(numberLabel);

            hexContainer.appendChild(hexagon);
            hexagonsCreated++;
            newHexagonsHTML += hexagon.outerHTML;
        }
    }

    localStorage.setItem('hexagonsHTML', newHexagonsHTML);
    cloneTopHexagonsToBottom();
    addBottomRow(numCols);
}

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
        if (row % 2 === 0) x += evenRowShift;
        else x += oddRowShift;
        y += verticalSpacing * row;
        newHexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;
        hexContainer.appendChild(newHexagon);
    });
}

function addBottomRow(numCols) {
    const { numRowsInView } = calculateGridDimensions();
    const evenRowShift = -100;
    const oddRowShift = 33;
    const verticalSpacing = -24;
    const row = numRowsInView + 2;

    for (let col = 0; col < numCols; col++) {
        const hexagon = document.createElement('div');
        hexagon.classList.add('hexagon');
        if (col === 0) hexagon.classList.add('bottom-left');
        else if (col === numCols - 1) hexagon.classList.add('bottom-right');
        let x = col * (hexagonSize * 1.51 + additionalSpacing);
        let y = row * (hexagonHeight * 0.75);
        if (row % 2 === 0) x += evenRowShift;
        else x += oddRowShift;
        y += verticalSpacing * row;
        hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;
        hexContainer.appendChild(hexagon);
    }
}

function addMoreElements() {
    const { numRowsInView, numCols } = calculateGridDimensions();
    const additionalElementsCount = 120;
    const evenRowShift = -100;
    const oddRowShift = 33;
    const verticalSpacing = -24;
    const startRow = numRowsInView + 1;
    const totalRows = startRow + Math.ceil(additionalElementsCount / numCols);
    let hexagonsCreated = initialHexagonCount;

    for (let row = startRow; row < totalRows; row++) {
        for (let col = 0; col < numCols; col++) {
            if (hexagonsCreated >= initialHexagonCount + additionalElementsCount) return;
            const hexagon = document.createElement('div');
            hexagon.classList.add('hexagon');

            if (row === -1 || row === numRowsInView || col === 0 || col === numCols - 1) {
                if (row === -1 && col === 0) hexagon.classList.add('top-left');
                else if (row === -1 && col === numCols - 1) hexagon.classList.add('top-right');
                else if (row === numRowsInView && col === 0) hexagon.classList.add('bottom-left');
                else if (row === numRowsInView && col === numCols - 1) hexagon.classList.add('bottom-right');
                else if (row === -1) hexagon.classList.add('top');
                else if (row === numRowsInView) hexagon.classList.add('bottom');
                else if (col === 0) hexagon.classList.add('left');
                else if (col === numCols - 1) hexagon.classList.add('right');
            }

            let x = col * (hexagonSize * 1.51 + additionalSpacing);
            let y = row * (hexagonHeight * 0.75);
            if (row % 2 === 0) x += evenRowShift;
            else x += oddRowShift;
            y += verticalSpacing * row;

            hexagon.style.transform = `translate(${x}px, ${y}px) rotate(90deg)`;

            const numberLabel = document.createElement('div');
            numberLabel.classList.add('hex-number');
            numberLabel.textContent = hexagonsCreated + 1;
            hexagon.appendChild(numberLabel);

            hexContainer.appendChild(hexagon);
            hexagonsCreated++;
        }
    }
}

createHexagons();