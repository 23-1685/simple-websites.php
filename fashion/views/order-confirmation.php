<?php require_once 'layout/header.php'; 
$orderId = $_GET['id'] ?? null;
if (!$orderId) {
    redirect('index.php?page=home');
}
?>
<h1>Thank You for Your Order!</h1>
<p>Your order #<?= htmlspecialchars($orderId); ?> has been placed successfully.</p>
<p>We will send you a confirmation email shortly.</p>
<a href="index.php?page=my-orders" class="btn-primary">View My Orders</a>
<?php require_once 'layout/footer.php'; ?>