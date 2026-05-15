<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webpages Projects</title>
    <link rel="stylesheet" href="styles.css">
    <!--<link rel="stylesheet" href="./Styles/General/styles.css">-->
    <!--<link rel="stylesheet" href="./Styles/HeaderBurger/headerBurger.css">-->
    <!--<link rel="stylesheet" href="./Styles/StylesForIndividualHexagons/individualHexagonsLayout.css">-->
    <style>
    </style>
    <script src="./scripts.js" defer></script>
     <script src="./Scripts/BackgroundTurqoiseVibration/turqoiseVibration.js" defer></script>
    <script src="./Scripts/SingleInstanceManager/singleInstanceManager.js" defer></script>
    <script src="./Scripts/ToggleBurgerMenu/toggleBurgerMenu.js" defer></script>
    <script src="./Scripts/TotalUniqueVisits/totalUniqueVisits.js" defer></script>
    <script src="./Scripts/DigitalClock/digitalClock.js" defer></script>
    <script src="./Scripts/ClipTextCutOut/clipTextWelcomeTitle.js" defer></script>
</head>
<body>
<!-- Header -->
<header id="main-header">
  <nav
    id="navbar"
    role="navigation"
    aria-label="Main navigation"
  >
    <div class="navbar-container">

      <h1 class="welcome-title">
        Welcome to <span class="highlight">Web Projects</span>
      </h1>

      <div class="burger-wrapper">
        <button
          id="webpages-burger-menu"
          class="burger-menu"
          aria-expanded="false"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

<div id="webpages-menu" class="webpages-menu" aria-hidden="true">
  
  <!-- ─────────── Projects Container ─────────── -->
  <div class="projects-container">
    <!-- Level 1 trigger -->
    <a href="#" class="projects-link">Projects ▾</a>
    
    <!-- Level 2 dropdown: hidden by default -->
    <div class="projects-dropdown">
      
      <!-- Level 2 items -->
      <div class="dropdown-item">
        <a href="http://localhost:8080/CCTVRaspberryPi28062024-1.0-SNAPSHOT/" target="_blank">
          CCTV Raspberry Pi
        </a>
      </div>
      <div class="dropdown-item">
        <a href="http://localhost:8080/VisionScope-1.0-SNAPSHOT/" target="_blank">
          Vision Scope
        </a>
      </div>
      <div class="dropdown-item">
        <a href="http://localhost/WebPages/MyWebSites/Websites/Music_DB_Html/index.html" target="_blank">Music Database ▸</a>
      </div>

      <!-- more Level 2 items… -->
      <div class="dropdown-item">
        <a href="http://localhost/WebPages/MyWebSites/Websites/Flynow_New/index.php" target="_blank">FlyNow</a>
      </div>
      <div class="dropdown-item">…</div>
    </div>
  </div>
  
  <!-- ─────────── Other top‐level links ─────────── -->
  <a href="MyWebSites/CyberSecurity/index.html" target="_blank">Cyber Security</a>
  <a href="MyWebSites/Scripting/index.html" target="_blank">Linux Scripting</a>
  <a href="MyWebSites/Metadata/index.html" target="_blank">Metadata</a>
  <a href="../porfolio.html">Portfolio</a>
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
        if ($row['title'] === 'CCTV Project') {
            // For the CCTV Project, prepend :8080 to the stored URL.
            $url = 'http://localhost:8080' . htmlspecialchars($row["url"]);
        } elseif ($row['title'] === 'Vision Scope') {
            // For Vision Scope, force the URL to be the login page.
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
    <div class="footer glossy-line">
        <ul>
            <li><a href="../index.html" onclick="navigateLink(event, this)">Home</a></li>
            <li><a href="../porfolio.html" onclick="navigateLink(event, this)">Back</a></li>
            <li><a href="./About/about.html" onclick="navigateLink(event, this)">About</a></li>
            <li><a href="https://gr.linkedin.com/in/ambel-basha-367156123" target="_blank" rel="noopener noreferrer" onclick="navigateLink(event, this)">Contact</a></li>
        </ul>
    </div>
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
<!---
<script>
const INSTANCE_KEY = 'uniquePageInstanceId';

window.onload = function() {
    console.log("Page loaded.");

    // Log initial path before normalization
    console.log("Initial Path: " + window.location.pathname);  // Show the path before normalization

    // Normalize the path to remove any double slashes (//) and trailing slashes
    const normalizedPath = normalizePath(window.location.pathname);
    
    // Log the normalized path
    console.log("Normalized Path: " + normalizedPath);  // Show the path after normalization

    const params = new URLSearchParams(window.location.search);
    const instanceIdFromUrl = params.get('instance_id');
    let storedInstanceId = localStorage.getItem(INSTANCE_KEY);

    // Ensure that instance IDs are logged in a consistent format
    console.log('Stored Instance ID:', storedInstanceId);
    console.log('URL Instance ID:', instanceIdFromUrl);
    console.log('Window Name:', window.name);

    // Normalize and compare storedInstanceId and window.name values (to handle any case issues)
    const normalizedStoredInstanceId = storedInstanceId ? storedInstanceId.toString().toLowerCase() : '';
    const normalizedWindowName = window.name ? window.name.toString().toLowerCase() : '';

    // If there is an instance_id in the URL, store it in localStorage and set window.name
    if (instanceIdFromUrl) {
        localStorage.setItem(INSTANCE_KEY, instanceIdFromUrl);
        window.name = instanceIdFromUrl; // Set window.name with instance_id from URL
        storedInstanceId = instanceIdFromUrl; // Update stored instance ID
        console.log("Instance ID from URL stored in localStorage.");
    } else if (!storedInstanceId) {
        // If there is no instance_id in the URL and nothing in localStorage, create a new instance
        const newInstanceId = Date.now().toString();
        localStorage.setItem(INSTANCE_KEY, newInstanceId);
        window.name = newInstanceId; // Set window.name with new instance ID
        storedInstanceId = newInstanceId; // Update stored instance ID
        console.log("No instance ID in URL. New instance created.");
    }

    console.log('Normalized Stored Instance ID:', normalizedStoredInstanceId);
    console.log('Normalized Window Name:', normalizedWindowName);

    // Ensure window.name is set before proceeding with the comparison
    if (normalizedStoredInstanceId === normalizedWindowName) {
        console.log("Instance matched, proceeding without modal.");
        // If instance ID from URL is different than the stored instance, redirect
        if (instanceIdFromUrl && instanceIdFromUrl !== window.name) {
            console.log('Redirecting to the existing instance...');
            redirectToExistingInstance();
        }
    } else {
        console.log("New instance detected, showing modal...");
        showModal();  // Ensure this is triggered
    }

    // Clean up the stored instance ID when the page is unloaded
    window.addEventListener('beforeunload', () => {
        const currentId = localStorage.getItem(INSTANCE_KEY);
        if (currentId === window.name) {
            localStorage.removeItem(INSTANCE_KEY);
            console.log('Instance removed from localStorage.');
        }
    });
};

function normalizePath(path) {
    // Normalize the path to ensure no double slashes (//) and no trailing slashes
    let normalized = path.replace(/\/+/g, "/").replace(/\/+$/, "").toLowerCase();  // Replace multiple slashes with a single slash and convert to lowercase

    // Ensure the path begins with a single slash
    if (normalized && normalized.charAt(0) !== '/') {
        normalized = '/' + normalized;
    }

    // Handle potential double slashes issue explicitly
    if (normalized.indexOf("//") !== -1) {
        normalized = normalized.replace(/\/\//g, "/"); // Replace any double slashes with a single slash
    }

    // Force the directory name to lower case to avoid case mismatch (Webpages vs WebPages)
    normalized = normalized.replace(/\/(webpages|webPages)/i, "/webpages"); // Ensures lowercase "webpages" consistently

    return normalized;
}

function showModal() {
    const modal = document.getElementById('modal');
    if (!modal) {
        console.error('Modal element not found!');
        return;
    }

    console.log('Showing modal now');  // Add this to confirm the modal function is reached
    modal.style.display = 'block';
    console.log('Modal displayed to the user.');

    const redirectButton = document.getElementById('redirectButton');
    const openNewButton = document.getElementById('openNewButton');
    const closeButton = document.getElementById('closeButton');

    if (redirectButton) {
        redirectButton.onclick = function() {
            console.log('Redirecting to existing instance...');
            redirectToExistingInstance();
        };
    } else {
        console.error('Redirect button not found!');
    }

    if (openNewButton) {
        openNewButton.onclick = function() {
            console.log('Opening a new instance...');
            closeModal();
            localStorage.removeItem(INSTANCE_KEY);
            window.location.reload();
        };
    } else {
        console.error('Open new button not found!');
    }

    if (closeButton) {
        closeButton.onclick = function() {
            closeModal();
            console.log('Modal closed by the user.');
        };
    } else {
        console.error('Close button not found!');
    }
}

function closeModal() {
    const modal = document.getElementById('modal');
    if (!modal) {
        console.error('Modal element not found!');
        return;
    }

    modal.style.display = 'none';
}

function redirectToExistingInstance() {
    const storedInstanceId = localStorage.getItem(INSTANCE_KEY);
    if (!storedInstanceId) {
        console.error('No stored instance ID found!');
        return;
    }

    // Ensure the current path is normalized without leading/trailing slashes
    const currentPath = window.location.pathname.replace(/\/+$/, ""); // Remove trailing slashes

    // Normalize the URL to avoid double slashes
    const normalizedUrl = `${window.location.origin}${normalizePath(currentPath)}?instance_id=${storedInstanceId}`;

    console.log('Redirecting to:', normalizedUrl);
    window.location.href = normalizedUrl;
}
</script>---->
<script>
const INSTANCE_KEY = 'uniquePageInstanceId';

window.onload = function() {
    console.log("Page loaded.");

    // Check if an instance_id is already in localStorage
    let storedInstanceId = localStorage.getItem(INSTANCE_KEY);

    // If no instance_id is found in localStorage, create and store a new one
    if (!storedInstanceId) {
        const newInstanceId = Date.now().toString(); // Unique ID based on current time
        localStorage.setItem(INSTANCE_KEY, newInstanceId);
        storedInstanceId = newInstanceId; // Update stored instance ID variable
        console.log("New instance ID created and stored:", storedInstanceId);
    } else {
        console.log("Existing instance ID found in localStorage:", storedInstanceId);
    }

    // Set window.name to the instance_id from localStorage to maintain consistency
    window.name = storedInstanceId;

    // Log the instance ID to confirm
    console.log("Window name set to instance ID:", window.name);
};

</script>
    <script src="Scripts/BackgroundHexBuilder/backgroundHexBuilder.js"></script>
    <script src="Scripts/FooterVisibility/footerVisibility.js"></script>
</body>
</html>
