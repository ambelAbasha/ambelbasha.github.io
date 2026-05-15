document.addEventListener('DOMContentLoaded', function () {
    const INSTANCE_KEY = 'uniquePageInstanceId';
    console.log("DOM fully loaded and parsed.");

    function manageSingleInstance() {
        const storedInstanceId = localStorage.getItem(INSTANCE_KEY);
        const currentInstanceId = Date.now().toString();
        console.log('Stored instance ID: ', storedInstanceId);
        console.log('Current instance ID: ', currentInstanceId);

        // If window.name is not set, set it to the current instance ID
        if (!window.name) {
            window.name = currentInstanceId;
            console.log('window.name was not set. Setting window.name to currentInstanceId: ', window.name);
        } else {
            console.log('window.name is already set to: ', window.name);
        }

        // If stored instance ID exists, compare with window.name
        if (storedInstanceId) {
            console.log('Stored instance found.');

            if (window.name === storedInstanceId) {
                console.log('Existing instance detected. window.name matches stored instance ID.');
                return; // Existing instance detected, do nothing
            } else {
                console.log('Instance mismatch. Showing modal for the new instance.');
                showModal();
                return;
            }
        }

        // No stored instance found, create a new one
        localStorage.setItem(INSTANCE_KEY, currentInstanceId);
        window.name = currentInstanceId; // Ensure window.name is updated to current instance
        console.log('No existing instance found. New instance created and stored.');

        // Clean up when the window is unloaded
        window.addEventListener('beforeunload', () => {
            const currentId = localStorage.getItem(INSTANCE_KEY);
            if (currentId === currentInstanceId) {
                localStorage.removeItem(INSTANCE_KEY);
                console.log('Instance removed on close.');
            }
        });

        console.log('New instance created and stored.');
    }

    function showModal() {
        const modal = document.getElementById('modal');
        if (modal) {
            console.log('Modal found, displaying it.');
            modal.style.display = 'block';

            setTimeout(function () {
                modal.style.opacity = 1;
                modal.classList.add('modal-open');
            }, 10);

            console.log('Modal displayed with opacity transition.');

            // Add event listeners for modal buttons
            document.getElementById('redirectButton').addEventListener('click', redirectToExistingInstance);
            document.getElementById('openNewButton').addEventListener('click', openNewInstance);
            document.getElementById('closeButton').addEventListener('click', handleCancel);
        } else {
            console.error('Modal not found in DOM');
        }
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        if (modal) {
            console.log('Closing modal.');
            modal.style.opacity = 0;
            modal.classList.remove('modal-open');

            setTimeout(function () {
                modal.style.display = 'none';
            }, 500);
        }
    }

    function redirectToExistingInstance() {
        console.log('Redirecting to existing instance...');
        const redirectUrl = `http://localhost/WebPages/MyWebSites/redirect.html?url=${encodeURIComponent(window.location.href)}`;
        console.log('Redirect URL: ', redirectUrl);
        window.location.href = redirectUrl;
    }

    function openNewInstance() {
        closeModal();
        localStorage.removeItem(INSTANCE_KEY); // Remove stored instance ID
        window.location.reload(); // Reload the page to create a new instance
    }

    function handleCancel() {
        closeModal();
        console.log("Modal closed without action.");
    }

    manageSingleInstance();
});
