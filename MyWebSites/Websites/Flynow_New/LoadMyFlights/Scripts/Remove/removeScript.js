document.addEventListener('DOMContentLoaded', function () {
    // Retrieve the CSRF token from the meta tag.
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) {
        console.error('CSRF meta tag not found.');
        return;
    }
    const csrfToken = csrfMeta.getAttribute('content');

    // Select the Remove Flights button and set up the warning sound.
    const removeFlightsBtn = document.getElementById('remove-selected');
    const warningSound = new Audio('WarningSound/systemNotification.mp3'); // Update with the correct path

    // Track the state of the removal modal.
    let isRemoveFlightsActive = false;

    // Function to show the flight removal confirmation modal.
    function handleRemoveFlights() {
        const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
        if (selected.length > 0) {
            // Show the confirmation modal.
            document.getElementById('confirmation-modal').style.display = 'block';
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = ''; // Clear previous content

            selected.forEach(function (checkboxValue) {
                // Find the closest table row for each selected checkbox.
                const checkbox = document.querySelector(`.row-select[value="${checkboxValue}"]`);
                const row = checkbox.closest('tr');
                const flightDetails = `
                    <p><strong>Airline:</strong> ${row.cells[1].textContent}</p>
                    <p><strong>From:</strong> ${row.cells[2].textContent}</p>
                    <p><strong>To:</strong> ${row.cells[3].textContent}</p>
                    <p><strong>Class:</strong> ${row.cells[4].textContent}</p>
                    <p><strong>Person:</strong> ${row.cells[5].textContent}</p>
                    <p><strong>Departure:</strong> ${row.cells[6].textContent}</p>
                    <p><strong>Return Date:</strong> ${row.cells[7].textContent}</p>
                    <hr>
                `;
                modalContent.innerHTML += flightDetails;
            });
            isRemoveFlightsActive = true;
        } else {
            alert('Please select at least one flight to remove.');
        }
    }

    // When the "Remove Flights" button is clicked.
    removeFlightsBtn.addEventListener('click', function () {
        // (Optional) If any other modal is active, warn the user.
        if (isRemoveFlightsActive) {
            warningSound.play();
            alert("Please complete the current removal process before starting another.");
        } else {
            handleRemoveFlights();
        }
    });

    // Event listener for the "Confirm Remove" button in the flight removal modal.
    document.getElementById('confirm-remove').addEventListener('click', function (e) {
        e.preventDefault();
        // Hide the confirmation modal immediately.
        document.getElementById('confirmation-modal').style.display = 'none';

        const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
        if (selected.length === 0) {
            alert('No flights selected for removal.');
            return;
        }

        // Send the AJAX request with the CSRF token in the header.
        fetch('removeFlights.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ booking_ids: selected })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Flights removed successfully.');
                setTimeout(() => location.reload(), 3000); // Reload after success
            } else {
                alert(data.error || 'An error occurred while removing flights.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });

        isRemoveFlightsActive = false; // Reset state
    });

    // Event listener for the "Cancel Remove" button in the flight removal modal.
    document.getElementById('cancel-remove').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('confirmation-modal').style.display = 'none';
        isRemoveFlightsActive = false; // Reset state
    });
});
