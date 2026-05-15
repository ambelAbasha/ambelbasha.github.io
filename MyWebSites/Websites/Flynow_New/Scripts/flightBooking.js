document.addEventListener("DOMContentLoaded", function() {
            const departureSelect = document.getElementById("fromSelect");
            const arrivalSelect = document.getElementById("toSelect");
            const departureDateInput = document.getElementById("departureDate");
            const returnDateInput = document.getElementById("returnDate");

            // Prevent same city selection
            function updateArrivalOptions() {
                const departureCity = departureSelect.value;
                const options = arrivalSelect.options;

                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    option.disabled = option.value === departureCity;
                }

                // Reset arrival select if it was the same as departure
                if (arrivalSelect.value === departureCity) {
                    arrivalSelect.value = ""; // Clear selection
                }
            }

            // Disable return date if it's the same as departure date
            function updateReturnDateOptions(selectedDates) {
                const selectedDepartureDate = selectedDates[0];
                if (selectedDepartureDate) {
                    const returnDate = returnDateInput._flatpickr;
                    returnDate.set('minDate', selectedDepartureDate);
                    returnDate.set('disable', [selectedDepartureDate]); // Disable the same date
                }
            }

            // Event listeners
            departureSelect.addEventListener("change", updateArrivalOptions);
            flatpickr(departureDateInput, {
                dateFormat: "d-m-Y",
                minDate: "today",
                onChange: function(selectedDates) {
                    updateReturnDateOptions(selectedDates);
                }
            });

            flatpickr(returnDateInput, {
                dateFormat: "d-m-Y",
                minDate: "today",
                // Initialize without a specific date to avoid issues
                onChange: function(selectedDates) {
                    // Ensure return date cannot be the same as departure date
                    const selectedDepartureDate = departureDateInput._flatpickr.selectedDateObj;
                    if (selectedDepartureDate && selectedDates[0].toDateString() === selectedDepartureDate.toDateString()) {
                        returnDateInput._flatpickr.clear(); // Clear the return date if it's the same
                    }
                }
            });
        });