const svg = document.getElementById("perspectiveLines");
const width = window.innerWidth;
const height = window.innerHeight;

const leftX = 1;
const rightX = width - 1;
const centerX = width / 2;

const centerY = height / 2;
const topY = height * 0.10;
const bottomY = height * 0.90;

// Draw red circles on left and right centerY with smaller radius and visible edges
const leftCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
leftCircle.setAttribute("cx", leftX);
leftCircle.setAttribute("cy", centerY);
leftCircle.setAttribute("r", 2);
leftCircle.setAttribute("fill", "red");
leftCircle.setAttribute("stroke", "black");
leftCircle.setAttribute("stroke-width", "0.5");
svg.appendChild(leftCircle);

const rightCircle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
rightCircle.setAttribute("cx", rightX);
rightCircle.setAttribute("cy", centerY);
rightCircle.setAttribute("r", 2);
rightCircle.setAttribute("fill", "red");
rightCircle.setAttribute("stroke", "black");
rightCircle.setAttribute("stroke-width", "0.5");
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

const duration = 200;
const lines = [];

linesConfig.forEach(() => {
  const lineLeft = document.createElementNS("http://www.w3.org/2000/svg", "line");
  lineLeft.setAttribute("x1", leftX);
  lineLeft.setAttribute("y1", centerY);
  lineLeft.setAttribute("x2", centerX);
  lineLeft.setAttribute("y2", topY);
  lineLeft.setAttribute("stroke", "black");
  lineLeft.setAttribute("stroke-width", "1.5");
  lineLeft.setAttribute("stroke-linecap", "round");
  svg.appendChild(lineLeft);

  const lineRight = document.createElementNS("http://www.w3.org/2000/svg", "line");
  lineRight.setAttribute("x1", rightX);
  lineRight.setAttribute("y1", centerY);
  lineRight.setAttribute("x2", centerX);
  lineRight.setAttribute("y2", topY);
  lineRight.setAttribute("stroke", "black");
  lineRight.setAttribute("stroke-width", "1.5");
  lineRight.setAttribute("stroke-linecap", "round");
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

function easeOutCubic(t) {
  return 1 - Math.pow(1 - t, 3);
}

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
    const t = Math.min(elapsed / duration, 1);
    const progress = easeOutCubic(t);

    const startY = yPositions[line.currentIndex];
    const endY = yPositions[line.currentIndex + 1];
    const interpY = startY + (endY - startY) * progress;

    line.left.setAttribute("y2", interpY);
    line.right.setAttribute("y2", interpY);

    if (t >= 1) {
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
