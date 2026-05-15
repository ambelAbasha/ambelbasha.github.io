<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpages Projects</title>
    <link rel="stylesheet" href="./Styles/styles.css">
    <link rel="stylesheet" href="./Styles/StylesForIndividualHexagons/individualHexagonsLayout.css">
    <style>
    </style>
</head>
<body>
    <div class="hex-container" id="hexContainer">
        <!-- Hexagons will be dynamically added here -->
    </div>
    <!-- Banner -->
    <div class="banner">
        <h2>Webpages Projects</h2>
    </div>
    <div class="outer-container">
        <div id="container" class="container hexagonal-frame">
            <!-- Content fetched dynamically from database -->
            <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "projects_db";

            // Function to handle database connection
            function connectToDatabase($servername, $username, $password, $dbname) {
                try {
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    // Check connection
                    if ($conn->connect_error) {
                        throw new Exception("Connection failed: " . $conn->connect_error);
                    }
                    return $conn;
                } catch (Exception $e) {
                    // Log the actual error message to a file or monitoring system
                    error_log($e->getMessage());
                    // Display a user-friendly message
                    echo "<div class='error-message'><p>Oops! Something went wrong.</p><p>We're sorry. Please try again later.</p></div>";
                    // Terminate the script
                    exit();
                }
            }

            // Connect to the database
            $conn = connectToDatabase($servername, $username, $password, $dbname);

            // Set charset to UTF-8
            $conn->set_charset("utf8");

            // SQL query to fetch projects
            $sql = "SELECT title, description, url FROM projects LIMIT 7";
            $result = $conn->query($sql);

            // Check if there are any projects
            if ($result && $result->num_rows > 0) {
                $counter = 0;

                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    if ($counter == 0 || $counter == 2 || $counter == 5) {
                        echo '<div class="row">';
                    }

                    echo '<div class="hex" onclick="expandHex(this)">';
                    echo '<a href="http://localhost/' . htmlspecialchars($row["url"]) . '" target="_blank">' . htmlspecialchars($row["title"]) . ' - ' . htmlspecialchars($row["description"]) . '</a>';
                    echo '<div class="hover-dot"></div>'; // Add the hover dot here
                    echo '</div>';

                    $counter++;

                    if ($counter == 2 || $counter == 5 || $counter == 7) {
                        echo '</div>'; // Close row
                    }
                }
            } else {
                echo "No projects found.";
            }

            // Close database connection
            $conn->close();
            ?>
        </div>
        <div class="triangle"></div> <!-- Triangle element -->
        <div class="triangle-overlay"></div> <!-- Overlay element -->
    </div>
    <!-- Footer -->
    <div class="footer glossy-line">
        <ul>
            <li><a href="../index.html" onclick="navigateLink(event, this)">Home</a></li>
            <li><a href="./About/about.html" onclick="navigateLink(event, this)">About</a></li>
            <li><a href="https://gr.linkedin.com/in/ambel-basha-367156123" target="_blank" rel="noopener noreferrer" onclick="navigateLink(event, this)">Contact</a></li>
        </ul>
    </div>
    <div id="modal" style="display: none;">
    <div class="modal-content">
        <h2>New Instance Detected</h2>
        <p>A session is already active. What would you like to do?</p>
        <button id="redirectButton">Redirect to Existing Instance</button>
        <button id="openNewButton">Open New Instance (Disabled)</button>
        <button id="closeButton">Close</button>
    </div>
</div>
<!-- Modal Structure -->
<div id="modal" class="modal" style="display: none;">
    <div class="modal-content">
        <h2>Page Already Open</h2>
        <p>Another instance of this page is already open. Would you like to:</p>
        <div class="button-container">
            <button id="redirectButton" class="button">Redirect to Existing Instance</button>
            <button id="openNewButton" class="button">Open a New Instance</button>
            <button id="closeButton" class="button">Cancel</button>
        </div>
    </div>
</div>

<script>
    const INSTANCE_KEY = 'uniquePageInstanceId';

    function manageSingleInstance() {
        const storedInstanceId = localStorage.getItem(INSTANCE_KEY);
        const currentInstanceId = Date.now().toString(); // Unique ID for this instance

        console.log('Stored Instance ID:', storedInstanceId);
        console.log('Current Instance ID:', currentInstanceId);
        console.log('Window Name:', window.name);

        // If a stored instance exists
        if (storedInstanceId) {
            // Check if the current window matches the stored instance
            if (window.name === storedInstanceId) {
                console.log('This is the existing instance. No redirection needed.');
                return; // Exit to prevent new instance creation
            } else {
                console.log('New instance detected, showing modal...');
                showModal(); // Show the modal
                return; // Prevent further execution
            }
        }

        // No stored instance found, set up the new instance
        localStorage.setItem(INSTANCE_KEY, currentInstanceId);
        window.name = currentInstanceId;

        // Clean up the stored instance ID upon closing the window
        window.addEventListener('beforeunload', () => {
            const currentId = localStorage.getItem(INSTANCE_KEY);
            if (currentId === currentInstanceId) {
                localStorage.removeItem(INSTANCE_KEY);
                console.log('Instance removed from localStorage.');
            }
        });

        console.log('New instance created and stored.');
    }

    function showModal() {
        const modal = document.getElementById('modal');
        modal.style.display = 'block'; // Show the modal
        console.log('Modal displayed to the user.');

        // Event listeners for the modal buttons
        document.getElementById('redirectButton').onclick = function() {
            console.log('Redirecting to existing instance...');
            redirectToExistingInstance();
        };

        document.getElementById('openNewButton').onclick = function() {
            console.log('Opening a new instance...');
            closeModal();
            // Open a new instance by simply reloading the page
            localStorage.removeItem(INSTANCE_KEY); // Clear stored instance
            window.location.reload(); // Reloads the current instance
        };

        document.getElementById('closeButton').onclick = function() {
            closeModal(); // Hide the modal
            console.log('Modal closed by the user.');
        };
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        modal.style.display = 'none'; // Hide the modal
    }

    function redirectToExistingInstance() {
        const redirectUrl = `${window.location.origin}/WebPages/MyWebSites/redirect.html?url=${encodeURIComponent(window.location.href)}`;
        console.log('Redirecting to:', redirectUrl);

        // Validate the URL before redirection
        if (isValidUrl(redirectUrl)) {
            window.location.href = redirectUrl; // Directly redirect to the existing instance
        } else {
            console.error('Invalid redirect URL.');
        }
    }

    function isValidUrl(url) {
        const origin = window.location.origin;
        return url.startsWith(origin) || url.startsWith('/') || url.startsWith('./') || url.startsWith('../');
    }

    window.onload = manageSingleInstance;
</script>

<script src="Scripts/backgroundScript.js"></script>
<script src="Scripts/script.js"></script>
</body>
</html>
