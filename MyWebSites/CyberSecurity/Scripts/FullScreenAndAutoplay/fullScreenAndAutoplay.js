let autoScrollInterval = null;

// Track which PDF block is currently visible (optional: can be enhanced)
function getVisiblePdfObject() {
    const pdfs = document.querySelectorAll("object[id^='pdfObject']");
    for (const pdf of pdfs) {
        const rect = pdf.getBoundingClientRect();
        if (rect.top >= 0 && rect.bottom <= window.innerHeight) {
            return pdf;
        }
    }
    return null;
}

function startAutoScroll(pdfObject) {
    if (!pdfObject) return;

    const scrollContainer = document.scrollingElement || document.documentElement;

    autoScrollInterval = setInterval(() => {
        scrollContainer.scrollBy({ top: 1, behavior: 'smooth' });
    }, 50); // adjust speed as needed
}

function stopAutoScroll() {
    clearInterval(autoScrollInterval);
    autoScrollInterval = null;
}

document.addEventListener("keydown", (event) => {
    const key = event.key.toUpperCase();

    // "F" for fullscreen
    if (key === "F") {
        const currentPdf = getVisiblePdfObject();
        if (currentPdf) {
            toggleFullscreen(currentPdf.id);
        }
    }

    // "A" for autoplay scroll
    if (key === "A") {
        if (autoScrollInterval) {
            stopAutoScroll();
        } else {
            const currentPdf = getVisiblePdfObject();
            startAutoScroll(currentPdf);
        }
    }
});

