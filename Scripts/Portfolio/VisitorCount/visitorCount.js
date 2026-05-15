    document.addEventListener("DOMContentLoaded", function () {
        // --- Retrieve and update visit counts ---
        if (!localStorage.getItem("totalVisits")) localStorage.setItem("totalVisits", 0);
        let totalVisits = parseInt(localStorage.getItem("totalVisits"), 10) + 1;
        localStorage.setItem("totalVisits", totalVisits);
    
        let uniqueVisits = parseInt(localStorage.getItem("uniqueVisits") || 0, 10);
        if (!sessionStorage.getItem("hasVisited")) {
            sessionStorage.setItem("hasVisited", "true");
            uniqueVisits++;
            localStorage.setItem("uniqueVisits", uniqueVisits);
        }
    
        // --- Get our label and number spans ---
        const totalVisitsLabel = document.querySelector("#total-visits .label");
        const totalVisitsDigits = document.querySelector("#total-visits .digits");
        const uniqueVisitsLabel = document.querySelector("#unique-visits .label");
        const uniqueVisitsDigits = document.querySelector("#unique-visits .digits");
    
        // --- Define an ASCII set for label scramble ---
        const asciiSet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";
    
        // Animate the label text one character at a time.
        function animateLabelText(span, targetText, duration, callback) {
            let startTime = null;
            const letters = targetText.split('');
            const totalLetters = letters.length;
            const delayPerLetter = duration / totalLetters;
          
            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                let elapsed = timestamp - startTime;
                let output = "";
                for (let i = 0; i < totalLetters; i++) {
                    if (elapsed > (i * delayPerLetter + delayPerLetter * 0.8)) {
                        output += letters[i];
                    } else if (elapsed > i * delayPerLetter) {
                        output += (letters[i] === " " ? " " : asciiSet.charAt(Math.floor(Math.random() * asciiSet.length)));
                    } else {
                        output += " ";
                    }
                }
                span.textContent = output;
                if (elapsed < duration) {
                    requestAnimationFrame(step);
                } else {
                    span.textContent = targetText;
                    if (callback) callback();
                }
            }
            requestAnimationFrame(step);
        }
    
        // Animate a single digit from 0 to its target value.
        function animateDigit(digitSpan, target, duration, callback) {
            let current = 0;
            let steps = target + 1;
            let stepDuration = duration / steps;
          
            const interval = setInterval(() => {
                digitSpan.textContent = current;
                if (current >= target) {
                    clearInterval(interval);
                    if (callback) callback();
                }
                current++;
            }, stepDuration);
        }
    
        // Animate all digits of a multi-digit number.
        function animateDigits(container, finalNumber, duration, callback) {
            container.innerHTML = "";
            const digits = String(finalNumber).split("");
            let completedDigits = 0;
          
            digits.forEach((d) => {
                const span = document.createElement("span");
                span.textContent = "0";
                container.appendChild(span);
                animateDigit(span, parseInt(d, 10), duration, () => {
                    completedDigits++;
                    if (completedDigits === digits.length && callback) {
                        callback();
                    }
                });
            });
        }
    
        // Create a floating "+1" animation next to the counter.
        function showVisitAnimation(value, targetElement) {
            const animationSpan = document.createElement("span");
            animationSpan.classList.add("visit-animation");
            animationSpan.textContent = `+${value}`;
            targetElement.appendChild(animationSpan);
            setTimeout(() => {
                animationSpan.remove();
            }, 1000);
        }
    
        // Fade out a label using CSS transitions.
        function fadeOutLabel(labelElement) {
            labelElement.classList.add("fade-out");
            // Optionally, remove the element from the DOM after the transition completes.
            setTimeout(() => {
                labelElement.style.display = "none";
            }, 1000); // Match this to your CSS transition duration.
        }
    
        // Start animations after a short delay.
        setTimeout(() => {
            // Animate the label for Total Visits.
            animateLabelText(totalVisitsLabel, "Total visits:", 1200, () => {
                animateDigits(totalVisitsDigits, totalVisits, 1500, () => {
                    showVisitAnimation(1, document.getElementById("total-visits"));
                    // Fade out the label after finishing the number animation.
                    fadeOutLabel(totalVisitsLabel);
                });
            });
    
            // Animate the label for Unique Visits.
            animateLabelText(uniqueVisitsLabel, "Unique visits:", 1200, () => {
                animateDigits(uniqueVisitsDigits, uniqueVisits, 1500, () => {
                    showVisitAnimation(1, document.getElementById("unique-visits"));
                    fadeOutLabel(uniqueVisitsLabel);
                });
            });
        }, 1000);
    });
  