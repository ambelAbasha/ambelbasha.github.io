<?php    
include('db.php');

// Query to fetch threshold values from the 'thresholds' table
$sqlThresholds = "SELECT * FROM thresholds LIMIT 1";
$stmtThresholds = $conn->prepare($sqlThresholds);
$stmtThresholds->execute();
$thresholdsResult = $stmtThresholds->get_result();
$thresholds = $thresholdsResult->fetch_assoc();

// Query to fetch data from the sales table
$sql = "SELECT * FROM sales";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    error_log("Query preparation failed: " . $conn->error);
    die("Error fetching data. Please try again later.");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ambel Basha">
    <meta name="description" content="Sales table with dynamic color coding for stock levels based on quantity thresholds.">
    <meta name="keywords" content="portfolio, sales, PHP, MySQL, stock, color coding">
    <meta name="robots" content="index, follow">
    <title>Sales Table with Color Coding</title>
    <link rel="stylesheet" href="Styles/styles.css">
    <script src="Scripts/scrollBar.js" defer></script>
</head>
<body>

<div class="wrapper">
    <div class="container">
        <!-- Display success or error message -->
        <?php
        if (isset($_SESSION['db_message'])) {
            $message = $_SESSION['db_message'];
            $class = $message['type'] == 'success' ? 'db-message success' : 'db-message error';
            echo "<div class=\"$class\">";
            echo "<span class=\"icon\">" . ($message['type'] == 'success' ? '✅' : '❌') . "</span>";
            echo $message['message'];
            echo "</div>";
            unset($_SESSION['db_message']); // Clear the message after displaying it
        }
        ?>

        <div class="table-container">
            <h3>Table of Computer Peripherals - Sales</h3>
            <!-- Sales Table -->
<div class="sales-table-container">
    <h4>Table of Sales</h4>
    <table>
        <thead>
            <tr>
                <th colspan="3">Table of Sales</th>
            </tr>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $product_name = isset($row['item']) ? htmlspecialchars($row['item']) : 'No Product Name';
                    $quantity = isset($row['quantity']) ? (int)$row['quantity'] : 0;
                    $price = isset($row['price']) ? htmlspecialchars($row['price']) : 'No Price';

                    // Define color thresholds for stock levels (from database)
                    $quantityThresholds = [
                        $thresholds['threshold_100'] => 'darkgrey', 
                        $thresholds['threshold_50'] => 'green', 
                        $thresholds['threshold_15'] => 'yellow', 
                        $thresholds['threshold_14'] => 'red'
                    ];

                    $name_color = 'red';
                    $quantity_color = 'red';

                    // Adjust color based on quantity
                    foreach ($quantityThresholds as $threshold => $color) {
                        if ($quantity >= $threshold) {
                            $name_color = $color;
                            $quantity_color = $color;
                            break;
                        }
                    }

                    // Display product info
                    echo "<tr id='item-" . $row['id'] . "'>
                            <td class='item-name' style='color: $name_color;'>" . $product_name . "</td>
                            <td class='item-quantity' style='color: $quantity_color;'>" . $quantity . "</td>
                            <td>" . $price . " €</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No data available</td></tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Color Coding Table -->
<!-- Color Coding Table -->
<div class="color-coding-container">
    <h4>Color Coding Explanation</h4>
    <table>
        <thead>
            <tr>
                <th>Color</th>
                <th>Quantity Threshold</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="background-color: darkgrey;"></td>
                <td class="threshold-cell">
                    <span class="threshold-label">Threshold for 100+ Quantity:</span>
                    <input type="range" id="threshold-100" min="0" max="200" class="threshold-range" value="<?php echo $thresholds['threshold_100']; ?>">
                    <span id="threshold-100-display"><?php echo $thresholds['threshold_100']; ?></span>
                </td>
                <td>Dark Grey: Stock is plentiful</td>
            </tr>
            <tr>
                <td style="background-color: green;"></td>
                <td class="threshold-cell">
                    <span class="threshold-label">Threshold for 50+  Quantity :</span>
                    <input type="range" id="threshold-50" min="0" max="200" class="threshold-range" value="<?php echo $thresholds['threshold_50']; ?>">
                    <span id="threshold-50-display"><?php echo $thresholds['threshold_50']; ?></span>
                </td>
                <td>Green: Stock is healthy</td>
            </tr>
            <tr>
                <td style="background-color: orange;"></td>
                <td class="threshold-cell">
                    <span class="threshold-label">Threshold for 15+ Quantity :</span>
                    <input type="range" id="threshold-15" min="0" max="200" class="threshold-range" value="<?php echo $thresholds['threshold_15']; ?>">
                    <span id="threshold-15-display"><?php echo $thresholds['threshold_15']; ?></span>
                </td>
                <td>Yellow-Orange: Stock is moderate</td>
            </tr>
            <tr>
                <td style="background-color: red;"></td>
                <td class="threshold-cell">
                    <span class="threshold-label">Threshold for < 14 Quantity :</span>
                    <input type="range" id="threshold-14" min="0" max="200" class="threshold-range" value="<?php echo $thresholds['threshold_14']; ?>">
                    <span id="threshold-14-display"><?php echo $thresholds['threshold_14']; ?></span>
                </td>
                <td>Red: Stock is low</td>
            </tr>
        </tbody>
    </table>
</div>
            <!-- Update Thresholds HTML for the button -->
            <button id="update-thresholds-btn">Update Thresholds</button>

            <!-- Success Modal -->
            <div id="successModal" class="modal">
                <div class="modal-content">
                    <span id="modalMessage"></span> <!-- Success message will appear here -->
                </div>
            </div>

        </div>
    </div>
    <!-- Return button that takes the user back to the main page -->
    <div class="return-button">
        <button onclick="window.location.href='../index.html'">Back</button>
    </div>
</div>

<script>
// Handle slider changes and dynamic updates
document.querySelectorAll('.threshold-range').forEach(range => {
    range.addEventListener('input', (event) => {
        const threshold = event.target.id.split('-')[1];  // Get the threshold ID (e.g., "100")
        const value = event.target.value;
        document.getElementById(`threshold-${threshold}-display`).textContent = value; // Update the display text

        // Update the database with the new threshold value using AJAX
        updateThresholdInDatabase(threshold, value);

        // Update colors based on the new thresholds
        updateTableColors();
    });
});

// Function to update colors based on new threshold values
function updateTableColors() {
    const thresholds = {
        100: document.getElementById('threshold-100').value,
        50: document.getElementById('threshold-50').value,
        15: document.getElementById('threshold-15').value,
        14: document.getElementById('threshold-14').value
    };

    const rows = document.querySelectorAll('tr[id^="item-"]');
    rows.forEach(row => {
        const quantity = parseInt(row.querySelector('.item-quantity').textContent);
        let color = 'red';

        if (quantity >= thresholds[100]) {
            color = 'darkgrey';
        } else if (quantity >= thresholds[50]) {
            color = 'green';
        } else if (quantity >= thresholds[15]) {
            color = 'orange';
        }

        row.querySelector('.item-quantity').style.color = color;
        row.querySelector('.item-name').style.color = color;
    });
}

// Update the database with new threshold values
function updateThresholdInDatabase(threshold, value) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "updateThreshold.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                showModal(response.message);
            }
        }
    };
    xhr.send(`threshold=${threshold}&value=${value}`);
}

// Show success modal
function showModal(message) {
    const modal = document.getElementById('successModal');
    const modalMessage = document.getElementById('modalMessage');
    modalMessage.textContent = message;
    modal.style.display = 'block';

    setTimeout(() => {
        modal.style.display = 'none';
    }, 3000);
}
</script>

</body>
</html>
