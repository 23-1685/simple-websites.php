<?php require_once 'layout/header.php'; ?>

<div class="hero">
    <h1>Welcome to Fashion Must Have Collection</h1>
    <p>Discover the latest trends in clothing and accessories.</p>
    <a href="index.php?page=shop" class="btn-primary">Explore Now</a>
</div>

<section class="featured">
    <h2>Featured Products</h2>
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