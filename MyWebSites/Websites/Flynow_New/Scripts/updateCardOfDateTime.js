// Function to update current time and date on the dials
function setAttributes() {
  var timeNow = new Date();
  
  // Get current hours, minutes, seconds
  var hours = timeNow.getHours();
  if (hours > 12) hours -= 12;  // For 12-hour format
  var minutes = timeNow.getMinutes();
  var seconds = timeNow.getSeconds();
  
  // Update the dials for the time section
  $('.js-clock[data-dur="hh"]').attr('data-cur', (hours + 1));
  if (minutes < 10) {
    $('.js-clock[data-dur="mm"]').attr('data-cur', 1);
    $('.js-clock[data-dur="m"]').attr('data-cur', (minutes + 1));
  } else {
    $('.js-clock[data-dur="mm"]').attr('data-cur', (nthDigit(minutes, 0) + 1));
    $('.js-clock[data-dur="m"]').attr('data-cur', (nthDigit(minutes, 1) + 1));
  }
  if (seconds < 10) {
    $('.js-clock[data-dur="ss"]').attr('data-cur', 1);
    $('.js-clock[data-dur="s"]').attr('data-cur', (seconds + 1));
  } else {
    $('.js-clock[data-dur="ss"]').attr('data-cur', (nthDigit(seconds, 0) + 1));
    $('.js-clock[data-dur="s"]').attr('data-cur', (nthDigit(seconds, 1) + 1));
  }
  
  // Get current day, month, year
  var day = timeNow.getDate();
  var month = timeNow.getMonth() + 1; // months are zero-indexed
  var year = timeNow.getFullYear();
  
  // Get current day of the week
  var daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
  var dayOfWeek = daysOfWeek[timeNow.getDay()];
  
  // Format date and time string
  var formattedDate = "It's " + dayOfWeek + " " + (day < 10 ? "0" : "") + day + " - " + (month < 10 ? "0" : "") + month + " - " + year + ", " + (hours < 10 ? "0" : "") + hours + ":" + (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
  
  // Update the date-time span element
  document.getElementById('currentDate').textContent = formattedDate;
  
  // Update the dials for the date section
  $('.js-clock[data-dur="dd"]').attr('data-cur', day);
  $('.js-clock[data-dur="mm"]').attr('data-cur', month);
  $('.js-clock[data-dur="yyyy"]').attr('data-cur', year);
}
