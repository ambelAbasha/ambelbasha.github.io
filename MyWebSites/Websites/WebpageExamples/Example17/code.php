<?php
// Get the action and code from POST request
$action = $_POST['action'] ?? '';
$code = intval($_POST['code'] ?? ''); // Convert to integer to prevent SQL injection

// Connect to the database
$link = mysqli_connect("localhost", "root", "", "students");
if (!$link) {
    echo "Error: Unable to connect to the database.<br>";
} else {
    mysqli_select_db($link, "students");

    // Switch based on the action to determine which query to run
    switch ($action) {
        case "DETAILS":
            query1($link, $code);
            break;
        case "ALL":
            query2($link);
            break;
        case "DELETE":
            query3($link, $code);
            break;
        default:
            echo "Invalid action specified.";
    }

    // Close the database connection
    mysqli_close($link);
}

// Function to retrieve a specific student's details using parameterized query
function query1($link, $code) {
    $query = "SELECT * FROM details WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $code); // 'i' means integer
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 0) {
            echo "No data matching your search criteria.";
        } else {
            $row = mysqli_fetch_assoc($result);
            echo "ID: " . $row['id'] . ", Name: " . $row['name'] . ", Surname: " . $row['surname'] . ", Email: " . $row['email'] . "<br>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement.<br>";
    }
}

// Function to retrieve all student details
function query2($link) {
    $query = "SELECT * FROM details";
    $result = mysqli_query($link, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        echo "No data available.";
    } else {
        echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Surname</th><th>Email</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['name'] . "</td><td>" . $row['surname'] . "</td><td>" . $row['email'] . "</td></tr>";
        }
        echo "</table>";
    }
}

// Function to delete a specific student's record using parameterized query
function query3($link, $code) {
    // Check if the record exists before attempting to delete
    $check_query = "SELECT * FROM details WHERE id = ?";
    $stmt_check = mysqli_prepare($link, $check_query);

    if ($stmt_check) {
        mysqli_stmt_bind_param($stmt_check, 'i', $code);
        mysqli_stmt_execute($stmt_check);
        $check_result = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($check_result) == 0) {
            echo "No record matching the criteria to delete.";
        } else {
            $record = mysqli_fetch_assoc($check_result);

            // Delete the record with a parameterized query
            $delete_query = "DELETE FROM details WHERE id = ?";
            $stmt_delete = mysqli_prepare($link, $delete_query);

            if ($stmt_delete) {
                mysqli_stmt_bind_param($stmt_delete, 'i', $code);
                mysqli_stmt_execute($stmt_delete);

                echo "Student with ID: " . $record['id'] . "\n";
                echo "Name: " . $record['name'] . "\n";
                echo "Surname: " . $record['surname'] . "\n";
                echo "Email: " . $record['email'] . "\n";
                echo "has been successfully removed.";

                mysqli_stmt_close($stmt_delete);
            } else {
                echo "Error preparing delete statement.<br>";
            }
        }

        mysqli_stmt_close($stmt_check);
    } else {
        echo "Error preparing check statement.<br>";
    }
}
?>
