<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accno = $_POST["accno"];
    $transactionType = $_POST["transactionType"];
    
    // Execute the shell script and capture the output
    $output = shell_exec("./atm_script.sh $accno $transactionType");
    
    // Output the result
    echo $output;
}
?>
