// Simple checkout validation
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.querySelector('.checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Basic validation - ensure payment method is selected
            const method = document.getElementById('payment_method');
            if (!method.value) {
                e.preventDefault();
                alert('Please select a payment method.');
            }
        });
    }
});