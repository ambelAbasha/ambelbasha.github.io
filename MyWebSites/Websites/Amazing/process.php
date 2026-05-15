<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Information</title>
<link rel="stylesheet" href="Styles/Process/styles.css">
</head>
<body>
<div class="container">
    <h2>Student Information</h2>
    <?php
    $num_students = $_POST['num_students'] ?? 0;
    if ($num_students > 0) {
        $fptr = fopen("student.txt", "w");
        if ($fptr === false) {
            echo "<p>Error opening file.</p>";
        } else {
            echo "<form action='save.php' method='post'>";
            for ($i = 0; $i < $num_students; $i++) {
                echo "<label for='name{$i}'>Enter name for student " . ($i+1) . ":</label>";
                echo "<input type='text' id='name{$i}' name='name[]' required>";
                echo "<label for='marks{$i}'>Enter marks for student " . ($i+1) . ":</label>";
                echo "<input type='number' id='marks{$i}' name='marks[]' required>";
            }
            echo "<button type='submit'>Save</button>";
            echo "</form>";
            fclose($fptr);
        }
    } else {
        echo "<p>Please enter a valid number of students.</p>";
    }
    ?>
</div>
</body>
</html>
