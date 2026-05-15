<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Analyzer - Result</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="outer-container">
        <div class="container center">
            <div class="box">
                <h1>Product Analyzer - Result</h1>
                <?php
                // Database connection
                $link = mysqli_connect("localhost", "root", "", "items");

                // Check connection
                if (!$link) {
                    die('Could not connect: ' . mysqli_error($link));
                }

                // Query to select all items
                $query = "SELECT * FROM items_value";
                $result = mysqli_query($link, $query);

                // Function to calculate average item price with 2 decimal places
                function showAvg($result) {
                    $count = 0;
                    $total_price = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $count++;
                        $total_price += $row['items_price'];
                    }
                    if ($count > 0) {
                        $avg_price = round($total_price / $count, 2); // round to 2 decimal places
                        echo "<div>Average Item's Price: $avg_price €</div>";
                    } else {
                        echo "<div>No items found</div>";
                    }
                }

                // Function to find item with minimum price
                function showMin($result) {
                    $min_price = PHP_INT_MAX;
                    $min_item = "";
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['items_price'] < $min_price) {
                            $min_price = $row['items_price'];
                            $min_item = $row['items_name'];
                        }
                    }
                    if ($min_item !== "") {
                        echo "<div>Item with Minimum Price: $min_item $min_price €</div>";
                    } else {
                        echo "<div>No items found</div>";
                    }
                }

                // Function to find item with maximum price
                function showMax($result) {
                    $max_price = PHP_INT_MIN;
                    $max_item = "";
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['items_price'] > $max_price) {
                            $max_price = $row['items_price'];
                            $max_item = $row['items_name'];
                        }
                    }
                    if ($max_item !== "") {
                        echo "<div>Item with Maximum Price: $max_item $max_price €</div>";
                    } else {
                        echo "<div>No items found</div>";
                    }
                }

                // Perform action based on user selection
                $action = isset($_POST['action']) ? $_POST['action'] : '';
                switch ($action) {
                    case "Average":
                        showAvg($result);
                        break;
                    case "Minimum":
                        showMin($result);
                        break;
                    case "Maximum":
                        showMax($result);
                        break;
                    default:
                        echo "<div>Invalid action selected</div>";
                        break;
                }

                // Close connection
                mysqli_close($link);
                ?>
                <br>
                <a href="input_form.php" class="btn">Back to Input Form</a>
            </div>
        </div>
    </div>
    <div class="return-button">
        <button onclick="window.location.href='../index.html'">Back</button>
    </div>
</body>
</html>
