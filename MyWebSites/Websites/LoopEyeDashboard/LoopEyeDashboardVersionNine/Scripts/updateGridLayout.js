const cameraGrid = document.getElementById("cameraGrid");
const addCameraBtn = document.getElementById("addCameraBtn");
const cameraLimitMessage = document.getElementById("cameraLimitMessage");

const cameraLimit = 10;
let cameras = [];

addCameraBtn.addEventListener("click", () => {
    if (cameras.length >= cameraLimit) {
        cameraLimitMessage.textContent = "You have reached the maximum limit of cameras.";
        return;
    }
    cameraLimitMessage.textContent = "";

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
            <input type="text" placeholder="Enter YouTube live stream URL" id="streamUrl-${cameraId}" onchange="updateStreamUrl(${cameraId})">
            <button id="submit-${cameraId}" onclick="submitStreamUrl(${cameraId})">Submit</button>
            <button id="change-${cameraId}" style="display: none;" onclick="changeStreamUrl(${cameraId})">Change URL</button>
            <button class="fullscreen-btn" onclick="toggleFullscreen(${cameraId})">Fullscreen</button>
            <button class="delete-btn" onclick="deleteCamera(${cameraId})">Delete</button>
        </div>
    `;

    cameraGrid.appendChild(cameraDiv);
});

function submitStreamUrl(cameraId) {
    const streamUrl = document.getElementById(`streamUrl-${cameraId}`).value;
    const videoElement = document.getElementById(`video-${cameraId}`);
    const submitButton = document.getElementById(`submit-${cameraId}`);
    const changeButton = document.getElementById(`change-${cameraId}`);

    const videoId = extractYouTubeId(streamUrl);

    if (videoId) {
        const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        videoElement.src = embedUrl;
        cameras[cameraId - 1].url = streamUrl;
        
        // Hide the submit button, show the change button
        submitButton.style.display = "none";
        changeButton.style.display = "inline-block";
        
        // Optionally hide URL input field after a delay if you want
        setTimeout(() => {
            document.getElementById(`streamUrl-${cameraId}`).style.display = 'none';
        }, 30000); // Hide after 30 seconds
    } else {
        alert("Invalid YouTube URL");
    }
}

function changeStreamUrl(cameraId) {
    const streamUrl = document.getElementById(`streamUrl-${cameraId}`).value;
    const videoElement = document.getElementById(`video-${cameraId}`);

    const videoId = extractYouTubeId(streamUrl);

    if (videoId) {
        const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        videoElement.src = embedUrl;
        cameras[cameraId - 1].url = streamUrl;
        console.log(`Camera ${cameraId} stream URL updated.`);
        
        // Re-display the URL input field for further changes
        document.getElementById(`streamUrl-${cameraId}`).style.display = "inline-block";
        
        // Show the submit button and hide the change button again
        document.getElementById(`submit-${cameraId}`).style.display = "inline-block";
        document.getElementById(`change-${cameraId}`).style.display = "none";
    } else {
        alert("Invalid YouTube URL");
    }
}

function extractYouTubeId(url) {
    const regex = /(?:https?:\/\/(?:www\.)?youtube\.com\/(?:.*[?&]v=|.*\/e(?:mbed)?\/|live\/)([a-zA-Z0-9_-]+))/;
    const match = url.match(regex);
    return match ? match[1] : null;
}

function toggleFullscreen(cameraId) {
    const videoElement = document.getElementById(`video-${cameraId}`);
    if (videoElement.requestFullscreen) {
        videoElement.requestFullscreen();
    } else if (videoElement.mozRequestFullScreen) { /* Firefox */
        videoElement.mozRequestFullScreen();
    } else if (videoElement.webkitRequestFullscreen) { /* Chrome, Safari, Opera */
        videoElement.webkitRequestFullscreen();
    } else if (videoElement.msRequestFullscreen) { /* IE/Edge */
        videoElement.msRequestFullscreen();
    }
}

function deleteCamera(cameraId) {
    const cameraDiv = document.querySelector(`[data-camera-id="${cameraId}"]`);
    cameraDiv.remove();
    cameras = cameras.filter(camera => camera.id !== cameraId);
}

function renameCamera(cameraId, newName) {
    cameras[cameraId - 1].name = newName;
}
