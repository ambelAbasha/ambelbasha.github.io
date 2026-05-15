    // Function to update the clock
    function updateClock() {
      const clockElement = document.getElementById('clock');
      const now = new Date();

      // Check if Daylight Saving Time is active in Greece
      const isDST = now.getMonth() > 2 && now.getMonth() < 10 || 
                    (now.getMonth() === 2 && now.getDate() - now.getDay() >= 31) || 
                    (now.getMonth() === 9 && now.getDate() - now.getDay() <= 6);

      // Greece's time zone is GMT+2 in standard time and GMT+3 during DST
      const offset = isDST ? 3 : 2;  // GMT+3 during DST, GMT+2 otherwise

      // Get local time in Greece (taking into account DST)
      const greeceTime = new Date(now.toLocaleString('en-US', { timeZone: 'Europe/Athens' }));

      const localHours = greeceTime.getHours().toString().padStart(2, '0');
      const localMinutes = greeceTime.getMinutes().toString().padStart(2, '0');
      const localSeconds = greeceTime.getSeconds().toString().padStart(2, '0');

      // Set the clock text to display the time in Greece
      clockElement.textContent = `${localHours}:${localMinutes} GMT+${offset}`;
    }

    // Update the clock every second
    setInterval(updateClock, 1000);

    // Initial call to set the clock immediately
    updateClock();