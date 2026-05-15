<!DOCTYPE html>
<html>
<head>
    <title>Product Analyzer - Result</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container center">
        <h2>Product Analyzer - Result</h2>
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

        // Function to calculate average item price
        function showAvg($result) {
            $count = 0;
            $total_price = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $count++;
                $total_price += $row['items_price'];
            }
            if ($count > 0) {
                $avg_price = $total_price / $count;
                echo "Average Item's Price: $avg_price<br>";
            } else {
                echo "No items found<br>";
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
                echo "Item with Minimum Price: $min_item ($min_price)<br>";
            } else {
                echo "No items found<br>";
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
                echo "Item with Maximum Price: $max_item ($max_price)<br>";
            } else {
                echo "No items found<br>";
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
                echo "Invalid action selected<br>";
                break;
        }

        // Close connection
        mysqli_close($link);
        ?>
        <br>
        <a href="input_form.php" class="btn">Back to Input Form</a>
    </div>
</body>
</html>
