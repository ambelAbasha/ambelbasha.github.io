<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Character Encoding for proper text rendering -->
    <meta charset="UTF-8">
    
    <!-- Viewport settings for responsive design, ensuring the page is optimized for mobile and other screen sizes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Description of the page for SEO and to provide a summary of the content -->
    <meta name="description" content="Displays the current date and time for Athens, Greece, updated every second.">
    
    <!-- Keywords for SEO optimization; helps search engines categorize the page content -->
    <meta name="keywords" content="date, time, Athens, Greece, real-time, JavaScript, responsive">
    
    <!-- Author meta tag, provides information about the creator of the page -->
    <meta name="author" content="Ambel Basha">
    
    <!-- Link to external stylesheet for styling the page -->
    <link rel="stylesheet" href="Styles/styles.css">
    
    <!-- Favicon for browser tab (Optional) -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    
    <!-- Title of the page, displayed in the browser's tab and search engine results -->
    <title>Date and Time Display</title>
</head>

<body>
    <!-- Main outer container to hold the page content and provide central alignment -->
    <div class="outer-container">
        
        <!-- Inner container specifically for the time display -->
        <div class="container" id="container">
            
            <!-- Time display section with the label and the dynamic content placeholder -->
            <div class="time-container">
                <p>
                    <!-- Label describing what the displayed time represents -->
                    <span class="label">
                        <p>Date and Time of Athens, Greece</p><p>(3 hours ahead of Greenwich Mean Time):</p>
                    </span><br />
                    
                    <!-- Placeholder where the current date and time will be dynamically inserted -->
                    <span id="datetime" class="time"></span> 
                </p>
            </div>
        </div>

        <!-- Return button that takes the user back to the main page -->
        <div class="return-button">
            <button onclick="window.location.href='../index.html'">Back</button>
        </div>
    </div>

    <!-- JavaScript to handle date and time updating every second -->
    <script>
        // Function to update the date and time every second
        function updateTime() {
            var now = new Date(); // Get current date and time
            var datetimeElement = document.getElementById("datetime"); // Get the element to display the time
            datetimeElement.textContent = formatDate(now); // Format the date and update the text content of the element
        }

        // Function to format the date into a readable string (DD-MM-YYYY, HH:MM:SS)
        function formatDate(date) {
            // Get day, month, year, hours, minutes, and seconds from the Date object
            var day = String(date.getDate()).padStart(2, '0'); // Ensure day is always two digits
            var month = String(date.getMonth() + 1).padStart(2, '0'); // Get month (1-based), pad if needed
            var year = date.getFullYear(); // Get the full year (e.g., 2024)
            var hours = String(date.getHours()).padStart(2, '0'); // Get hours in 24-hour format, pad if needed
            var minutes = String(date.getMinutes()).padStart(2, '0'); // Get minutes, pad if needed
            var seconds = String(date.getSeconds()).padStart(2, '0'); // Get seconds, pad if needed

            // Return the formatted date and time string (e.g., "02-12-2024, 13:05:45")
            return `${day}-${month}-${year}, ${hours}:${minutes}:${seconds}`;
        }

        // Initial call to updateTime to immediately set the time when the page loads
        updateTime();

        // Set an interval to update the time every 1000ms (1 second) to ensure it stays current
        setInterval(updateTime, 1000);
    </script>
</body>

</html>
