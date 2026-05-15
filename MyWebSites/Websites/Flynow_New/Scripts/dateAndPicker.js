document.addEventListener("DOMContentLoaded", function() {
    const currentDateElement = document.getElementById("currentDate");

    // Function to get the current date and time
    function getCurrentDateTime() {
        const now = new Date();
        return now.toLocaleString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false // Use 24-hour format
        });
    }

    // Update the date-time on the page every second
    function updateDateTime() {
        if (currentDateElement) {
            currentDateElement.textContent = getCurrentDateTime();
        }
    }

    // Refresh every second
    setInterval(updateDateTime, 1000); 
    updateDateTime(); // Initial call to set the date immediately

    // Flatpickr options
    const flatpickrOptions = {
        dateFormat: "d-m-Y",
        minDate: "today" // Disable past dates
    };

    // Initialize Flatpickr for departure and return date fields by their IDs
    flatpickr("#departureDate", flatpickrOptions); // Departure date
    flatpickr("#returnDate", flatpickrOptions);    // Return date

    // Additional Flatpickr initialization for any other calendar input
    flatpickr("#calendarInput", {
        ...flatpickrOptions,
        animate: true, // Enable animation
        static: true // Prevents the picker from floating
    });
});
