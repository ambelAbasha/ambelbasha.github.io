// Scroll Reveal Effect
document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll(".section");

    function revealSections() {
        sections.forEach(section => {
            const rect = section.getBoundingClientRect();
            if (rect.top < window.innerHeight * 0.85) {
                section.classList.add("visible");
            }
        });
    }

    window.addEventListener("scroll", revealSections);
    revealSections(); // Run once to check initial state
});

// Counter Animation
document.addEventListener("DOMContentLoaded", function () {
    const counter = document.querySelector(".counter");
    if (counter) {
        let count = 0;
        const target = parseInt(counter.dataset.target);
        const speed = 100; // Adjust speed

        function updateCounter() {
            if (count < target) {
                count += Math.ceil(target / speed);
                counter.textContent = count;
                setTimeout(updateCounter, 30);
            } else {
                counter.textContent = target; // Ensure exact count
            }
        }

        counter.style.opacity = "1";
        updateCounter();
    }
});
