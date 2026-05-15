import os
import fitz  # PyMuPDF
import pytesseract  # OCR tool
from PIL import Image, ImageEnhance, ImageOps
from googletrans import Translator
import logging
import sys
import cv2  # OpenCV for preprocessing
import numpy as np  # NumPy for array manipulations

# Set up logging
logging.basicConfig(filename='pdf_translation.log', level=logging.INFO, encoding='utf-8')

# Setup paths and translator
PDF_DIRECTORY = "C:\\xampp\\htdocs\\WebPages\\MyWebSites\\CyberSecurity\\Pdf's"
TRANSLATOR = Translator()
TARGET_LANGUAGE = 'en'  # English or change it to your target language

def safe_print(*args, **kwargs):
    """ A safe print function to handle Unicode errors. """
    try:
        print(*args, **kwargs)
    except UnicodeEncodeError:
        logging.error("Failed to print: %s", args)

def preprocess_image_for_ocr(image):
    """ Preprocess image to improve OCR accuracy: binarize, enhance contrast, denoise. """
    try:
        # Convert to grayscale
        gray = ImageOps.grayscale(image)

        # Enhance contrast
        enhancer = ImageEnhance.Contrast(gray)
        enhanced_img = enhancer.enhance(2)  # Increase contrast level

        # Convert to OpenCV format
        cv_img = cv2.cvtColor(np.array(enhanced_img), cv2.COLOR_RGB2BGR)

        # Denoise the image using OpenCV
        denoised_img = cv2.fastNlMeansDenoising(cv_img, None, 30, 7, 21)

        # Convert back to PIL format
        processed_img = Image.fromarray(cv2.cvtColor(denoised_img, cv2.COLOR_BGR2RGB))

        return processed_img

    except Exception as e:
        logging.error(f"Image preprocessing failed: {e}")
        return image

def extract_text_with_ocr(page):
    """ Extract text from a PDF page using OCR. """
    try:
        pix = page.get_pixmap(dpi=300)  # Set a higher DPI for better resolution
        img = Image.frombytes("RGB", [pix.width, pix.height], pix.samples)

        # Preprocess image for OCR
        processed_img = preprocess_image_for_ocr(img)

        # OCR text extraction
        text = pytesseract.image_to_string(processed_img, config='--psm 6')  # PSM 6 is for block of text
        return text
    except Exception as e:
        logging.error(f"OCR extraction failed: {e}")
        return ""

def translate_large_text(full_text):
    """Translate large text by splitting it into smaller chunks."""
    translated_chunks = []
    chunk_size = 4000  # Define the maximum length per chunk

    for i in range(0, len(full_text), chunk_size):
        chunk = full_text[i:i + chunk_size]
        try:
            translated_result = TRANSLATOR.translate(chunk, dest=TARGET_LANGUAGE)
            if translated_result and hasattr(translated_result, 'text'):
                translated_chunks.append(translated_result.text)
        except Exception as e:
            logging.error(f"Translation error for a chunk: {e}")
    
    return '\n'.join(translated_chunks)

def save_text_to_pdf(translated_text, output_path):
    """ Save translated text to a new PDF page-by-page. """
    try:
        if translated_text.strip():
            new_doc = fitz.open()

            # Split translated text into chunks for separate pages
            text_chunks = translated_text.split("\n\n")  # Split into paragraphs for better page handling

            for chunk in text_chunks:
                new_page = new_doc.new_page()
                # Inserting translated text directly into new page
                new_page.insert_text((72, 72), chunk, fontsize=12)  # Position the text and set font size

            # Save the PDF
            new_doc.save(output_path)
            new_doc.close()
            return True
        else:
            safe_print(f"No valid text to save to PDF for {output_path}")
            return False
    except Exception as e:
        logging.error(f"Failed to save PDF: {e}")
        return False

# Loop through PDF files in directory
for filename in os.listdir(PDF_DIRECTORY):
    if filename.endswith(".pdf") and not filename.startswith("._"):
        pdf_path = os.path.join(PDF_DIRECTORY, filename)
        safe_print(f"Processing: {pdf_path}")

        try:
            doc = fitz.open(pdf_path)
            full_text = ""

            # Extract text from each page
            for page in doc:
                page_text = page.get_text()

                if page_text.strip():
                    full_text += page_text
                else:
                    safe_print(f"No text extracted from page {page.number + 1}, attempting OCR.")
                    ocr_text = extract_text_with_ocr(page)
                    full_text += ocr_text

            doc.close()

            if full_text.strip():
                safe_print(f"Extracted text from {filename}: {full_text[:500]}...")  # Show first 500 characters

                # Translate extracted text
                try:
                    translated_text = translate_large_text(full_text) if len(full_text) > 5000 else TRANSLATOR.translate(full_text, dest=TARGET_LANGUAGE).text

                    if translated_text.strip():
                        new_pdf_path = os.path.join(PDF_DIRECTORY, f"translated_{filename}")
                        success = save_text_to_pdf(translated_text, new_pdf_path)

                        if success:
                            safe_print(f"Translated {filename} and saved as {new_pdf_path}")
                        else:
                            safe_print(f"Failed to save translated PDF for {filename}")
                    else:
                        safe_print(f"Translation failed or resulted in empty text for {filename}.")
                except Exception as e:
                    logging.error(f"Translation error for {filename}: {e}")
            else:
                safe_print(f"No text extracted from {pdf_path}, skipping translation.")

        except Exception as e:
            safe_print(f"Failed to process {pdf_path}: {e}")
            logging.error(f"Error processing {pdf_path}: {e}")

safe_print("Translation process completed. Check log for details.")
