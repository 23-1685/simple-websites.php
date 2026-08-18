<?php require_once 'layout/header.php'; ?>

<h1>Shopping Cart</h1>
<?php if (empty($_SESSION['cart'])): ?>
    <p>Your cart is empty. <a href="index.php?page=shop">Continue Shopping</a></p>
<?php else: ?>
    <form action="index.php?page=cart&action=update" method="POST">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td><?= formatPrice($item['price']); ?></td>
                        <td>
                            <input type="number" name="quantities[<?= $key; ?>]" value="<?= $item['quantity']; ?>" min="1" class="qty-input">
                        </td>
                        <td><?= formatPrice($item['price'] * $item['quantity']); ?></td>
                        <td><a href="index.php?page=cart&action=remove&id=<?= $key; ?>" class="btn-remove">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Subtotal</strong></td>
                    <td colspan="2"><strong><?= formatPrice(getCartTotal()); ?></strong></td>
                </tr>
            </tfoot>
        </table>
        <div class="cart-actions">
            <button type="submit" class="btn">Update Cart</button>
            <a href="index.php?page=checkout" class="btn-primary">Proceed to Checkout</a>
        </div>
    </form>
<?php endif; ?>

<?php require_once 'layout/footer.php'; ?>