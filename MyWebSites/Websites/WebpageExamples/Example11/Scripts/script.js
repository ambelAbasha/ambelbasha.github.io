document.addEventListener("DOMContentLoaded", function () {
    var messageDiv = document.getElementById("message");
    var paginationDiv = document.getElementById("pagination");
    var inputForm = document.getElementById("inputForm");
    var currentPage = 1;
    var totalPages = 0;
    var itemsPerPage = 5; // Change this value to adjust items per page
    var totalVisiblePages = 3; // Change this value to adjust the number of visible pages

    // Function to display messages
    function displayMessages(page) {
        var start = (page - 1) * itemsPerPage + 1;
        var end = Math.min(page * itemsPerPage, parseInt(inputForm.number.value));
        var message = "<table><tr><th>Nr :</th><th>Message</th></tr>";

        for (var i = start; i <= end; i++) {
            var ordinal = getOrdinal(i);
            message += "<tr><td>" + i + "</td><td>This function is being called for the " + ordinal + " time</td></tr>";
        }

        message += "</table>";
        messageDiv.innerHTML = message;
    }

    // Function to get the ordinal suffix for a number (1st, 2nd, 3rd, etc.)
    function getOrdinal(n) {
        var suffix = ["th", "st", "nd", "rd"];
        var v = n % 100;
        return n + (suffix[(v - 20) % 10] || suffix[v] || suffix[0]);
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

    // Function to change page
    window.changePage = function(page) {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            displayMessages(currentPage);
            generatePaginationButtons();
        }
    };

    // Function to handle form submission
    inputForm.addEventListener("submit", function (event) {
        event.preventDefault();
        totalPages = Math.ceil(inputForm.number.value / itemsPerPage); // Recalculate total pages
        currentPage = 1; // Reset current page to 1 after submitting the form
        displayMessages(currentPage);
        generatePaginationButtons();
        messageDiv.classList.remove("hidden"); // Show the messageDiv
    });

    // Initialization
    generatePaginationButtons(); // Generate initial pagination buttons
});
