<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $names = $_POST['name'] ?? [];
    $marks = $_POST['marks'] ?? [];

    if (count($names) != count($marks)) {
        echo "<p>Error: Number of names and marks do not match.</p>";
    } else {
        $fptr = fopen("student.txt", "a");
        if ($fptr === false) {
            echo "<p>Error opening file for appending.</p>";
        } else {
            for ($i = 0; $i < count($names); $i++) {
                fwrite($fptr, "Name: {$names[$i]} \nMarks: {$marks[$i]} \n\n");
            }
            fclose($fptr);
            echo "<p>Data saved successfully.</p>";
        }
    }
} else {
    echo "<p>Invalid request method.</p>";
}
?>
