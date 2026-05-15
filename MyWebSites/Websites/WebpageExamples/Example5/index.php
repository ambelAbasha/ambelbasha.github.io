<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Email and Domain Checker - A simple PHP tool to extract and check email and domain parts, displaying their index positions.">
    <meta name="author" content="Ambel Basha">
    <meta name="keywords" content="email, domain checker, PHP, email validation, string functions">
    <meta name="robots" content="index, follow"> <!-- Directs search engines to index the page and follow the links -->
    <title>Email and Domain Checker</title>
    <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
    <!-- Outer container holding the entire page content -->
    <div class="outer-container">
        <!-- Main content container for email and domain display -->
        <div class="container">
            
            <?php 
            // Example email address
            $given_mail = "cs121105@uniwa.gr";
            
            // Find the position of '@' in the email address
            $index_pos_at = strpos($given_mail, '@');
            
            // Split the email into username and domain parts using the '@' separator
            $email_parts = explode('@', $given_mail);
            
            // Display the email with different styling for username and domain parts
            echo "<p class='email'>
                    <span class='username'>$email_parts[0]</span>
                    <span class='at-symbol'>@</span>
                    <span class='domain'>$email_parts[1]</span>
                  </p>";
            
            // Display the index position of '@' in the email
            echo "<p class='result'><strong>The index position of @ is:</strong> $index_pos_at</p>";
            
            echo "<br/>"; // Line break between sections
            
            // Example domain address
            $str_address = "www.cs.uniwa.gr";
            
            // Find the position of '.gr' in the domain address
            $index_pos_dot = strpos($str_address, '.gr');
            
            // Split the domain into base domain and top-level domain (TLD)
            $domain_parts = explode('.gr', $str_address);
            
            // Display the domain with styling for the base domain and TLD
            echo "<p class='domain'>
                    <span class='url'>$domain_parts[0]</span>
                    <span class='tld'>.gr</span>
                  </p>";
            
            // Display the index position of '.gr' in the domain
            echo "<p class='result'><strong>The index position of .gr is:</strong> $index_pos_dot</p>";
            ?>
        </div>
        
        <!-- Button to return to the homepage -->
        <div class="return-button">
            <button onclick="window.location.href='../index.html'">Back</button>
        </div>
    </div>
</body>
</html>
