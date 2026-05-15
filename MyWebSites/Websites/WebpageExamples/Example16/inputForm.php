<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Form</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="outer-container">
        <div class="container center">
            <div class="box">
                <h1>Product Analyzer - Input Form</h1>
                <form method="post" action="processInput.php">
                    <div class="form-group">
                        <label for="action">Action:</label>
                        <select name="action" id="action">
                            <option value="Average">Average</option>
                            <option value="Minimum">Minimum</option>
                            <option value="Maximum">Maximum</option>
                        </select>
                    </div>
                    <input type="submit" value="Submit" class="btn">
                </form>
            </div>
        </div>
    </div>
    <div class="return-button">
        <button onclick="window.location.href='../index.html'">Back</button>
    </div>
</body>
</html>
