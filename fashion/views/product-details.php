<?php require_once 'layout/header.php'; ?>

<div class="product-detail">
    <div class="product-image">
        <img src="<?= $product['image_url'] ?: 'assets/images/products/placeholder.jpg'; ?>" alt="<?= htmlspecialchars($product['name']); ?>">
    </div>
    <div class="product-info">
        <h1><?= htmlspecialchars($product['name']); ?></h1>
        <p class="price"><?= formatPrice($product['base_price']); ?></p>
        <p><?= nl2br(htmlspecialchars($product['description'])); ?></p>
        <form action="index.php?page=cart&action=add" method="GET">
            <input type="hidden" name="page" value="cart">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" value="<?= $product['id']; ?>">
            <?php if (!empty($variants)): ?>
                <label for="variant">Size / Color:</label>
                <select name="variant" id="variant" class="select-input">
                    <?php foreach ($variants as $variant): ?>
                        <option value="<?= $variant['id']; ?>">
                            <?= htmlspecialchars($variant['size'] . ' - ' . $variant['color']); ?>
                            (<?= formatPrice($variant['additional_price']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <label for="qty">Quantity:</label>
            <input type="number" name="qty" id="qty" value="1" min="1" class="qty-input">
            <button type="submit" class="btn-primary">Add to Cart</button>
        </form>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>