<?php
// Start session if not already started
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Sanitize input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Format price
function formatPrice($amount) {
    return '$' . number_format($amount, 2);
}

// Redirect
function redirect($url) {
    header("Location: $url");
    exit;
}

// Get cart count
function getCartCount() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

// Calculate cart total
function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get logged-in user ID
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get user name
function getUserName() {
    return $_SESSION['user_name'] ?? 'Guest';
}
?>