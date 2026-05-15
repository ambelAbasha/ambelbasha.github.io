function calculateTotal() { 
    let departureTotal = 0;
    let returnTotal = 0;

    // Get all checked departure flights and calculate the total price
    document.querySelectorAll('#departureForm input[type="checkbox"]:checked').forEach(checkbox => {
        const price = parseFloat(checkbox.dataset.price);
        departureTotal += isNaN(price) ? 0 : price;
    });

    // Get all checked return flights and calculate the total price
    document.querySelectorAll('#returnForm input[type="checkbox"]:checked').forEach(checkbox => {
        const price = parseFloat(checkbox.dataset.price);
        returnTotal += isNaN(price) ? 0 : price;
    });

    // Update the departure price display
    let departurePriceElement = document.getElementById('departurePrice');
    if (departurePriceElement) {
        departurePriceElement.innerText = departureTotal.toFixed(2); // Show only the departure price
    }

    // Update the return price display
    let returnPriceElement = document.getElementById('returnPrice');
    if (returnPriceElement) {
        returnPriceElement.innerText = returnTotal.toFixed(2); // Show only the return price
    }

    // Update the total price display (sum of both departure and return prices)
    let totalPriceElement = document.getElementById('totalPrice');
    if (totalPriceElement) {
        totalPriceElement.innerText = (departureTotal + returnTotal).toFixed(2); // Sum of departure and return price
    }

    // Show the total container if it's hidden and total price is greater than zero
    const totalContainer = document.querySelector('.total-container');
    if (totalContainer && (departureTotal + returnTotal) > 0) {
        totalContainer.style.display = 'block'; // Show the total container
    }

    // Add the "+" flowing effect for price updates
    if (totalPriceElement && (departureTotal + returnTotal) > 0) {
        const priceUpdateElement = document.createElement('div');
        priceUpdateElement.classList.add('price-update');
        priceUpdateElement.innerText = '+'; // This will be the "flowing" symbol
        
        // Append the "+" symbol to the total price
        totalPriceElement.appendChild(priceUpdateElement);

        // Remove the "+" symbol after the animation
        setTimeout(() => {
            priceUpdateElement.remove();
        }, 1000); // Matches the duration of the animation
    }
}

// Ensure total is calculated on page load
window.onload = () => calculateTotal();
