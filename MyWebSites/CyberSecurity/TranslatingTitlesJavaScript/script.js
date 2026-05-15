function toggleLanguage() {
    var pageTitle = document.getElementById('pageTitle');
    var pdfTitles = document.querySelectorAll('.pdf-block h2');

    // Toggle page title and PDF block titles between languages
    if (pageTitle.textContent === 'Ιστοσελίδα Κυβερνοασφάλειας') {
        pageTitle.textContent = 'Cyber Security Webpage';
        pdfTitles.forEach((title, index) => {
            title.textContent = translateToEnglish(index);
        });
    } else {
        pageTitle.textContent = 'Ιστοσελίδα Κυβερνοασφάλειας';
        pdfTitles.forEach((title, index) => {
            title.textContent = translateToGreek(index);
        });
    }

    adjustPdfHeight("pdf-block", [9, 14, 6, 4]); // Adjusting height for PDF blocks
}

// Function to translate PDF block titles from Greek to English
function translateToEnglish(index) {
    var translatedTitles = [
        'Report 1 - Known Vulnerabilities',
        'Report 2 - Cryptography',
        'Report 3 - Password Detection',
        'Report 4 - Registry Overflow',
        'Panoptis 2018 - Linux Forensics'
    ];
    return translatedTitles[index];
}

// Function to translate PDF block titles from English to Greek
function translateToGreek(index) {
    var translatedTitles = [
        'Αναφορά 1 - Γνωστές Ευπάθειες',
        'Αναφορά 2 - Κρυπτογραφία',
        'Αναφορά 3 - Ανίχνευση Συνθηματικών',
        'Αναφορά 4 - Υπερχείλιση Καταχωρητή',
        'Πανόπτης 2018 - Linux Forensics'
    ];
    return translatedTitles[index];
}
