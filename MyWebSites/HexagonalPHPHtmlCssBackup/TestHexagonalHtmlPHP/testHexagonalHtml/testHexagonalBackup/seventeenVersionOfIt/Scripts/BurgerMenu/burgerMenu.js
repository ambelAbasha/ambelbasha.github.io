let timer = null;
let countdownTimer = null;

function toggleMenu() {
    const menu = document.getElementById("menu");
    const burgerMenu = document.getElementById("burger-menu");
    const items = Array.from(menu.querySelectorAll('a'));

    if (menu.classList.contains("expanded")) {
        hideMenuItemsReverse(items);
    } else {
        // Show items normally when expanding
        menu.classList.add("expanded");
        items.forEach((item, index) => {
            item.classList.remove('hidden');
            item.style.animation = `showItem 0.5s ${index * 0.5}s forwards`;
        });
        document.getElementById("countdown-message").classList.remove('hidden');
        startCountdown();
        startCollapseTimer();
    }
}

// Start Countdown and display the message
function startCountdown() {
    let countdownSeconds = 30;
    const countdownDisplay = document.getElementById("countdown-timer");
    const countdownMessage = document.getElementById("countdown-message");

    countdownDisplay.textContent = countdownSeconds;

    // Show the countdown message when the countdown starts
    countdownMessage.classList.add("visible");
    countdownMessage.classList.remove("hidden");

    countdownTimer = setInterval(() => {
        countdownSeconds--;
        countdownDisplay.textContent = countdownSeconds;

        if (countdownSeconds <= 0) {
            clearInterval(countdownTimer);
            hideMenuItemsReverse(Array.from(document.getElementById("menu").querySelectorAll('a')));
            // Optionally hide the message when the countdown finishes
            countdownMessage.classList.remove("visible");
            countdownMessage.classList.add("hidden");
        }
    }, 1000);
}

// Start the collapse timer after the specified delay
function startCollapseTimer() {
    clearTimeout(timer);

    timer = setTimeout(() => {
        hideMenuItemsReverse(Array.from(document.getElementById("menu").querySelectorAll('a')));
    }, 30000);
}


function hideMenuItemsReverse(items) {
    // Start from the last item and move upwards
    items.reverse().forEach((item, index) => {
        setTimeout(() => {
            item.style.animation = `hideItem 0.5s forwards`;
            setTimeout(() => {
                item.classList.add('hidden');
                if (index === items.length - 1) {
                    // Once the last item is hidden, collapse the menu
                    const menu = document.getElementById("menu");
                    menu.classList.remove("expanded");
                    document.getElementById("burger-menu").setAttribute("aria-expanded", "false");
                    menu.setAttribute("aria-hidden", "true");
                }
            }, 500); // Wait for the animation to finish before hiding
        }, index * 500); // Each item starts hiding after the previous one
    });

    clearInterval(countdownTimer);
    document.getElementById("countdown-message").classList.add('hidden');
}
