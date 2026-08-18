<?php
startSession();

// Handle AJAX/actions
if ($action === 'add' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    $variantId = $_GET['variant'] ?? null;
    $quantity = $_GET['qty'] ?? 1;

    // Fetch product price
    require_once 'models/Product.php';
    $productModel = new Product($pdo);
    $product = $productModel->getById($productId);
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    $price = $product['base_price'];
    if ($variantId) {
        $variant = $productModel->getVariantById($variantId);
        if ($variant) {
            $price += $variant['additional_price'];
        }
    }

    // Add to session cart
    $key = $productId . '-' . ($variantId ?? '0');
    if (!isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key] = [
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => 0,
            'price' => $price,
            'name' => $product['name']
        ];
    }
    $_SESSION['cart'][$key]['quantity'] += $quantity;

    if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
        echo json_encode(['success' => true, 'cart_count' => getCartCount()]);
        exit;
    }
    redirect('index.php?page=cart');
}

if ($action === 'remove' && isset($_GET['id'])) {
    $key = $_GET['id'];
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
    }
    redirect('index.php?page=cart');
}

if ($action === 'update' && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $key => $qty) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$key]);
        } else {
            $_SESSION['cart'][$key]['quantity'] = (int)$qty;
        }
    }
    redirect('index.php?page=cart');
}

// Display cart
require_once 'views/cart.php';
?>