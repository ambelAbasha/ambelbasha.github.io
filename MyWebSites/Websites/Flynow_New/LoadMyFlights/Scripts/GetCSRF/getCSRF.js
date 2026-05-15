document.addEventListener('DOMContentLoaded', function() {
    // Get the CSRF token from a hidden input field
    const csrfToken = document.getElementById('csrf-token').value;

    // Function to send a request with the CSRF token
    function sendRequest(data) {
        fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                csrf_token: csrfToken,
                ...data // Spread the additional data into the body
            })
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response from the server
            if (data.success) {
                location.reload(); // Reload on success
            } else {
                alert(data.message); // Show error message
            }
        })
        .catch(error => console.error('Error:', error)); // Handle fetch errors
    }

    // Export the sendRequest function if needed
    window.sendRequest = sendRequest;

    // If you need to handle logout, bind the confirmLogout function here
    const confirmLogout = document.getElementById('confirm-logout');
    if (confirmLogout) {
        confirmLogout.onclick = function() {
            const logoutUrl = '../logout.php?csrf_token=' + csrfToken; // Construct logout URL
            console.log('Logging out, redirecting to:', logoutUrl);
            window.location.href = logoutUrl; // Redirect to the logout URL
        };
    }
});
