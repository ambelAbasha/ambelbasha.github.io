<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpages Projects</title>
    <link rel="stylesheet" href="./Styles/styles.css">
    <!---Java script Loading Externally --->
    <script src="./Scripts/singleInstanceManager.js"></script>
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

</div>
<!----Script link / tag appears after the hexContainer  -->
    <script src="./Scripts/footerVisibility.js"></script>
    <script src="./Scripts/backgroundScript.js"></script>
</body>
</html>
