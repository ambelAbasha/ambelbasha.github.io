<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link rel="stylesheet" href="resultStyle.css"> <!-- External CSS file -->
</head>
<body>
    <div class="outer-container"> <!-- Outer container for centering -->
        <div class="container"> <!-- Main container for content -->
            <div class="box"> <!-- Box containing the results -->
                <?php
                // PHP logic for processing search results
                if (isset($_GET['val'])) {
                    if (filter_var($_GET['val'], FILTER_VALIDATE_INT) !== false) {
                        $val = intval($_GET['val']);
                        $numbers = [1, 2, 10, 8, 7, 4, 9, 16, 12, 22, 18, 20];
                        sort($numbers);

                        function binarySearch($arr, $N, $K) {
                            $L = 0;
                            $U = $N - 1;
                            $index = -1;

                            while ($L <= $U) {
                                $i = intval(($L + $U) / 2);
                                if ($K == $arr[$i]) {
                                    $index = $i;
                                    break;
                                } elseif ($K < $arr[$i]) {
                                    $U = $i - 1;
                                } else {
                                    $L = $i + 1;
                                }
                            }
                            return ($index >= 0) ? $index + 1 : 0;
                        }

                        $result = binarySearch($numbers, count($numbers), $val);

                        if ($result == 0) {
                            echo "<div class='not-found'>The number you entered is not present in this array.</div>";
                        } else {
                            echo "<h2>Sorted Array and Search Results:</h2>";
                            echo "</br>";
                        }
                    } else {
                        echo "<div class='error-message'>Search Result: Invalid entry. Please enter an integer.</div>";
                    }
                } else {
                    echo "<div class='error-message'>Search Result: Invalid input. Please enter a valid integer from 1 to 22.</div>";
                }
                ?>

                <?php
                if (isset($result) && $result != 0) {
                    echo "<h3>Sorted Array:</h3>";
                    echo "<div class='sorted-array'>";
                    foreach ($numbers as $num) {
                        echo "<span>$num</span>";
                    }
                    echo "</div>";
                    echo "<h4>Number $val found at position $result.</h4>";
                }
                ?>
            </div> <!-- End of results box -->
        </div> <!-- End container -->

        <div class="return-button"> <!-- Return button container -->
            <button onclick="window.location.href='../index.html'">Back</button> <!-- Return button -->
        </div>
    </div> <!-- End outer-container -->
</body>
</html>
