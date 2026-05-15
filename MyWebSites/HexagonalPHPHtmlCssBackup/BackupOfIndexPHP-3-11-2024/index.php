<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpages Projects</title>
    <link rel="stylesheet" href="Styles/styles.css">
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

    <!-- Scripts -->
    <script>
        const INSTANCE_KEY = 'uniquePageInstanceId';
        const TIMEOUT = 30000; // 30 seconds timeout

        function manageSingleInstance() {
            const storedInstanceId = localStorage.getItem(INSTANCE_KEY);
            const currentInstanceId = Date.now().toString(); // Unique ID for this instance

            if (storedInstanceId) {
                if (window.name === '') {
                    window.name = currentInstanceId; // Assign unique ID to window.name
                }

                if (window.name !== storedInstanceId) {
                    // Show prompt and handle actions
                    const userAction = confirm('Another instance of this page is already open. Click OK to be redirected to the existing instance or Cancel to open a new instance.');
                    if (userAction) {
                        redirectToExistingInstance();
                    } else {
                        // Cancel: Do not store new instance ID and close the current tab
                        localStorage.removeItem(INSTANCE_KEY);
                        window.close();
                    }
                } else {
                    // This is the existing instance
                    console.log('This instance is the existing one.');
                }
            } else {
                // First instance, set the flag
                localStorage.setItem(INSTANCE_KEY, currentInstanceId);
                window.addEventListener('beforeunload', () => {
                    if (localStorage.getItem(INSTANCE_KEY) === currentInstanceId) {
                        localStorage.removeItem(INSTANCE_KEY);
                    }
                });

                // Set timeout to handle redirection or closing the new instance
                setTimeout(() => {
                    if (window.name === currentInstanceId) {
                        alert('No action taken. Closing this tab.');
                        window.close();
                    }
                }, TIMEOUT);
            }
        }

        function redirectToExistingInstance() {
            const existingInstanceUrl = `${window.location.origin}/redirect.html?url=${encodeURIComponent(window.location.href)}`;
            console.log('Redirecting to:', existingInstanceUrl); // Debugging line
            window.location.href = existingInstanceUrl;
        }

        window.onload = manageSingleInstance;
    </script>
    <script src="Scripts/backgroundScript.js"></script>
    <script src="Scripts/script.js"></script>
</body>
</html>
