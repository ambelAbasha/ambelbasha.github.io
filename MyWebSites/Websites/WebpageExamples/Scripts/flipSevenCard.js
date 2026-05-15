document.addEventListener('DOMContentLoaded', () => {
    // Get the sidebar list items and the flip card elements
    const sidebarItems = document.querySelectorAll('.sidebar li');
    const flipCardText = document.getElementById('flip-card-text');
    const flipCard = document.querySelector('.flip-card');

// Explanation text for each example
const explanations = {
    1: `Example 1: "Table of Computer Peripherals - Sales" Web Application
    This small but effective web application manages and displays sales data for computer peripherals using HTML, PHP, MySQL, and CSS. The core of the application focuses on secure database interaction, utilizing PHP prepared statements to prevent SQL injection attacks—an essential practice for maintaining web application security.
    On the front-end, the application features a minimalistic, user-friendly table that dynamically loads sales data, with the items' quantities and prices displayed clearly. A color-coded system is employed to help users easily identify the stock levels of each item. The color-coding system works as follows:
    - **Dark Grey**: Stock ≥ 100 (Plentiful stock)
    - **Green**: Stock ≥ 50 (Healthy stock)
    - **Yellow-Orange**: Stock ≥ 15 (Moderate stock)
    - **Red**: Stock ≤ 14 (Low stock)
    This color system allows users to quickly assess the status of inventory, helping to identify running-out or low-stock items.
    Additionally, MySQL triggers are used to automatically log changes such as inserts, updates, and deletions to the sales data, ensuring the integrity and consistency of the database. The application is also responsive, providing a seamless user experience across both mobile and desktop devices. Proper error handling and database connectivity ensure reliability and smooth operation of the application.`,
    
    2: `Example 2: This web page presents a User Input Form that allows users to enter a number between 1 and 100, with the added functionality of dynamic message generation and pagination. Upon submission, the form processes the input and displays a table with messages like “Welcome to PHP X” based on the number provided. The form validates the input, ensuring that the entered number does not exceed 100; if the value is invalid, an error message is displayed for 5 seconds, after which the input field is cleared automatically.
    
    In addition to the basic form functionality, the page incorporates pagination, breaking the output into smaller chunks, showing 5 messages per page. This ensures easy navigation when large numbers are entered. The pagination buttons dynamically update to show relevant page numbers, with "Next" and "Prev" buttons for easy navigation. This combination of form submission, dynamic message display, validation, and pagination creates an interactive and user-friendly experience for the visitor.
    
    The responsive CSS ensures that the layout is optimized across different screen sizes, providing a clean and structured appearance. The styling includes basic input form designs, message table layouts, and pagination buttons with hover effects, contributing to a visually appealing and smooth interaction for the user.`,
    
    3: `Example 3: Understanding Variable Types in PHP
    
    This web page demonstrates how PHP handles different variable types through a simple table. It showcases various variable assignments, including integers, strings, booleans, and arrays. For each type, the value of the variable is displayed alongside its type using the gettype() function in PHP.
    
    The example starts by assigning an integer to the variable $testing and then prints the type. It follows up with a string, a floating-point number, a boolean value, and an array, each time displaying the variable's value and its corresponding type.
    
    This example helps illustrate PHP's flexibility with variable types and the importance of knowing how to manipulate and display different types of data within a PHP script. It serves as a basic, yet informative, introduction to PHP’s variable handling.
    
    The page is a part of Ambel Basha's educational portfolio, designed to give visitors an interactive and informative example of PHP programming concepts.`,
    
    4: `Example 4: Demonstrating Number Formatting in PHP
    
    This web page showcases how to format numbers in various styles using PHP. The example uses the \`printf\` function to display a given number in different formats such as decimal, binary, octal, floating-point, string, and hexadecimal (both lowercase and uppercase). 
    
    By utilizing the \`printf\` function, the page demonstrates PHP's versatility in handling and displaying numeric data in a wide range of formats. The code makes it easy to see how a single numeric value can be represented in different numeral systems, which is especially useful for web developers who need to display numbers in multiple formats, such as for financial reports, binary data, or hexadecimal color codes.
    
    This example serves as a practical introduction to PHP's built-in number formatting capabilities and helps users understand how to manipulate and present numeric data in a clear and structured manner.
    
    The page is part of Ambel Basha's educational portfolio, aimed at providing interactive examples of common PHP programming concepts.`,
    
    5: `Example 5: Email and Domain Checker
    
    This web page demonstrates how to extract and manipulate email and domain parts using PHP string functions. The page showcases two primary functionalities:
    
    Email Parsing: It extracts the username and domain from a given email address (e.g., cs121105@uniwa.gr) and displays them separately with distinct styling. The position of the @ symbol in the email is also displayed.
    
    Domain Parsing: It works with a domain address (e.g., www.cs.uniwa.gr), extracting the base domain and top-level domain (TLD) .gr, and displaying their index position within the string.
    
    This example illustrates the use of PHP functions like strpos() for finding specific characters in a string, and explode() to split strings based on delimiters. It’s a useful resource for understanding how to work with strings, particularly for tasks like email validation or domain analysis.
    
    This page is part of Ambel Basha’s educational portfolio, providing an interactive and informative example of PHP string handling techniques.`,
    
    6: `Example 6:  This example demonstrates how to tokenize a string into individual words using PHP’s strtok() function, and then display each token along with its index in a table. The string used in this example is a typical query string that contains URL parameters. The key features of this example include:
    
    String Tokenization:
    The given string "hl=en&ie=UTF-8&q=php+development+books&btnG=Google+Search" is tokenized using PHP's strtok() function, which breaks the string into smaller substrings based on delimiters such as spaces, newline characters (\\n), and the ampersand (&) symbol. This approach is useful for breaking down URL query parameters or any structured text into manageable parts.
    
    Table Display of Tokens:
    Each token is displayed in a table with two columns:
      - Word Index: The index of the token in the string (e.g., the first token, second token, etc.).
      - Word: The actual token itself, which is a part of the original string.
    
    Loop for Tokenizing and Displaying Results:
    The PHP script loops through the string, extracts each token, and displays it in the table. It also keeps track of the index of each token, which is incremented on each loop iteration.`,
    
    7: `Example 7: Dynamic Date and Time Display for Athens, Greece\n\nThis example demonstrates how to display the current date and time for Athens, Greece, with real-time updates every second. The page is optimized for mobile devices and other screen sizes using responsive design principles.\n\nKey Features:\n\n- **Dynamic Date and Time:** The JavaScript code fetches the current date and time and formats it into a readable string (DD-MM-YYYY, HH:MM:SS). It updates the time every second to provide a real-time display.\n\n- **Timezone:** The time displayed corresponds to Athens, Greece, which is 3 hours ahead of Greenwich Mean Time (GMT+3).\n\n- **SEO Optimization:** The page includes metadata like description and keywords for search engine optimization, ensuring better visibility and categorization.\n\n- **Modern Layout:** The design uses external CSS to style the page, ensuring a clean and responsive layout. The time is displayed in a centered container, and the user can return to the main page using a back button.\n\n- **Interactivity:** JavaScript ensures the time remains up-to-date by updating the content every second, making the page interactive and dynamic.`,
    
    8: `Example 8: Dynamic Calendar with Month Navigation\n\nThis example demonstrates how to create a dynamic calendar using PHP, HTML, and CSS. 
    The calendar displays the current month and allows users to navigate between months using "Prev" and "Next" buttons. Key features of this example include:\n\n
    - Dynamic Date Calculation: The PHP code calculates the number of days in the selected month and adjusts the table layout accordingly. It also highlights the current day of the month.\n
    - Navigation Controls: Users can move between months using the "Prev" and "Next" buttons, which dynamically update the month and year in the URL.\n
    - CSS Styling: The calendar is styled with CSS to create a clean and user-friendly interface, including features like highlighted current date, weekday headers, and rounded corners for better visual appeal.\n
    - Responsive Design: The calendar adjusts for different screen sizes, ensuring a smooth experience across devices.\n\n
    This example showcases PHP's ability to manage dynamic content and handle date calculations, combined with HTML and CSS for a visually interactive calendar.`,
    
    9: `Example 9: Biggest, Smallest, and Average Numbers using PHP Functions

    This web page demonstrates how to calculate and display the biggest, smallest, and average numbers from a set of three numbers using PHP functions. The page includes:
    
    - **Biggest Number**: The function \`biggest()\` takes three numbers as input and returns the largest number.
    - **Smallest Number**: The function \`smallest()\` finds and returns the smallest number among the three inputs.
    - **Average**: The \`average()\` function calculates the average of the given numbers, ignoring any \`null\` values.
    
    The results for each set of numbers are dynamically displayed in a table format, with calculations for three different sets of values. If a number is \`null\`, it is excluded from the calculations.
    
    This example demonstrates basic PHP functions for performing mathematical operations and handling null values gracefully.`,
        
    10: 'Example 10: Recursion and String Reversal in PHP\n\nThis PHP code demonstrates two approaches to reversing a string: using recursion and iteration. It features two distinct functions, `recursion()` and `repeat()`, both of which process a string and output its characters in reverse order.\n\nRecursion Example (recursion() function):\nThis function takes a string as input and uses recursion to reverse it. It extracts the last character of the string using substr(), prints it, and then calls itself with the rest of the string (excluding the last character). The process continues recursively until the string is empty, effectively reversing the order of the characters.\n\nIteration Example (repeat() function):\nThis function uses a for loop to iterate over the string in reverse order, from the last character to the first. For each character, substr() is used to extract it, and it is printed one by one, thus reversing the string.\n\nFunctionality and Output:\nThe page displays examples for the words "Programming" and "Accessing". The code reverses these words using both the `recursion()` and `repeat()` functions, showing the results for each in the output.',
    11: `Example 11: User Input Form with Validation and Pagination

    This example demonstrates how to create a user input form with validation, ensuring that the user enters a number between 1 and 100. When the user submits the form, the page displays the result and paginated output based on the input. Key features include:
    
    - **User Input Validation**: The form validates that the user input is a number between 1 and 100. If the input is invalid (not a number, empty, or greater than 100), an error message is displayed.
    - **Pagination**: The result is paginated, with 5 items per page. The page displays pagination buttons to navigate through the result.
    - **PHP and JavaScript Integration**: PHP is used to handle form submission and validate input, while JavaScript dynamically displays the result and generates the pagination controls based on the number input.
    - **Responsive Layout**: The layout is styled using CSS to ensure a clean and user-friendly design, with responsive features for different screen sizes.
    
    This example demonstrates how to combine HTML, CSS, PHP, and JavaScript to create an interactive user form with validation and pagination.`,
    12: 'Example 12: Matrix Generated by Nested \'for\' Loop\n\nThis example demonstrates how to generate a 12x12 matrix using a nested for loop in PHP. The matrix consists of 12 rows and 12 columns, where each cell displays a sequential number starting from 1. The key features of this example include:\n\n- PHP Nested Loops: The outer loop iterates through 12 rows, while the inner loop iterates through 12 columns for each row. This results in the creation of a 12x12 grid of table cells.\n- Counter Incrementation: A counter is initialized to 1 and incremented after each iteration, ensuring each cell contains a unique, sequential number.\n- Responsive Design: The page layout is styled with CSS to ensure that the matrix is displayed cleanly, regardless of screen size, with adjustments for smaller screens.\n- Return Button: A button is included to allow users to navigate back to the homepage easily.\n\nThis example showcases the use of PHP for generating dynamic content on a webpage, with a practical demonstration of working with loops to create structured data within an HTML table.',
    13: 'Example 13: Fibonacci Sequence and Golden Ratio Demonstration\n\nThis HTML document combines PHP and JavaScript to demonstrate the Fibonacci sequence and the Golden Ratio, mathematical concepts used in art, architecture, and nature. The page provides an interactive way for users to explore these concepts through two buttons that trigger PHP functions in the backend and display results on the frontend. The user experience is enhanced with feedback using modals.\n\nKey Features:\n- HTML Structure: The document includes meta tags for mobile responsiveness, ensuring it works across screen sizes. It links to an external styles.css file for custom styling, keeping the HTML organized. The page features two buttons:\n - Fibonacci Sequence (Function f1): This button triggers the backend function to calculate and display the first 30 Fibonacci numbers in a comma-separated format.\n - Golden Ratio (Function f2): This button calculates Fibonacci ratios and approximates the Golden Ratio, showing results rounded to five decimal places.\n- Results Display: The results from the PHP functions are shown in a Result Container. The Explanation Container provides descriptions to help users understand the numbers. A Modal briefly appears to provide feedback on button presses, enhancing interactivity.\n\nPHP Functions (Backend):\n- f1: Computes the first 30 Fibonacci numbers and returns them in a comma-separated string.\n- f2: Computes Fibonacci ratios and approximates the Golden Ratio. Both functions are triggered via URL parameters (?func=f1 or ?func=f2) and display results on the page.\n\nJavaScript Functions (Frontend):\n- callFunctionF1: Displays the Fibonacci sequence with an explanation.\n- callFunctionF2: Displays Golden Ratio approximations and explains how the ratios converge. Both trigger the showModal function, briefly displaying feedback.\n\nCSS Reference: The CSS handles layout, button styling, modal visibility, and ensures the page is responsive.',
    14: 'Example 14: This example demonstrates a form where users can input a country or capital to search for corresponding information using PHP. The HTML structure includes a form with a textarea for input and a select dropdown to choose between searching for a country or a capital. Upon submission, the form sends data to result.php via the POST method. The PHP script sanitizes the input using htmlspecialchars() to prevent security risks and validates that only alphabetic characters are allowed through preg_match(). Based on the user’s selection, the PHP logic checks the input against an array of countries and their capitals. If a match is found, the corresponding capital or country is displayed; if no match is found, a “Not found” message is shown. The user is then redirected to result_display.php, where the result is presented. For the output, CSS styles like result-success and result-error are applied to distinguish success and error messages visually. The form layout uses classes such as form-header, textarea, and submit-wrapper to improve readability and the user experience. An example use case: when "Greece" is entered as a country, the output would display "Athens is the capital city of Greece." with dynamic success/error styling.',
    15: 'Example 15: Description for the fifteenth example.',
    16: 'Example 16: Description for the sixteenth example.',
    17: 'Example 17: Description for the seventeenth example.'
};

    // Function to toggle the "Read More" effect
    const toggleReadMore = () => {
        flipCardText.classList.toggle('read-more');
        const currentText = flipCardText.innerHTML;
        
        if (flipCardText.classList.contains('read-more')) {
            flipCardText.innerHTML = currentText + '<span class="read-more-button">Read Less</span>';
        } else {
            flipCardText.innerHTML = explanations[1]; // Reset the text to the default explanation
        }
    };

    // Hover event listener for each sidebar item
    sidebarItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            // Get the example number from the text of the sidebar link
            const exampleNumber = item.querySelector('a').textContent.split(' ')[1]; 
            // Set the explanation text dynamically in the flip card
            flipCardText.innerHTML = explanations[exampleNumber];

            // Add "Read More" button
            const readMoreButton = document.createElement('button');
            readMoreButton.textContent = 'Read More';
            readMoreButton.classList.add('read-more-button');
            readMoreButton.onclick = toggleReadMore;
            flipCardText.appendChild(readMoreButton);

            // Trigger the flip effect on the card
            flipCard.classList.add('flipped');
        });

        item.addEventListener('mouseleave', () => {
            // Delay before flipping the card back
            setTimeout(() => {
                // Remove the flip effect after 15 seconds
                flipCard.classList.remove('flipped');
                // Clear the explanation text and remove the "Read More" button after the card flip
                flipCardText.innerHTML = '';
            }, 15000); // 15 seconds delay
        });
    });
});
