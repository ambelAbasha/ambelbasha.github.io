from flask import Flask, jsonify
import subprocess

app = Flask(__name__)

@app.route('/translate-pdfs')
def translate_pdfs():
    try:
        # Call your translatePdfs.py script
        result = subprocess.run(['python', 'translatePdfs.py'], capture_output=True, text=True)
        
        if result.returncode == 0:
            return jsonify({"message": "Translation successful!"}), 200
        else:
            return jsonify({"message": "Translation failed.", "error": result.stderr}), 500
    except Exception as e:
        return jsonify({"message": "An error occurred.", "error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
