<?php
require_once 'models/Product.php';
$productModel = new Product($pdo);
$products = $productModel->getFeatured(6);
require_once 'views/home.php';
?>