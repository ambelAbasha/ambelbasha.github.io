<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portfolio webpage showcasing projects created by Ambel Basha.">
    <meta name="keywords" content="portfolio, projects, web development, Ambel Basha">
    <meta name="author" content="Ambel Basha">
    <title>Webpages Projects</title>
    <link rel="stylesheet" href="Styles/backgroundStyles.css">
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
            <?php
            // Database connection details
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "projects_db";

            // Attempt to connect to the database
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

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
                    echo '<a href="http://localhost/' . $row["url"] . '" target="_blank">' . $row["title"] . ' - ' . $row["description"] . '</a>';
                    echo '</div>';

                    $counter++;

                    if ($counter == 2 || $counter == 5 || $counter == 7) {
                        echo '</div>';
                    }
                }
            } else {
                echo "No projects found.";
            }

            // Close database connection
            $conn->close();
            ?>
        </div>
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
        function expandHex(element) {
            document.getElementById('container').classList.add('expanded-container');
            element.classList.add('expanded');
            setTimeout(function() {
                resetHex(element);
            }, 3000);
        }

        function resetHex(element) {
            element.classList.remove('expanded');
            document.getElementById('container').classList.remove('expanded-container');
        }

        function navigateLink(event, link) {
            event.preventDefault();
            var existingTab = window.open(link.href, '_blank');
            if (existingTab) {
                existingTab.focus();
            } else {
                window.location.href = link.href;
            }
        }
    </script>
    <script src="Scripts/backgroundScript.js"></script>
    <script src="Scripts/script.js"></script>
</body>
</html>
