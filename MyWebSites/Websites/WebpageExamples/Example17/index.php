<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Ensure this path is correct -->
    <script>
        function clearIdField() {
            const action = document.getElementById("action").value;
            if (action === "ALL" || action === "DELETE") {
                document.getElementById("code").value = ""; // Clear the ID field
            }
        }
    </script>
</head>
<body class="form-page">
    <div class="outer-container"> <!-- Added outer container -->
        <div class="container"> <!-- Flex container for form -->
            <h2 class="heading-choice">Enter a choice</h2> <!-- Centered heading at the top -->
            <form method="post" action="form.php">
                <div class="form-group">
                    <label for="code">ID:</label>
                    <input type="text" id="code" name="code" placeholder="Enter a student's id"> <!-- Placeholder text -->
                </div>
                <div class="form-group"> <!-- Spacing between form elements -->
                    <label for="action" class="custom-select-label">CHOICE:</label>
                    <select id="action" name="action" class="custom-select" onchange="clearIdField()"> <!-- Add event listener -->
                        <option>DETAILS</option>
                        <option>ALL</option>
                        <option>DELETE</option>
                    </select>
                </div>
                <!-- Centered submit button with additional spacing -->
                <div style="text-align: center;"> <!-- Centering content -->
                    <input type="submit" value="OK" class="submit-button">
                </div>
            </form>
        </div> <!-- End container -->
        <div class="return-button"> <!-- Added return button -->
            <button onclick="window.location.href='../index.html'">Return</button>
        </div>
    </div> <!-- End of outer-container -->
</body>
</html>
