document.addEventListener("DOMContentLoaded", () => {
    const cameraContainer = document.querySelector("main");

    // Function to create a new camera card
    const createCameraCard = (url) => {
        const card = document.createElement("div");
        card.classList.add("camera-card");

        card.innerHTML = `
            <div class="video-container">
                <video src="${url}" autoplay muted></video>
            </div>
            <div class="controls">
                <input type="text" class="url-input" value="${url}" />
                <button class="edit-url">Edit URL</button>
                <button class="fullscreen">Fullscreen</button>
                <button class="delete">Delete</button>
            </div>
        `;

        addCardEventListeners(card);
        cameraContainer.appendChild(card);
    };

    // Add event listeners to card controls
    const addCardEventListeners = (card) => {
        const video = card.querySelector("video");
        const urlInput = card.querySelector(".url-input");
        const editButton = card.querySelector(".edit-url");
        const fullscreenButton = card.querySelector(".fullscreen");
        const deleteButton = card.querySelector(".delete");

        editButton.addEventListener("click", () => {
            const isEditing = urlInput.style.display === "block";
            urlInput.style.display = isEditing ? "none" : "block";
            if (isEditing) {
                video.src = urlInput.value;
            }
        });

        fullscreenButton.addEventListener("click", () => {
            if (video.requestFullscreen) {
                video.requestFullscreen();
            } else if (video.webkitRequestFullscreen) { /* Safari */
                video.webkitRequestFullscreen();
            } else if (video.msRequestFullscreen) { /* IE11 */
                video.msRequestFullscreen();
            }
        });

        deleteButton.addEventListener("click", () => {
            card.remove();
        });

        // Drag and drop functionality
        card.draggable = true;

        card.addEventListener("dragstart", (e) => {
            e.dataTransfer.setData("text/plain", card.outerHTML);
            setTimeout(() => card.classList.add("hidden"), 0);
        });

        card.addEventListener("dragend", () => {
            card.classList.remove("hidden");
        });
    };

    // Handle dropping the card
    cameraContainer.addEventListener("dragover", (e) => {
        e.preventDefault();
    });

    cameraContainer.addEventListener("drop", (e) => {
        e.preventDefault();
        const data = e.dataTransfer.getData("text/plain");
        const droppedCard = document.createElement("div");
        droppedCard.innerHTML = data;
        const cardElement = droppedCard.firstElementChild;
        addCardEventListeners(cardElement);
        cameraContainer.appendChild(cardElement);
    });

    // Add button to create new camera cards
    const addButton = document.querySelector("#add-camera");
    addButton.addEventListener("click", () => {
        const defaultUrl = "https://example.com/stream.mp4"; // Default placeholder URL
        createCameraCard(defaultUrl);
    });
});
