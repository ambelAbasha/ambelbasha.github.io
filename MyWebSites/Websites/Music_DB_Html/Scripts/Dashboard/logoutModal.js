document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("logoutModal");
    const logoutButton = document.getElementById("logoutButton");
    const cancelLogout = document.getElementById("cancelLogout");

    // Show modal when the logout button is clicked
    logoutButton.onclick = function(event) {
        event.preventDefault();  // Prevent the default behavior of the link
        modal.style.display = "block"; // Show the modal
    };

    // Hide modal when Cancel button is clicked
    cancelLogout.onclick = function() {
        modal.style.display = "none"; // Hide the modal
    };

    // Hide modal if the user clicks outside the modal content
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none"; // Hide modal
        }
    };
});
