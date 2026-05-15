const dashboard = document.getElementById("dashboard");
const addCameraBtn = document.getElementById("addCameraBtn");
const cameraLimitMessage = document.getElementById("cameraLimitMessage");

let cameras = [];
const cameraLimit = 10;
let cameraStreams = [];

addCameraBtn.addEventListener("click", () => {
  if (cameras.length >= cameraLimit) {
    cameraLimitMessage.innerText =
      "You have reached the maximum limit of cameras. Apply for more access.";
    return;
  }
  cameraLimitMessage.innerText = "";

  const cameraId = cameras.length + 1;
  cameras.push({ id: cameraId, url: "", name: `Camera ${cameraId}` });

  const cameraDiv = document.createElement("div");
  cameraDiv.className = "camera";
  cameraDiv.dataset.cameraId = cameraId;

  cameraDiv.innerHTML = `
    <div class="video-wrapper">
      <iframe id="video-${cameraId}" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
    </div>
    <h3 contenteditable="true" onblur="renameCamera(${cameraId}, this.innerText)">
      Camera ${cameraId}
    </h3>
    <div class="controls">
      <input 
        type="text" 
        placeholder="Enter YouTube live stream URL" 
        id="streamUrl-${cameraId}" 
        onchange="updateStreamUrl(${cameraId})"
      />
      <button id="submit-${cameraId}" onclick="submitStreamUrl(${cameraId})">Submit</button>
      <button class="fullscreen-btn" onclick="toggleFullscreen(${cameraId})">Fullscreen</button>
    </div>
  `;

  cameraDiv.addEventListener("click", () => toggleSelect(cameraDiv));
  dashboard.appendChild(cameraDiv);

  updateGridLayout();
});

// Remaining functions...
