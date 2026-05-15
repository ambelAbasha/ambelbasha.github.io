document.addEventListener("DOMContentLoaded", () => {
    const addCameraBtn = document.querySelector(".add-camera-btn");
    const cameraGrid = document.getElementById("camera-grid");

    let cameras = [];
    const cameraLimit = 10;

    addCameraBtn.addEventListener("click", () => {
        if (cameras.length >= cameraLimit) {
            alert("You have reached the maximum limit of cameras. Apply for more access.");
            return;
        }

        const cameraId = cameras.length + 1;
        cameras.push({ id: cameraId, url: "", name: `Camera ${cameraId}` });
        renderCamera(cameraId);
    });

    function renderCamera(cameraId) {
        const cameraDiv = document.createElement("div");
        cameraDiv.className = "camera";
        cameraDiv.dataset.cameraId = cameraId;
        cameraDiv.draggable = true;

        cameraDiv.innerHTML = `
            <div class="video-wrapper">
                <iframe id="video-${cameraId}" src="" allowfullscreen></iframe>
            </div>
            <h3 contenteditable="true" onblur="renameCamera(${cameraId}, this.innerText)">
                Camera ${cameraId}
            </h3>
            <div class="controls">
                <input 
                    type="text" 
                    placeholder="Enter stream URL or media" 
                    id="streamUrl-${cameraId}" 
                    onchange="updateStreamUrl(${cameraId})"
                />
                <button id="submit-${cameraId}" class="submit-btn" onclick="submitStreamUrl(${cameraId})">Submit</button>
                <button class="fullscreen-btn" onclick="toggleFullscreen(${cameraId})">Fullscreen</button>
            </div>
        `;

        cameraGrid.appendChild(cameraDiv);
        initializeDragAndDrop(cameraDiv);
    }

    function renameCamera(cameraId, newName) {
        cameras[cameraId - 1].name = newName;
    }

    function updateStreamUrl(cameraId) {
        const streamInput = document.getElementById(`streamUrl-${cameraId}`);
        cameras[cameraId - 1].url = streamInput.value;
    }

    function submitStreamUrl(cameraId) {
        const camera = cameras[cameraId - 1];
        const videoIframe = document.getElementById(`video-${cameraId}`);
        videoIframe.src = camera.url;

        const streamInput = document.getElementById(`streamUrl-${cameraId}`);
        streamInput.style.display = "none";

        const changeIndicator = document.createElement("div");
        changeIndicator.className = "change-indicator";
        changeIndicator.innerText = "Change URL";
        changeIndicator.onclick = () => {
            streamInput.style.display = "block";
        };

        streamInput.parentNode.appendChild(changeIndicator);
    }

    function toggleFullscreen(cameraId) {
        const iframe = document.getElementById(`video-${cameraId}`);
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.mozRequestFullScreen) { /* Firefox */
            iframe.mozRequestFullScreen();
        } else if (iframe.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
            iframe.webkitRequestFullscreen();
        } else if (iframe.msRequestFullscreen) { /* IE/Edge */
            iframe.msRequestFullscreen();
        }
    }

    function initializeDragAndDrop(cameraDiv) {
        cameraDiv.addEventListener("dragstart", (e) => {
            e.dataTransfer.setData("text/plain", e.target.dataset.cameraId);
        });

        cameraGrid.addEventListener("dragover", (e) => {
            e.preventDefault();
        });

        cameraGrid.addEventListener("drop", (e) => {
            e.preventDefault();
            const draggedCameraId = e.dataTransfer.getData("text/plain");
            const draggedCamera = document.querySelector(`[data-camera-id='${draggedCameraId}']`);

            const target = e.target.closest(".camera");
            if (target && target !== draggedCamera) {
                cameraGrid.insertBefore(draggedCamera, target);
            }
        });
    }
});
