document.addEventListener('DOMContentLoaded', function() {
    const addToCartForms = document.querySelectorAll('form[action*="cart&action=add"]');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData);
            params.set('ajax', 1);
            
            fetch('index.php?' + params.toString(), {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-count').textContent = data.cart_count;
                    alert('Item added to cart!');
                } else {
                    alert('Error adding item.');
                }
            })
            .catch(err => console.error(err));
        });
    });
});