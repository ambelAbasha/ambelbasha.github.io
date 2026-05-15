document.addEventListener('DOMContentLoaded', function() {
    const logoutButton = document.getElementById('logout-button');
    const logoutModal = document.getElementById('logoutModal');
    const closeModal = document.getElementById('closeModal');
    const confirmLogout = document.getElementById('confirm-logout');
    const cancelLogout = document.getElementById('cancel-logout');

    // When the logout link is clicked, prevent its default action and show the modal.
    logoutButton.onclick = function(e) {
        e.preventDefault();
        logoutModal.style.display = 'block';
    };

    // Close the modal when the user clicks the close icon.
    closeModal.onclick = function(e) {
        e.preventDefault();
        logoutModal.style.display = 'none';
    };

    // When the user confirms logout, navigate to the logout page.
    confirmLogout.onclick = function(e) {
        e.preventDefault();
        // Use the href attribute from the logout link.
        window.location.href = logoutButton.getAttribute('href');
    };

    // If the user cancels, simply hide the modal.
    cancelLogout.onclick = function(e) {
        e.preventDefault();
        logoutModal.style.display = 'none';
    };
});
