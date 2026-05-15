// Function to toggle language between Greek and English
function toggleLanguage() {
    var pageTitle = document.getElementById('pageTitle');
    var pdfTitles = document.querySelectorAll('.pdf-block h2');
    var pdfObjects = document.querySelectorAll('.pdf-block object');
    var body = document.body;
    var modal = document.getElementById('languageModal');
    var modalMessage = document.getElementById('modalMessage');
    var languageFlag = document.getElementById('languageFlag'); // Flag image element

    // Get current language state stored in data-language attribute
    var currentLanguage = body.getAttribute('data-language') || 'en';  // Default to 'en' if not set
    console.log("Current Language:", currentLanguage);

    // Determine new language and switch
    var newLanguage = currentLanguage === 'en' ? 'el' : 'en';
    var oldLanguage = currentLanguage;

    // Set new language in body attribute
    body.setAttribute('data-language', newLanguage);

    // Set title and button text based on new language
    if (newLanguage === 'en') {
        pageTitle.textContent = 'Cyber Security Webpage';  // Title in English
        languageFlag.src = 'Images/greekFlag.png';  // Change flag to English
    } else {
        pageTitle.textContent = 'Ιστοσελίδα Κυβερνοασφάλειας';  // Title in Greek
        languageFlag.src = 'Images/englishFlag.png';  // Change flag to Greek
    }

    // Log the flag src for debugging
    console.log('Updated flag src:', languageFlag.src);

    // Update PDF titles and links based on new language
    pdfTitles.forEach((title, index) => {
        var translatedTitle = translatePDF(title.textContent, oldLanguage, newLanguage);
        title.textContent = translatedTitle;
        updatePdfLink(pdfObjects[index], translatedTitle, newLanguage);
    });

    // Show the modal with language change message
    showLanguageModal(oldLanguage, newLanguage);
}

// Function to update the PDF link based on language toggle
function updatePdfLink(pdfObject, title, lang) {
    var pdfLink;

    // Try to fetch the PDF link in the selected language
    pdfLink = pdfLinks[lang][title];

    // If PDF is missing in the selected language, fallback to the other language
    if (!pdfLink) {
        pdfLink = pdfLinks[lang === 'en' ? 'el' : 'en'][title];  // Fallback to the opposite language
    }

    console.log("Updating PDF link for title:", title, "to", pdfLink);

    // If a valid PDF link is found
    if (pdfLink) {
        const encodedPdfLink = encodeURI(pdfLink); // Encode the URL
        const timestamp = new Date().getTime(); // Append timestamp to avoid caching
        const newPdfLink = `${encodedPdfLink}?t=${timestamp}`;

        // Temporarily reset the data attribute to force reload
        pdfObject.setAttribute('data', ''); // Reset the data attribute to force reloading
        pdfObject.setAttribute('data', newPdfLink); // Set the new URL with timestamp

        console.log("New PDF link set:", newPdfLink);

        pdfObject.onload = function () {
            console.log("PDF loaded successfully.");
        };

        pdfObject.onerror = function () {
            console.error("Error loading PDF.");
        };
    } else {
        console.error("No valid PDF link found for title:", title);
    }
}

// Function to translate PDF titles
function translatePDF(text, sourceLang, targetLang) {
    console.log(`Translating text: "${text}" from ${sourceLang} to ${targetLang}`);
    if (sourceLang === 'el' && targetLang === 'en') {
        return translateToEnglish(text);
    } else if (sourceLang === 'en' && targetLang === 'el') {
        return translateToGreek(text);
    } else {
        return text; // Return the same if no translation is needed
    }
}

// Function to translate PDF block titles from Greek to English
function translateToEnglish(title) {
    const translatedTitles = {
        'Γνωστές Ευπάθειες': 'Known Vulnerabilities',
        'Κρυπτογραφία': 'Cryptography',
        'Ανίχνευση Συνθηματικών': 'Password Detection',
        'Υπερχείλιση Καταχωρητή': 'Registry Overflow',
        'Πανόπτης 2018 - Linux Forensics': 'Panoptis 2018 - Linux Forensics'
    };

    return translatedTitles[title] || title; // Return the translated title or original if not found
}

// Function to translate PDF block titles from English to Greek
function translateToGreek(title) {
    const translatedTitles = {
        'Known Vulnerabilities': 'Γνωστές Ευπάθειες',
        'Cryptography': 'Κρυπτογραφία',
        'Password Detection': 'Ανίχνευση Συνθηματικών',
        'Registry Overflow': 'Υπερχείλιση Καταχωρητή',
        'Panoptis 2018 - Linux Forensics': 'Πανόπτης 2018 - Linux Forensics'
    };

    return translatedTitles[title] || title; // Return the translated title or original if not found
}

// Show the language change modal
function showLanguageModal(oldLang, newLang) {
    const modal = document.getElementById('languageModal');
    const modalMessage = document.getElementById('modalMessage');

    if (newLang === 'en') {
        // English message
        const enOld = oldLang === 'en' ? 'English' : 'Greek';
        const enNew = 'English';
        modalMessage.textContent = `Language has changed from ${enOld} to ${enNew}.`;
    } else {
        // Greek message
        const grOld = oldLang === 'en' ? 'Αγγλικά' : 'Ελληνικά';
        const grNew = 'Ελληνικά';
        modalMessage.textContent = `Η γλώσσα άλλαξε από ${grOld} σε ${grNew}.`;
    }

    modal.style.display = 'block';

    setTimeout(() => {
        modal.style.display = 'none';
    }, 3000); // 3 seconds
}

// Dictionary to map titles to PDF links for both languages
const pdfLinks = {
    en: {
        'Known Vulnerabilities': "Pdf's/Report1-KnownVulnerabilities.pdf",
        'Cryptography': "Pdf's/Report2-Cryptography.pdf",
        'Password Detection': "Pdf's/Report3-PasswordDetection.pdf",
        'Registry Overflow': "Pdf's/Report4-RegistryOverflow.pdf",
        'Panoptis 2018 - Linux Forensics': "Pdf's/Report5-LinuxForensics.pdf"
    },
    el: {
        'Γνωστές Ευπάθειες': "Pdf's/Αναφορά1ΓνωστεςΕυπάθειες.pdf",
        'Κρυπτογραφία': "Pdf's/Αναφορά2Κρυπτογραφία.pdf",
        'Ανίχνευση Συνθηματικών': "Pdf's/Αναφορά3ΑνίχνευσηΣυνθηματικών.pdf",
        'Υπερχείλιση Καταχωρητή': "Pdf's/Αναφορά4ΥπερχείλισηΚαταχωρητή.pdf",
        'Πανόπτης 2018 - Linux Forensics': "Pdf's/Πανοπτής2018LinuxForensis.pdf"
    }
};
