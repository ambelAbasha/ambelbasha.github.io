<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Analyzer - Input Form</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="outer-container">
        <div class="container">
            <div class="box">
                <h1>Product Analyzer - Input Form</h1>
                <form method="post" action="process_input.php">
                    <div class="form-container">
                        <p><label for="action">Action:</label></p>
                        <select name="action" id="action">
                            <option value="average">Average</option>
                            <option value="minimum">Minimum</option>
                            <option value="maximum">Maximum</option>
                        </select>
                    </div>
                    <div class="form-container">
                        <input type="submit" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
