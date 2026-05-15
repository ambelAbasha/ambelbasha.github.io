document.addEventListener("DOMContentLoaded", function () {
    // Base offset — makes the counter look realistic from day one
    const BASE_VISITS = 120;

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

    const displayTotal = totalVisits + BASE_VISITS;
    const displayUnique = uniqueVisits + Math.floor(BASE_VISITS * 0.6);

    // --- Get our label and number spans ---
    const totalVisitsLabel  = document.querySelector("#total-visits .label");
    const totalVisitsDigits = document.querySelector("#total-visits .digits");
    const uniqueVisitsLabel  = document.querySelector("#unique-visits .label");
    const uniqueVisitsDigits = document.querySelector("#unique-visits .digits");

    const asciiSet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";

    function animateLabelText(span, targetText, duration, callback) {
        let startTime = null;
        const letters = targetText.split('');
        const total = letters.length;
        const delay = duration / total;

        function step(ts) {
            if (!startTime) startTime = ts;
            const elapsed = ts - startTime;
            let out = "";
            for (let i = 0; i < total; i++) {
                if (elapsed > (i * delay + delay * 0.8)) {
                    out += letters[i];
                } else if (elapsed > i * delay) {
                    out += (letters[i] === " " ? " " : asciiSet.charAt(Math.floor(Math.random() * asciiSet.length)));
                } else {
                    out += " ";
                }
            }
            span.textContent = out;
            if (elapsed < duration) {
                requestAnimationFrame(step);
            } else {
                span.textContent = targetText;
                if (callback) callback();
            }
        }
        requestAnimationFrame(step);
    }

    function animateDigit(digitSpan, target, duration, callback) {
        let current = 0;
        const steps = target + 1;
        const stepDur = duration / steps;
        const interval = setInterval(() => {
            digitSpan.textContent = current;
            if (current >= target) {
                clearInterval(interval);
                if (callback) callback();
            }
            current++;
        }, stepDur);
    }

    function animateDigits(container, finalNumber, duration, callback) {
        container.innerHTML = "";
        const digits = String(finalNumber).split("");
        let done = 0;
        digits.forEach(d => {
            const span = document.createElement("span");
            span.textContent = "0";
            container.appendChild(span);
            animateDigit(span, parseInt(d, 10), duration, () => {
                done++;
                if (done === digits.length && callback) callback();
            });
        });
    }

    function showVisitAnimation(value, targetElement) {
        const el = document.createElement("span");
        el.classList.add("visit-animation");
        el.textContent = `+${value}`;
        targetElement.appendChild(el);
        setTimeout(() => el.remove(), 1000);
    }

    // Start animations after a short delay — labels stay visible permanently
    setTimeout(() => {
        animateLabelText(totalVisitsLabel, "Total visits:", 900, () => {
            animateDigits(totalVisitsDigits, displayTotal, 1200, () => {
                showVisitAnimation(1, document.getElementById("total-visits"));
            });
        });

        animateLabelText(uniqueVisitsLabel, "Unique visits:", 900, () => {
            animateDigits(uniqueVisitsDigits, displayUnique, 1200, () => {
                showVisitAnimation(1, document.getElementById("unique-visits"));
            });
        });
    }, 800);
});
