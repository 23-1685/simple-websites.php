<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="product-detail">
    <div class="product-image">
        <img src="<?= htmlspecialchars($product['image_url'] ?: 'assets/images/products/placeholder.jpg'); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
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
                <div class="form-group">
                    <label for="variant">Size / Color:</label>
                    <select name="variant" id="variant" class="select-input">
                        <?php foreach ($variants as $variant): ?>
                            <option value="<?= $variant['id']; ?>">
                                <?= htmlspecialchars($variant['size'] . ' - ' . $variant['color']); ?>
                                (<?= formatPrice($variant['additional_price']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="qty">Quantity:</label>
                <input type="number" name="qty" id="qty" value="1" min="1" class="qty-input">
            </div>
            
            <button type="submit" class="btn-primary">Add to Cart</button>
        </form>
        
        <p><a href="index.php?page=shop" class="btn">← Back to Shop</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>