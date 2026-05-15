<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cancel Reservation</title>
    <link href="mystyle.css" rel="stylesheet" type="text/css"/>
</head>
<body id="indexBody">
<?php
session_start();
echo "<h1><center>Your Flight:</center></h1>";

// Database connection
$con = mysqli_connect("localhost", "root", "", "flynow"); // Updated to MySQLi
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['username'] ?? ''; // Safely get username

// Fetch reservation details
$query = "SELECT fid FROM reserv WHERE username = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fid);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($fid) {
    // Fetch flight details
    $query2 = "SELECT * FROM flights WHERE id = ?";
    $stmt2 = mysqli_prepare($con, $query2);
    mysqli_stmt_bind_param($stmt2, "i", $fid);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);

    if (mysqli_num_rows($result2) > 0) {
        echo '<form method="get">';
        echo '<table width="96%" height="52" align="center" class="CSSTable" id="searchTable">';
        echo '<tr>
                <th scope="col">Id</th>
                <th scope="col">From</th>
                <th scope="col">To</th>
                <th scope="col">Airline</th>
                <th scope="col">Date</th>
                <th scope="col">Cost</th>
                <th scope="col">Class</th>
                <th scope="col">Free Seats</th>
              </tr>';

        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo '<tr>
                    <td>' . htmlspecialchars($row2['id']) . '</td>
                    <td>' . htmlspecialchars($row2['_from']) . '</td>
                    <td>' . htmlspecialchars($row2['_to']) . '</td>
                    <td>' . htmlspecialchars($row2['airlines']) . '</td>
                    <td>' . htmlspecialchars($row2['date']) . '</td>
                    <td>' . htmlspecialchars($row2['cost']) . '</td>
                    <td>' . htmlspecialchars($row2['_class']) . '</td>
                    <td>' . htmlspecialchars($row2['free_seats']) . '</td>
                  </tr>';
        }
        echo '</table><br><br>';

        // Handle the remove action
        if (isset($_GET['remove'])) {
            $query3 = "DELETE FROM reserv WHERE fid = ?";
            $stmt3 = mysqli_prepare($con, $query3);
            mysqli_stmt_bind_param($stmt3, "i", $fid);
            mysqli_stmt_execute($stmt3);
            mysqli_stmt_close($stmt3);

            header("Location: index.php");
            exit(); // Ensure no further code runs after redirection
        }

        echo '<center><input type="submit" name="remove" value="Remove"></center>';
        echo '</form>';
    } else {
        echo "<center>No available flight!</center>";
    }

    mysqli_stmt_close($stmt2);
} else {
    echo "<center>No reservations found!</center>";
}

mysqli_close($con); // Close the database connection
?>
</body>
</html>
