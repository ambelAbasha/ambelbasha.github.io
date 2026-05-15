<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Pagination</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body class="index-page">
    <div class="outer-container">
        <div class="page-container">
            <?php
                $action = $_POST['action'] ?? '';
                $code = intval($_POST['code'] ?? ''); // Convert code to integer to mitigate SQL injection
                $page = intval($_POST['page'] ?? 1); // Get the current page number

                $link = mysqli_connect("localhost", "root", "", "students");
                if (!$link) {
                    echo "<div class='result-container'>Error: Unable to connect to the database.</div>"; // Styling error message
                } else {
                    // Determine which query to run based on the action
                    switch ($action) {
                        case "DETAILS":
                            query1($link, $code);
                            break;
                        case "ALL":
                            query2($link, $page);
                            break;
                        case "DELETE":
                            query3($link, $code); // Updated query to prevent SQL injection
                            break;
                        default:
                            echo "<div class='result-container'>Invalid action specified.</div>";
                    }
                    // Close the database connection
                    mysqli_close($link);
                }

                // Function to retrieve a specific student's details using parameterized query
                function query1($link, $code) {
                    $query = "SELECT @row := @row + 1 AS `Record Nr`, id AS `ID`, name AS `Name`, surname AS `Surname`, email AS `Email` FROM details, (SELECT @row := 0) r WHERE id = ?";
                    $stmt = mysqli_prepare($link, $query); // Prepare the query with a placeholder

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, 'i', $code); // Bind parameter
                        mysqli_stmt_execute($stmt); // Execute the statement

                        $result = mysqli_stmt_get_result($stmt); // Get the result
                        if (mysqli_num_rows($result) == 0) {
                            echo "<div class='result-container'>No data matching your search criteria.</div>";
                        } else {
                            echo "<div class='result-container'>";
                            echo "<div class='result-header'>Student Details</div>";
                            echo "<table>";
                            echo "<tr><th>Record Nr</th><th>ID</th><th>Name</th><th>Surname</th><th>Email</th></tr>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr><td>" . $row['Record Nr'] . "</td><td>" . $row['ID'] . "</td><td>" . $row['Name'] . "</td><td>" . $row['Surname'] . "</td><td>" . $row['Email'] . "</td></tr>";
                            }
                            echo "</table>";
                            echo "</div>";
                        }

                        mysqli_stmt_close($stmt); // Close the statement
                    } else {
                        echo "<div class='result-container'>Error preparing statement.</div>";
                    }
                }

                // Function to retrieve all student details with pagination
                function query2($link, $page) {
                    $records_per_page = 10;
                    $offset = ($page - 1) * $records_per_page;

                    // Count total records
                    $total_query = "SELECT COUNT(*) as total FROM details";
                    $total_result = mysqli_query($link, $total_query);
                    $total_row = mysqli_fetch_assoc($total_result);
                    $total_records = $total_row['total'];
                    $total_pages = ceil($total_records / $records_per_page);

                    $query = "SELECT @row := @row + 1 AS `Record Nr`, id AS `ID`, name AS `Name`, surname AS `Surname`, email AS `Email` FROM details, (SELECT @row := $offset) r LIMIT $records_per_page OFFSET $offset";
                    $result = mysqli_query($link, $query);

                    if (!$result || mysqli_num_rows($result) == 0) {
                        echo "<div class='result-container'>No data available.</div>";
                    } else {
                        echo "<div class='result-container'>";
                        echo "<div class='result-header'>Student Records Results</div>";
                        echo "<table><tr><th>Record Nr</th><th>ID</th><th>Name</th><th>Surname</th><th>Email</th></tr>";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>" . $row['Record Nr'] . "</td><td>" . $row['ID'] . "</td><td>" . $row['Name'] . "</td><td>" . $row['Surname'] . "</td><td>" . $row['Email'] . "</td></tr>";
                        }
                        echo "</table>";

                        // Pagination controls
                        echo "<div class='pagination'>";
                        if ($page > 1) {
                            echo "<a href='#' class='page-link' data-page='" . ($page - 1) . "'>&laquo; Prev</a>";
                        }
                        // Limiting the number of pagination elements to five
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $start + 4);
                        for ($i = $start; $i <= $end; $i++) {
                            $active = $i == $page ? 'active' : '';
                            echo "<a href='#' class='page-link $active' data-page='$i'>$i</a>";
                        }
                        if ($page < $total_pages) {
                            echo "<a href='#' class='page-link' data-page='" . ($page + 1) . "'>Next &raquo;</a>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                }

                // Function to delete a specific student's record using parameterized query
                function query3($link, $code) {
                    // Check if the record exists using a parameterized query
                    $check_query = "SELECT * FROM details WHERE id = ?";
                    $stmt_check = mysqli_prepare($link, $check_query);

                    if ($stmt_check) {
                        mysqli_stmt_bind_param($stmt_check, 'i', $code); // Bind parameter
                        mysqli_stmt_execute($stmt_check); // Execute query
                        $check_result = mysqli_stmt_get_result($stmt_check); // Get result

                        if (mysqli_num_rows($check_result) == 0) {
                            echo "<div class='result-container'>No record matching the criteria to delete.</div>"; // Styled message
                        } else {
                            $record = mysqli_fetch_assoc($check_result); // Get record details
                            $delete_query = "DELETE FROM details WHERE id = ?";
                            $stmt_delete = mysqli_prepare($link, $delete_query);

                            if ($stmt_delete) {
                                mysqli_stmt_bind_param($stmt_delete, 'i', $code); // Bind parameter
                                mysqli_stmt_execute($stmt_delete); // Execute deletion
                            
                                // Detailed message with ID, name, and surname
                                echo "<div class='result-container'>"; 
                                echo "<div class='deletion-message'>";
                                echo "Student with ID: " . $record['id'] . " Name: " . $record['name'] . " Surname: " . $record['surname'] . " has been successfully removed.";
                                echo "</div>";
                                echo "</div>";
                                mysqli_stmt_close($stmt_delete); // Close the statement
                            } else {
                                echo "<div class='result-container'>Error preparing delete statement.</div>"; // Styled message
                            }                            

                        }

                        mysqli_stmt_close($stmt_check); // Close the check statement
                    } else {
                        echo "<div class='result-container'>Error preparing check statement.</div>";
                    }
                }
            ?>
        </div> <!-- End page-container -->
        <div class="return-button">
            <button onclick="window.location.href='index.php'">Return</button>
        </div>
    </div> <!-- End Of Outer Container -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.page-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                let page = this.getAttribute('data-page');
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'action';
                input.value = 'ALL';
                form.appendChild(input);
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'page';
                input.value = page;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            });
        });
    });
    </script>
</body>
</html>
