document.addEventListener('DOMContentLoaded', function () {
    const INSTANCE_KEY = 'uniquePageInstanceId';
    const DEBUG_MODE = true; // Toggle this to false in production

    // Logging function based on DEBUG_MODE
    function log(...messages) {
        if (DEBUG_MODE) console.log(...messages);
    }

    log("[DOM Loaded] DOM fully loaded and parsed.");

    // Helper to normalize path strings (force lowercase for consistency)
    function normalizePath(path) {
        if (!path) return '/';
        let normalized = path.replace(/\/+/g, '/').replace(/\/$/, ''); // Normalize multiple slashes and trailing slashes
        if (!normalized.startsWith('/')) normalized = '/' + normalized; // Ensure it starts with a single slash
        log("[Instance Management] Normalized path (lowercase):", normalized.toLowerCase());
        return normalized.toLowerCase(); // Force paths to lowercase for comparison (to handle case sensitivity)
    }

    // URL validation helper
    function isValidUrl(url) {
        try {
            const targetUrl = new URL(url, window.location.origin); // Validate URL relative to the origin
            const isValid = targetUrl.origin === window.location.origin;
            log("[Instance Management] URL validation result:", isValid, url);
            return isValid;
        } catch (error) {
            log("[Instance Management] Invalid URL error:", error);
            return false;
        }
    }

    // Tab ID management
    var tabID = sessionStorage.tabID && sessionStorage.closedLastTab !== '2' ? 
        sessionStorage.tabID : 
        sessionStorage.tabID = Math.random();
    sessionStorage.closedLastTab = '2';

    // Clean up sessionStorage on unload event
    window.addEventListener('unload', function() {
        sessionStorage.closedLastTab = '1';
    });
    window.addEventListener('beforeunload', function() {
        sessionStorage.closedLastTab = '1';
    });

    // Single instance management logic
    function manageSingleInstance() {
        const currentInstanceId = Date.now().toString(); // Unique ID for this instance
        let storedInstanceId = localStorage.getItem(INSTANCE_KEY);

        log("[Instance Management] Current Instance ID:", currentInstanceId);
        log("[Instance Management] Stored Instance ID:", storedInstanceId);

        // Ensure window.name and localStorage alignment
        if (!window.name) {
            window.name = currentInstanceId; // Set window name to ensure instance uniqueness
            localStorage.setItem(INSTANCE_KEY, currentInstanceId); // Store instance ID in localStorage
            log("[Instance Management] New instance initialized:", currentInstanceId);
        } else {
            log("[Instance Management] Existing window.name detected:", window.name);
        }

        // Listen for changes in localStorage across other tabs (storage event)
        window.addEventListener('storage', (event) => {
            if (event.key === INSTANCE_KEY && event.newValue !== storedInstanceId) {
                log("[Storage Event] Another tab or window has updated localStorage.");
                window.location.reload(); // Reload the page to handle the new instance ID
            }
        });

        // Force modal to show on instance mismatch between window.name and storedInstanceId
        if (storedInstanceId && storedInstanceId !== window.name) {
            log("[Instance Management] Instance mismatch detected.");
            showModal(); // Show the modal if there’s a mismatch
        } else {
            log("[Instance Management] Instance match detected, no modal required.");
        }

        // Clear localStorage entry on page unload if matched
        window.addEventListener('beforeunload', () => {
            if (localStorage.getItem(INSTANCE_KEY) === window.name) {
                localStorage.removeItem(INSTANCE_KEY); // Remove instance ID on page unload
                log("[Instance Management] Instance removed on unload.");
            }
        });
    }

    // Modal management functions
    function showModal() {
        const modal = document.getElementById('modal');
        if (!modal) {
            log("[Modal Handling] Modal element not found.");
            return;
        }

        modal.style.display = 'flex'; // Show modal if it exists
        log("[Modal Handling] Modal displayed.");

        // Attach event listeners to buttons
        const attachButtonHandler = (buttonId, handler, logMessage) => {
            const button = document.getElementById(buttonId);
            if (button) {
                button.removeEventListener('click', handler); // Remove any existing listeners
                button.addEventListener('click', handler); // Attach new listener
                log(logMessage);
            } else {
                log(`[Modal Handling] Button not found: ${buttonId}`);
            }
        };

        // Attach handlers to modal buttons
        attachButtonHandler('redirectButton', redirectToExistingInstance, "[Modal Handling] Redirect button listener attached.");
        attachButtonHandler('openNewButton', openNewInstance, "[Modal Handling] Open New Instance button listener attached.");
        attachButtonHandler('closeButton', closeModal, "[Modal Handling] Close button listener attached.");
    }

    function redirectToExistingInstance() {
        const storedInstanceId = localStorage.getItem(INSTANCE_KEY);
        if (!storedInstanceId) {
            log("[Instance Management] No stored instance ID to redirect.");
            return;
        }

        // Ensure proper path handling
        const currentPath = normalizePath(window.location.pathname);
        let redirectUrl = `${window.location.origin}/Webpages/MyWebSites/redirect.html?url=${encodeURIComponent(window.location.href)}&instance_id=${storedInstanceId}`;

        // Fix for double-slash issues
        redirectUrl = redirectUrl.replace(/([^:]\/)\/+/g, "$1"); // Remove duplicate slashes

        log("[Redirect URL Debug] URL:", redirectUrl);  // Log URL before redirection
        log("[Redirect URL Debug] Instance ID:", storedInstanceId);  // Log instance ID

        if (isValidUrl(redirectUrl)) {
            log("[Instance Management] Redirecting to existing instance:", redirectUrl);

            // If the current tab is the correct instance, **do not open a new tab**
            if (window.name === storedInstanceId) {
                log("[Redirect] Already in the existing instance, not opening a new tab.");
                // Use window.location.replace() to update the current tab to reflect the correct instance.
                window.location.replace(redirectUrl); // Use replace instead of href to prevent history in the stack
            } else {
                log("[Redirect] Redirecting to existing instance in the same tab.");
                // This will navigate in the same tab (no new tab opened).
                setTimeout(() => {
                    window.location.href = redirectUrl; // Use href for normal navigation
                }, 200); // Wait 200ms to ensure proper localStorage handling before redirection
            }
        } else {
            log("[Instance Management] Invalid redirect URL:", redirectUrl);
        }
    }

    // Close the modal
    function closeModal() {
        const modal = document.getElementById('modal');
        if (modal) {
            modal.style.display = 'none'; // Close the modal
            log("[Modal Handling] Modal closed.");
        } else {
            log("[Modal Handling] Modal element not found for closing.");
        }
    }

    // Open a new instance (clear current instance info and reload)
    function openNewInstance() {
        log("[Instance Management] Opening a new instance.");
        closeModal(); // Close the modal first
        localStorage.removeItem(INSTANCE_KEY); // Clear stored instance ID
        window.name = ''; // Reset the window name
        log("[Instance Management] Cleared instance and reloading the page.");
        window.location.reload(); // Reload the page to reset the instance
    }

    manageSingleInstance(); // Initialize instance management
});
