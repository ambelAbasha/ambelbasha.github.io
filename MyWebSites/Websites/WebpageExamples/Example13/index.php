<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calling functions f1 & f2 (Part 'A')</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

    <div class="outer-container">
        <div class="container">
            <div class="functions-container">
                <!-- Button 1 with JavaScript function f1 -->
                <button id="btn-f1" class="function-button" onclick="callFunctionF1()">
                    Fibonacci Seq.
                </button>
                <div class="return-button">
                    <button onclick="window.location.href='../index.html'">Back</button>
                </div>
                <!-- Button 2 with JavaScript function f2 -->
                <button id="btn-f2" class="function-button" onclick="callFunctionF2()">
                    Golden Ratio
                </button>
            </div>
        </div>

         <!-- Result Container -->
         <div class="result-container" id="result-container">
            <h3>Function Result:</h3>
            <div id="result-content">Click a button to see the result here.</div>
        </div>

        <!-- Explanation Container -->
        <div class="explanation-container" id="explanation-container">
            <h3>Explanation:</h3>
            <div id="explanation-content">Click a button to see the explanation here.</div>
        </div>

        <!-- Modal for Button Press Feedback -->
        <div id="modal" class="modal">
            <div class="modal-content" id="modal-content">
                <p id="modal-message"></p>
            </div>
        </div>
    </div>

    <script>
        // JavaScript function to calculate Fibonacci sequence (Function f1)
        function f1() {
            const fib = [0, 1];  // Initializing the first two Fibonacci numbers

            // Calculate the next Fibonacci numbers up to 30
            for (let i = 2; i < 30; i++) {
                fib.push(fib[i - 1] + fib[i - 2]);
            }

            return fib;
        }

        // JavaScript function to calculate Golden Ratio approximations (Function f2)
        function f2() {
            const fib = f1();  // Get the Fibonacci sequence from function f1
            const goldenRatios = [];

            // Calculate the Golden Ratio approximation (F(n) / F(n-1)) for each Fibonacci number
            for (let i = 2; i < fib.length; i++) {
                goldenRatios.push((fib[i] / fib[i - 1]).toFixed(5));  // Rounded to 5 decimal places
            }

            // Apply truncation logic to avoid too many repeated numbers
            const uniqueRatios = [];
            let lastValue = null;
            let repeatCount = 0;

            for (let ratio of goldenRatios) {
                if (ratio === lastValue) {
                    repeatCount++;
                } else {
                    repeatCount = 1;  // Reset repeat count for new unique value
                }

                if (repeatCount <= 2) {
                    uniqueRatios.push(ratio);  // Add to uniqueRatios if it's the first or second repetition
                }

                lastValue = ratio;
            }

            return uniqueRatios;
        }

        // Function to display Fibonacci sequence result
        function callFunctionF1() {
            // Display modal message for button 1
            showModal("Button One is pressed. Fibonacci Sequence Function.");

            // Display Fibonacci Explanation
            document.getElementById("explanation-content").innerHTML = `
                <h4>Fibonacci Sequence Explanation:</h4>
                <p>The Fibonacci sequence is a sequence of numbers in which each number (after the first two) is the sum of the two preceding ones. The sequence begins with 0 and 1. Mathematically, this is represented as:</p>
                <p><strong>F(0) = 0, F(1) = 1</strong></p>
                <p>For n ≥ 2, the sequence follows the recurrence relation: <strong>F(n) = F(n-1) + F(n-2)</strong></p>
                <p>The first few Fibonacci numbers are: 0, 1, 1, 2, 3, 5, 8, 13, 21, 34, ...</p>
                <p>In your function f1(), the process works as follows:</p>
                <ul>
                    <li><strong>Initialization:</strong> The sequence starts with the first two numbers, 0 and 1.</li>
                    <li><strong>Loop:</strong> The function calculates each subsequent Fibonacci number by adding the two previous ones.</li>
                    <li><strong>Returning the Result:</strong> The Fibonacci numbers are returned as a comma-separated string.</li>
                </ul>
            `;

            // Get the Fibonacci sequence and display it
            const fibonacciNumbers = f1().join(', ');
            document.getElementById("result-content").innerHTML = "Fibonacci Sequence (First 30 numbers): " + fibonacciNumbers;
        }

        // Function to display Golden Ratio approximation result
        function callFunctionF2() {
            // Display modal message for button 2
            showModal("Button Two is pressed. Golden Ratio Sequence.");

            // Display Golden Ratio Explanation
            document.getElementById("explanation-content").innerHTML = `
                <h4>Golden Ratio Explanation:</h4>
                <p>The Golden Ratio is an irrational number approximately equal to 1.61803. It is commonly represented as:</p>
                <p><strong>φ = (1 + √5) / 2 ≈ 1.61803</strong></p>
                <p>This ratio is famous for its appearance in various areas of mathematics, art, architecture, and nature.</p>
                <p>In the context of the Fibonacci sequence, the Golden Ratio can be approximated by taking the ratio of two consecutive Fibonacci numbers. As the Fibonacci numbers grow larger, this ratio converges towards the Golden Ratio. For example:</p>
                <ul>
                    <li>F(2)/F(1) = 1/1 = 1</li>
                    <li>F(3)/F(2) = 2/1 = 2</li>
                    <li>F(4)/F(3) = 3/2 = 1.5</li>
                    <li>F(29)/F(28) ≈ 514229/317811 ≈ 1.61803</li>
                </ul>
                <p>As the Fibonacci numbers grow, the ratios approach 1.61803, the Golden Ratio.</p>
            `;

            // Get the Golden Ratio approximations and display them
            const goldenRatios = f2().join(', ');
            document.getElementById("result-content").innerHTML = "Golden Ratio Approximation (Fibonacci Ratios): " + goldenRatios;
        }

        // Function to display the modal and hide it after 1.5 seconds
        function showModal(message) {
            document.getElementById("modal-message").innerText = message;
            document.getElementById("modal").style.display = "flex";

            // Set timeout to close the modal after 1.5 seconds
            setTimeout(function() {
                document.getElementById("modal").style.display = "none";
            }, 1500);  // 1.5 second delay on displaying results 
        }
    </script>
</body>
</html>
