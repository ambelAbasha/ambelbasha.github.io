document.addEventListener('DOMContentLoaded', function () {
    const INSTANCE_KEY = 'uniquePageInstanceId';
    const CHANNEL_NAME = 'instanceChannel';
    const channel = new BroadcastChannel(CHANNEL_NAME);

    const currentInstanceId = window.name || Date.now().toString(); // Keep the same instance ID if already set

    function log(...messages) {
        console.log(...messages);
    }

    // Track opened windows
    let existingTabReference = null;

    function manageSingleInstance() {
        let storedInstanceId = localStorage.getItem(INSTANCE_KEY);

        log("[Instance Management] Current:", currentInstanceId, "Stored:", storedInstanceId);

        if (!storedInstanceId) {
            // No conflict: This is the first instance
            localStorage.setItem(INSTANCE_KEY, currentInstanceId);
            window.name = currentInstanceId;
            log("[Instance Management] Setting this tab as active instance.");
        } else if (storedInstanceId !== currentInstanceId) {
            // Conflict detected: Notify and show modal
            log("[Instance Management] Conflict detected.");
            showModal();
            channel.postMessage({ action: 'focus', storedInstanceId });
        }

        // Respond to updates from other tabs
        channel.onmessage = (event) => {
            if (event.data.action === 'focus' && storedInstanceId === currentInstanceId) {
                log("[BroadcastChannel] Received focus request.");
                window.focus();
            }
        };

        // Handle tab close
        window.addEventListener('beforeunload', () => {
            if (localStorage.getItem(INSTANCE_KEY) === currentInstanceId) {
                localStorage.removeItem(INSTANCE_KEY);
            }
        });
    }

    function showModal() {
        const modal = document.getElementById('modal');
        if (modal) {
            modal.style.display = 'flex';

            document.getElementById('redirectButton').onclick = focusExistingTab;
            document.getElementById('openNewButton').onclick = openNewInstance;
            document.getElementById('closeButton').onclick = closeModal;
        }
    }

    function focusExistingTab() {
        log("[Instance Management] Requesting focus on the existing instance.");
        channel.postMessage({ action: 'focus', storedInstanceId: localStorage.getItem(INSTANCE_KEY) });
        closeModal();
    }

    function openNewInstance() {
        log("[Instance Management] Opening a new instance.");
        localStorage.setItem(INSTANCE_KEY, currentInstanceId);
        closeModal();
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        if (modal) modal.style.display = 'none';
    }

    manageSingleInstance();
});
