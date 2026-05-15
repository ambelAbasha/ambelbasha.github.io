<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Page Title: Calendar -->
    <title>Calendar</title>

    <!-- Meta Information for SEO and page description -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A dynamic calendar built with PHP, HTML, and CSS that displays the current month and allows navigation between months.">
    <meta name="keywords" content="calendar, PHP, HTML, CSS, MySQL, dynamic, navigation">
    <meta name="author" content="Ambel Basha">

    <!-- Link to the external stylesheet for styling the calendar page -->
    <link rel="stylesheet" href="Styles/styles.css">
</head>
<body bgcolor="white">

<!-- Outer Container to center content -->
<div class="outer-container">
    <div class="container">
        <!-- Centered Table for the Calendar -->
        <center>
            <table class="calendar-table">
                <?php
                // PHP Code to handle dynamic month and year navigation
                if(isset($_GET['next'])) {
                    // Navigate to next month
                    $month = $_GET['month'];
                    if($month == 12) {
                        $year = $_GET['year'] + 1;
                        $month = 1;
                    } else {
                        $year = $_GET['year'];
                        $month++;
                    }
                } elseif(isset($_GET['prev'])) {
                    // Navigate to previous month
                    $month = $_GET['month'];
                    if($month == 1) {
                        $year = $_GET['year'] - 1;
                        $month = 12;
                    } else {
                        $year = $_GET['year'];
                        $month--;
                    }
                } else {
                    // Default to current month and year
                    $month = date('m');
                    $year = date('Y');
                }

                // Get the current date for today's highlighted date
                $cur_date = mktime(0, 0, 0, $month, 1, $year);
                $month_name = date('F', $cur_date);  // Get the month name
                $week_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');  // Weekdays array

                // Display the current month and year in the header
                echo "<tr><td colspan=\"7\" class=\"month-cell\"><center>$month_name $year</center></td></tr>";
                echo '<tr>';

                // Loop through weekdays for the table header
                foreach($week_days as $day) {
                    echo "<td class=\"week-day\">$day</td>";
                }
                echo '</tr>';

                // Get the number of days in the current month and the first day of the month
                $num_of_days = date('t', $cur_date);
                $first_day = date('N', $cur_date);
                $cur_day = date('j');  // Get the current day of the month

                // Initialize day counter
                $day_counter = 1;

                // Generate the calendar table dynamically
                while ($day_counter <= $num_of_days + $first_day - 1) {
                    echo '<tr>';
                    for ($i = 1; $i <= 7; $i++) {
                        // Empty cells before the first day of the month
                        if ($day_counter <= $first_day - 1 || $day_counter - $first_day + 1 > $num_of_days) {
                            echo '<td class="empty-cell">&nbsp;</td>';
                        } else {
                            $day = $day_counter - $first_day + 1;
                            // Highlight the current day
                            if ($day == $cur_day && $month == date('m') && $year == date('Y')) {
                                echo '<td class="curr-date">' . $day . '</td>';
                            } else {
                                echo '<td>' . $day . '</td>';
                            }
                        }
                        $day_counter++;
                    }
                    echo '</tr>';
                }

                // Add empty rows if the month doesn't fill 6 rows
                $remaining_rows = 6 - ceil(($num_of_days + $first_day - 1) / 7);
                for ($i = 0; $i < $remaining_rows; $i++) {
                    echo '<tr>';
                    for ($j = 0; $j < 7; $j++) {
                        echo '<td class="empty-cell">&nbsp;</td>';
                    }
                    echo '</tr>';
                }
                ?>
            </table>

            <!-- Form for navigation between months -->
            <form name="nav_form" method="get" action="<?=$_SERVER['PHP_SELF']?>">
                <input type="Submit" name="prev" value="<< Prev"/>
                <input type="Submit" name="next" value="Next >>"/>
                <input type="hidden" name="month" value="<?=$month?>"/>
                <input type="hidden" name="year" value="<?=$year?>"/>
            </form>
        </center>
    </div>

    <!-- Return Button to go back to the main page -->
    <div class="return-button">
        <button onclick="window.location.href='../index.html'">Back</button>
    </div>
</div>

</body>
</html>
