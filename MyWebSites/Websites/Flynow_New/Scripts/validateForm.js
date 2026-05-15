// Form validation function
        function validateForm() {
            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(function(el) {
                el.style.display = 'none';
            });

            let isValid = true;

            // Check each required field
            if (!document.querySelector('#airlineSelect').value) {
                document.getElementById('airlineError').style.display = 'block';
                isValid = false;
            }
            if (!document.querySelector('#fromSelect').value) {
                document.getElementById('fromError').style.display = 'block';
                isValid = false;
            }
            if (!document.querySelector('#toSelect').value) {
                document.getElementById('toError').style.display = 'block';
                isValid = false;
            }
            if (!document.querySelector('#classSelect').value) {
                document.getElementById('classError').style.display = 'block';
                isValid = false;
            }
            if (!document.querySelector('input[name="date"]').value) {
                document.getElementById('dateError').style.display = 'block';
                isValid = false;
            }
            if (!document.querySelector('input[name="date2"]').value) {
                document.getElementById('date2Error').style.display = 'block';
                isValid = false;
            }
            if (!document.querySelector('#personSelect').value) {
                document.getElementById('personError').style.display = 'block';
                isValid = false;
            }

            return isValid;
        }
        // Form validation function
        function validateForm() {
            let isValid = true;

            // Array of field validation data
            const fields = [
                {id: 'airlineSelect', errorId: 'airlineError'},
                {id: 'fromSelect', errorId: 'fromError'},
                {id: 'toSelect', errorId: 'toError'},
                {id: 'classSelect', errorId: 'classError'},
                {id: 'personSelect', errorId: 'personError'},
                {id: 'departureDate', errorId: 'dateError'},
                {id: 'returnDate', errorId: 'date2Error'}
            ];

            // Clear all previous error messages
            document.querySelectorAll('.error-message').forEach(errorMsg => {
                errorMsg.style.display = 'none';
            });

            // Validate each field
            fields.forEach(field => {
                const element = document.getElementById(field.id) || document.querySelector(`input[name="${field.id}"]`);
                if (!element || !element.value) {
                    document.getElementById(field.errorId).style.display = 'block';
                    isValid = false;
                }
            });

            return isValid;
        }