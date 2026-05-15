let isDeletingFlights = false; // Flag to indicate if flights are being deleted

document.getElementById('remove-selected').addEventListener('click', function() {
    document.getElementById('confirmation-modal').style.display = 'block';
});

document.getElementById('confirm-remove').addEventListener('click', function() {
    const selectedFlightIds = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
    if (selectedFlightIds.length > 0) {
        const csrfToken = document.getElementById('csrf-token').value;
        isDeletingFlights = true; // Set the flag to true when deletion starts

        fetch('path/to/this/script.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'remove_flights',
                booking_ids: selectedFlightIds,
                csrf_token: csrfToken,
            }),
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Show success/error message
            if (data.success) {
                location.reload(); // Refresh the page on success
            }
            isDeletingFlights = false; // Reset the flag after the operation completes
        })
        .catch(error => {
            console.error('Error:', error);
            isDeletingFlights = false; // Reset the flag on error
        });
    } else {
        alert('Please select at least one flight to remove.');
    }
    document.getElementById('confirmation-modal').style.display = 'none';
});

document.getElementById('cancel-remove').addEventListener('click', function() {
    document.getElementById('confirmation-modal').style.display = 'none';
});

// Logout modal logic
const logoutModal = document.getElementById('logoutModal');
document.getElementById('logout-button').onclick = function() {
    if (isDeletingFlights) {
        alert("You can't logout while deleting flights.");
    } else {
        logoutModal.style.display = 'block';
    }
};

document.getElementById('closeModal').onclick = function() {
    logoutModal.style.display = 'none';
};

document.getElementById('cancel-logout').onclick = function() {
    logoutModal.style.display = 'none';
};

document.getElementById('confirm-logout').onclick = function() {
    window.location.href = '../logout.php'; // Redirect to logout
};

// Close modal on outside click
window.onclick = function(event) {
    if (event.target === logoutModal) {
        logoutModal.style.display = 'none';
    }
};
