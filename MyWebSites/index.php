<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpages Projects</title>
 <!-- Stylesheets -->
    <!-- <link rel="stylesheet" href="Styles/Animated/animated.css"> Uncomment if needed -->
    <link rel="stylesheet" href="Styles/General/styles.css">
    <link rel="stylesheet" href="Styles/HeaderBurger/headerBurger.css">
    <link rel="stylesheet" href="Styles/StylesForIndividualHexagons/individualHexagonsLayout.css">
    <link rel="stylesheet" href="Styles/FooterStyles/footerStyles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Inline Styles (if needed) -->
    <style>
    </style>

    <!-- Scripts -->
    <script src="Scripts/BackgroundTurqoiseVibration/turqoiseVibration.js" defer></script>
    <script src="Scripts/SingleInstanceManager/singleInstanceManager.js" defer></script>
    <script src="Scripts/ToggleBurgerMenu/toggleBurgerMenu.js" defer></script>
    <!-- <script src="Scripts/HexagonAnimation/hexagonAnimation.js" defer></script> Uncomment if needed -->
    <script src="Scripts/TotalUniqueVisits/totalUniqueVisits.js" defer></script>
    <script src="Scripts/DigitalClock/digitalClock.js" defer></script>
    <script src="Scripts/ClipTextCutOut/clipTextWelcomeTitle.js" defer></script>
    <!-- <script src="Scripts/FooterVisibilityReachingEnd/footerVisibilityReachingEnd.js" defer></script> Uncomment if needed -->
    <script src="Scripts/FooterVisibility/footerVisibility.js" defer></script>
</head>
<body>
<!-- Header -->
<header id="main-header">
  <nav id="navbar" role="navigation" aria-label="Main navigation">
    <div class="navbar-container">
      <h1 class="welcome-title">
        <span class="welcome-title">Web Projects Showcase</span>
      </h1>

      <div class="burger-wrapper">
        <button id="webpages-burger-menu" class="burger-menu" aria-expanded="false">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

      <div id="webpages-menu" class="webpages-menu" aria-hidden="true">
        <a href="../MyWebSites/CyberSecurity/index.html" target="_blank">Cyber Security</a>
        <a href="../MyWebSites/Scripting/index.html" target="_blank">Linux Scripting</a>
        <a href="../MyWebSites/Metadata/index.html" target="_blank">Metadata</a>
        <a href="../portfolio.html">Portfolio</a>
        <a href="../index.html">Home</a>
      </div>

      <!-- Visitor counts section -->
      <div id="visitor-count-section">
        <p id="total-visits">
          <span class="label">Total visits:</span>
          <span class="number">
            <span class="prefix">+</span>
            <span class="digits"></span>
          </span>
        </p>
        <p id="unique-visits">
          <span class="label">Unique visits:</span>
          <span class="number">
            <span class="prefix">+</span>
            <span class="digits"></span>
          </span>
        </p>
      </div>

      <!-- Clock -->
      <div id="clock" class="clock"></div>

    </div>
  </nav>
</header>

<!-- Secondary Navigation -->
<nav aria-label="Section navigation">
  <button class="burger">
    <div></div>
    <div></div>
    <div></div>
  </button>
  <ul>
    <li><a href="#web">Web</a></li>
    <li><a href="#cyber">Cybersecurity</a></li>
    <li><a href="#linux">Linux</a></li>
    <li><a href="#metadata">Metadata</a></li>
  </ul>
</nav>

    <div class="hex-container" id="hexContainer">
        <!-- Hexagons will be dynamically added here -->
    </div>
    <!-- Dot Elements 
    <div class="dot" id="dot-top-left"></div>
    <div class="dot" id="dot-top-right"></div>
    <div class="dot" id="dot-bottom-left"></div>
    <div class="dot" id="dot-bottom-right"></div>

    Lines connecting the dots 
    <div class="line" id="line-top"></div>
    <div class="line" id="line-bottom"></div> --->

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
// Determine URL based on project title.
if ($row['title'] === 'CCTV Home Surveillance') {
    // For CCTV Home Surveillance, use the updated URL format.
    $url = 'http://localhost:8080' . htmlspecialchars($row["url"]);
} elseif ($row['title'] === 'Vision Scope') {
    // For Vision Scope, use the specific URL format with port 8080.
    $url = 'http://localhost:8080' . htmlspecialchars($row["url"]);
} else {
    // Default: Prepend http://localhost to the stored URL.
    $url = 'http://localhost' . htmlspecialchars($row["url"]);
}


        echo '<div class="hex" onclick="expandHex(this)">';
        echo '<a href="' . $url . '" target="_blank">' 
             . htmlspecialchars($row["title"]) . ' - ' 
             . htmlspecialchars($row["description"]) . '</a>';
        echo '<div class="hover-dot"></div>';
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
<footer>
  <div class="footer-container">
      <!-- About Me Section -->
      <div class="footer-about">
        <a href="About/index.html">
          <i class="fas fa-user"></i> About
        </a>
      </div>
      <!-- About this Page -->
      <div class="footer-social">
        <a href="../MyWebSites/ThisPage/thisPage.html" target="_blank">
          <i class="fas fa-info-circle"></i> This Page
        </a>
      </div>
<!-- Footer Links Section -->
<div class="footer-links">
  <a href="../../WebPages/portfolio.html">
    <i class="fas fa-briefcase"></i> Portfolio
  </a>
</div>

            <!-- This Page Section -->
      <div class="footer-this-page">
        <a href="../../WebPages/index.html">
          <i class="fas fa-home"></i> Home
        </a>
</div>
<!-- Contact Section -->
<section class="contact-section">
  <a href="About/about.html#contact" class="contact-link">
    <i class="fas fa-phone-alt"></i> Contact
  </a>
</section>

    </div>
  </div>
  <!-- Copyright Section -->
  <div class="footer-copyright">
    <p>© 2025 Ambel Basha — Full Stack Developer & Cybersecurity Enthusiast</p>
  </div>
</footer>

    <!-- Modal Structure (Fixed to this version) -->
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

    <script src="Scripts/BackgroundHexBuilder/backgroundHexBuilder.js"></script>
    <script src="Scripts/FooterVisibility/footerVisibility.js"></script>
</body>
</html>
