<?php require_once 'layout/header.php'; ?>

<h1>Checkout</h1>
<?php if (isset($_SESSION['checkout_error'])): ?>
    <div class="error"><?= $_SESSION['checkout_error']; unset($_SESSION['checkout_error']); ?></div>
<?php endif; ?>
<?php if (empty($_SESSION['cart'])): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <form action="index.php?page=checkout" method="POST" class="checkout-form">
        <div class="form-group">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" required>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>
        <!-- Add address fields here if needed -->
        <div class="order-summary">
            <h3>Order Summary</h3>
            <p>Subtotal: <?= formatPrice(getCartTotal()); ?></p>
            <p>Shipping: $5.00</p>
            <p>Tax (10%): <?= formatPrice(getCartTotal() * 0.10); ?></p>
            <p><strong>Total: <?= formatPrice(getCartTotal() + 5.00 + (getCartTotal() * 0.10)); ?></strong></p>
        </div>
        <button type="submit" class="btn-primary">Place Order</button>
    </form>
<?php endif; ?>

<?php require_once 'layout/footer.php'; ?>