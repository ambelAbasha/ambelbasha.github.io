document.addEventListener('DOMContentLoaded', () => {
    // Get the thresholds from the database and set them dynamically
    const thresholds = <?php echo json_encode($thresholds); ?>;

    // Set the initial value of each threshold input based on the database
    document.getElementById('threshold-100').value = thresholds.threshold_100;
    document.getElementById('threshold-50').value = thresholds.threshold_50;
    document.getElementById('threshold-15').value = thresholds.threshold_15;
    document.getElementById('threshold-14').value = thresholds.threshold_14;

    // Update the display of each threshold
    document.getElementById('threshold-100-display').textContent = thresholds.threshold_100;
    document.getElementById('threshold-50-display').textContent = thresholds.threshold_50;
    document.getElementById('threshold-15-display').textContent = thresholds.threshold_15;
    document.getElementById('threshold-14-display').textContent = thresholds.threshold_14;

    // Listen for input changes on the threshold ranges
    document.querySelectorAll('.threshold-range').forEach(range => {
        range.addEventListener('input', (event) => {
            const threshold = event.target.id.split('-')[1];  // e.g., "100"
            const value = event.target.value;

            // Update the threshold value on the page
            document.getElementById(`threshold-${threshold}-display`).textContent = value;

            // Make an AJAX POST request to update the threshold in the database
            fetch('updateThreshold.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'threshold': threshold,
                    'value': value
                }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optionally update some UI element to show success
                    console.log(`Threshold for ${threshold} updated successfully.`);
                } else {
                    console.error(`Failed to update threshold: ${data.message}`);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
