<?php
requireLogin();
require_once 'models/Order.php';
require_once 'models/Delivery.php';
require_once 'models/Payment.php';

$orderModel = new Order($pdo);
$deliveryModel = new Delivery($pdo);
$paymentModel = new Payment($pdo);

// Get user's address (for simplicity, using a fake one or first address - implement real address selection)
// We'll assume the user has an address or we create a dummy one.
// For production, you'd have an address selection form.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process checkout
    $customerId = getUserId();
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        redirect('index.php?page=cart');
    }

    // Calculate totals
    $subtotal = getCartTotal();
    $tax = $subtotal * 0.10; // 10% tax
    $shippingCost = 5.00;
    $discount = 0;
    $total = $subtotal + $tax + $shippingCost - $discount;

    // For demo: use first address of customer or insert a dummy address
    // Ideally you would get address_id from the form
    $addressId = 1; // Change this to real logic
    $paymentMethod = $_POST['payment_method'] ?? 'credit_card';

    // Create order items array
    $items = [];
    foreach ($cart as $key => $item) {
        $items[] = [
            'product_id' => $item['product_id'],
            'variant_id' => $item['variant_id'],
            'quantity' => $item['quantity'],
            'unit_price' => $item['price'],
            'total_price' => $item['price'] * $item['quantity']
        ];
    }

    try {
        $orderId = $orderModel->create($customerId, $addressId, $addressId, $subtotal, $tax, $shippingCost, $discount, $total, $paymentMethod, $items);
        
        // Create delivery record
        $deliveryModel->create($orderId, $addressId);
        
        // Create payment record (mock)
        $paymentModel->create($orderId, $total, $paymentMethod, 'TXN-' . time(), 'completed');

        // Clear cart
        unset($_SESSION['cart']);

        redirect('index.php?page=order-confirmation&id=' . $orderId);
    } catch (Exception $e) {
        $_SESSION['checkout_error'] = $e->getMessage();
        redirect('index.php?page=checkout');
    }
}

// Show checkout page
require_once 'views/checkout.php';
?>