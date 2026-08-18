<?php require_once 'layout/header.php'; ?>

<section class="shop">
    <h1>All Products</h1>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?= $product['image_url'] ?: 'assets/images/products/placeholder.jpg'; ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                <h3><?= htmlspecialchars($product['name']); ?></h3>
                <p class="price"><?= formatPrice($product['base_price']); ?></p>
                <a href="index.php?page=product&id=<?= $product['id']; ?>" class="btn">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once 'layout/footer.php'; ?>