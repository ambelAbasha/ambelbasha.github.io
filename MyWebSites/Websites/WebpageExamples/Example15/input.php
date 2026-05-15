<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter a Number</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Link to the CSS file -->
</head>
<body>
    <div class="container"> <!-- Center the form -->
        <div class="box"> <!-- Box styling -->
            <h2>Search a Number</h2> <!-- Title -->

            <!-- Form with GET method, action leading to result.php -->
            <form method="get" action="result.php" class="form-container">
                <input type="number" name="val" placeholder="Enter a number" required pattern="\d+" title="Please enter an integer" class="input-field"> <!-- Input field with number type and pattern -->
                <br>
                <input type="submit" value="Submit" class="submit-button"> <!-- Submit button with updated styling -->
            </form>
            <div class="return-button"> <!-- Return button container -->
            <button onclick="window.location.href='../index.html'">Back</button> <!-- Return button -->
        </div>
        </div>
    </div>
</body>
</html>
