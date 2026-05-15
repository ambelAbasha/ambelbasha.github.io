<?php
include('db.php');

// Ensure the connection is still open before preparing the query
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: Unable to connect to the database. Please try again later.");
}

// Query to fetch data from the sales table
$sql = "SELECT * FROM sales";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared correctly
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
    <title>Sales Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<h2>Sales Table</h2>
<table>
    <thead>
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

                // Assuming thresholds are fetched from session or database
                $thresholds = [
                    '100' => 'darkgrey',
                    '50' => 'green',
                    '15' => 'yelloworange',
                    '14' => 'red'
                ];

                $name_color = 'red';
                $quantity_color = 'red';

                // Adjust color based on quantity thresholds
                foreach ($thresholds as $threshold => $color) {
                    if ($quantity >= $threshold) {
                        $name_color = $color;
                        $quantity_color = $color;
                        break;
                    }
                }

                echo "<tr>
                        <td class='item-name' style='color: $name_color;'>$product_name</td>
                        <td class='item-quantity' style='color: $quantity_color;'>$quantity</td>
                        <td>$price €</td>
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

</body>
</html>
