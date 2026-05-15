// Get modal elements
const logoutModal = document.getElementById('logoutModal');
const closeModal = document.getElementById('closeModal');
const confirmRemove = document.getElementById('confirm-remove');
const cancelRemove = document.getElementById('cancel-remove');
const logoutButton = document.getElementById('logoutButton');

// Function to open the modal
function openModal() {
    console.log('Opening Modal');
    logoutModal.style.display = 'flex'; // Show the modal
}

// Function to close the modal
function closeModalFunction() {
    console.log('Closing Modal');
    logoutModal.style.display = 'none'; // Hide the modal
}

// Open modal when logout button is clicked
logoutButton.addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default anchor behavior
    openModal(); // Show the modal
});

// Close the modal if cancel or close button is clicked
closeModal.onclick = closeModalFunction;
cancelRemove.onclick = closeModalFunction;

// Perform logout on confirmation
confirmRemove.onclick = function() {
    console.log('User confirmed logout');
    window.location.href = '?action=logout'; // Adjust logout URL as needed
};

// Prevent interaction with the background (modal surface click)
// Do nothing if user clicks on the semi-transparent background
logoutModal.addEventListener('click', function(event) {
    event.stopPropagation(); // Prevent the click event from propagating to the modal background
});

// Stop click propagation for modal content (keep the modal open when clicking inside)
const modalContent = document.querySelector('.modal-content');
modalContent.addEventListener('click', function(event) {
    event.stopPropagation(); // Prevent propagation of click to modal background
});

// Close modal if clicked outside modal content (on modal surface)
// Only allow closing the modal via close button or cancel button
window.onclick = function(event) {
    if (event.target === logoutModal) {
        // Prevent closing the modal when clicking on the background (semitransparent area)
        return; 
    }
};
