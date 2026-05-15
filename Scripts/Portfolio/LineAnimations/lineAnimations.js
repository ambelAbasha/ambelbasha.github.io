const svg = document.getElementById("perspectiveLines");
const width = window.innerWidth;
const height = window.innerHeight;

const leftX = 3;
const rightX = width - 3;
const centerX = width / 2;

const centerY = height / 2;
const topY = height * 0.10;
const bottomY = height * 0.90;

// Draw red circles on left and right centerY
const leftCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
leftCircle.setAttribute("cx", leftX);
leftCircle.setAttribute("cy", centerY);
leftCircle.setAttribute("r", 5);
svg.appendChild(leftCircle);

const rightCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
rightCircle.setAttribute("cx", rightX);
rightCircle.setAttribute("cy", centerY);
rightCircle.setAttribute("r", 5);
svg.appendChild(rightCircle);

// Generate 10 y positions from topY to bottomY
const yPositions = [];
for (let i = 0; i < 10; i++) {
  const y = topY + (i * (bottomY - topY)) / 9;
  yPositions.push(y);
}

// Each line config
const linesConfig = [
  { passCount: 10, finalStopIndex: 9 },
  { passCount: 9, finalStopIndex: 8 },
  { passCount: 8, finalStopIndex: 7 },
  { passCount: 7, finalStopIndex: 6 },
  { passCount: 6, finalStopIndex: 5 },
  { passCount: 5, finalStopIndex: 4 },
  { passCount: 4, finalStopIndex: 3 },
  { passCount: 3, finalStopIndex: 2 },
  { passCount: 2, finalStopIndex: 1 },
  { passCount: 1, finalStopIndex: 0 }
];

const duration = 600;
const lines = [];

linesConfig.forEach(() => {
  const lineLeft = document.createElementNS("http://www.w3.org/2000/svg", "line");
  lineLeft.setAttribute("x1", leftX);
  lineLeft.setAttribute("y1", centerY);
  lineLeft.setAttribute("x2", centerX);
  lineLeft.setAttribute("y2", topY);
  svg.appendChild(lineLeft);

  const lineRight = document.createElementNS("http://www.w3.org/2000/svg", "line");
  lineRight.setAttribute("x1", rightX);
  lineRight.setAttribute("y1", centerY);
  lineRight.setAttribute("x2", centerX);
  lineRight.setAttribute("y2", topY);
  svg.appendChild(lineRight);

  lines.push({
    left: lineLeft,
    right: lineRight,
    currentIndex: 0,
    animating: false
  });
});

let currentLineIndex = 0;
let startTime = null;

function animate(time) {
  if (!startTime) startTime = time;
  const elapsed = time - startTime;

  if (currentLineIndex >= lines.length) return;

  const line = lines[currentLineIndex];
  const config = linesConfig[currentLineIndex];

  if (!line.animating) {
    line.animating = true;
    line.currentIndex = 0;
  }

  if (line.currentIndex < config.passCount - 1) {
    const progress = Math.min(elapsed / duration, 1);
    const startY = yPositions[line.currentIndex];
    const endY = yPositions[line.currentIndex + 1];
    const interpY = startY + (endY - startY) * progress;

    line.left.setAttribute("y2", interpY);
    line.right.setAttribute("y2", interpY);

    if (progress >= 1) {
      line.currentIndex++;
      startTime = time;
    }

    requestAnimationFrame(animate);
  } else {
    const finalY = yPositions[config.finalStopIndex];
    line.left.setAttribute("y2", finalY);
    line.right.setAttribute("y2", finalY);

    currentLineIndex++;
    startTime = time;

    if (currentLineIndex < lines.length) {
      requestAnimationFrame(animate);
    }
  }
}

requestAnimationFrame(animate);
