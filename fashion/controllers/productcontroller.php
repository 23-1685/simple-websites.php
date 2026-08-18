<?php
require_once __DIR__ . '/../models/Product.php';
$productModel = new Product($pdo);

if ($page === 'product' && isset($_GET['id'])) {
    $product = $productModel->getById($_GET['id']);
    $variants = $productModel->getVariants($_GET['id']);
    if (!$product) {
        require_once __DIR__ . '/../views/404.php';
        exit;
    }
    require_once __DIR__ . '/../views/product-details.php';
} else {
    $products = $productModel->getAll();
    require_once __DIR__ . '/../views/shop.php';
}
?>