document.addEventListener("DOMContentLoaded", function () {
    var messageDiv = document.getElementById("message");
    var inputForm = document.getElementById("inputForm");
    var inputField = document.getElementById("number");
    var paginationDiv = document.getElementById("pagination");
    var currentPage = 1;
    var itemsPerPage = 5; // Items per page for pagination
    var totalPages = 0; // Total pages to be calculated
    var totalVisiblePages = 3; // Number of visible pages in pagination

    // Function to display the generated messages in a table
    function displayMessages(page) {
        var start = (page - 1) * itemsPerPage + 1;
        var end = Math.min(page * itemsPerPage, parseInt(inputForm.number.value));
        var message = "<table>";

        for (var i = start; i <= end; i++) {
            message += "<tr><td>Welcome to PHP " + i + "</td></tr>";
        }

        message += "</table>";
        messageDiv.innerHTML = message;
    }

    // Function to generate pagination buttons
    function generatePaginationButtons() {
        var buttons = "";

        if (currentPage > 1) {
            buttons += '<button class="pagination-button" onclick="changePage(' + (currentPage - 1) + ')">&laquo; Prev</button>';
        }

        var startPage = Math.max(1, currentPage - Math.floor(totalVisiblePages / 2));
        var endPage = Math.min(startPage + totalVisiblePages - 1, totalPages);

        for (var i = startPage; i <= endPage; i++) {
            if (i === currentPage) {
                buttons += '<button class="pagination-button current-page" onclick="changePage(' + i + ')">' + i + '</button>';
            } else {
                buttons += '<button class="pagination-button" onclick="changePage(' + i + ')">' + i + '</button>';
            }
        }

        if (currentPage < totalPages) {
            buttons += '<button class="pagination-button" onclick="changePage(' + (currentPage + 1) + ')">Next &raquo;</button>';
        }

        paginationDiv.innerHTML = buttons;
    }

    // Function to handle page changes in pagination
    window.changePage = function(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            displayMessages(currentPage);
            generatePaginationButtons();
        }
    };

    // Handle form submission and number validation
    inputForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        var number = inputForm.number.value;

        // If number exceeds 100, show error and clear input field after 5 seconds
        if (number > 100) {
            messageDiv.innerHTML = "<p>Value can be less or equal to 100</p>";

            // Clear the input field after 5 seconds (5000 milliseconds)
            setTimeout(function() {
                inputField.value = ""; // Clear the input field
                messageDiv.innerHTML = ""; // Clear the message div
            }, 5000); // 5 seconds delay

        } else {
            // Valid number entered - calculate pagination and display messages
            totalPages = Math.ceil(number / itemsPerPage);
            currentPage = 1; // Reset to first page
            displayMessages(currentPage);
            generatePaginationButtons();
            messageDiv.classList.remove("hidden"); // Show the message div
        }
    });

    // Initial call to generate pagination buttons
    generatePaginationButtons();
});
