<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header('Location: index.php'); exit; }
require_once '../includes/config.php';
require_once '../models/Order.php';
require_once '../models/Delivery.php';

$orderModel = new Order($pdo);
$deliveryModel = new Delivery($pdo);
$orders = $orderModel->getAll();

if (isset($_GET['action']) && $_GET['action'] === 'update_status' && isset($_GET['id'])) {
    $orderId = $_GET['id'];
    $status = $_GET['status'];
    $orderModel->updateStatus($orderId, $status);
    // Update delivery status if shipped/delivered
    $delivery = $deliveryModel->getByOrder($orderId);
    if ($delivery) {
        $deliveryStatus = $status === 'shipped' ? 'in_transit' : ($status === 'delivered' ? 'delivered' : 'pending');
        $deliveryModel->updateStatus($delivery['id'], $deliveryStatus);
    }
    header('Location: orders.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Orders</h1>
        <a href="index.php">Back to Dashboard</a>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id']; ?></td>
                    <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                    <td><?= formatPrice($order['total_amount']); ?></td>
                    <td><?= ucfirst($order['status']); ?></td>
                    <td>
                        <form style="display:inline;">
                            <select name="status" onchange="window.location.href='orders.php?action=update_status&id=<?= $order['id']; ?>&status='+this.value">
                                <option value="pending" <?= $order['status']=='pending'?'selected':''; ?>>Pending</option>
                                <option value="paid" <?= $order['status']=='paid'?'selected':''; ?>>Paid</option>
                                <option value="processing" <?= $order['status']=='processing'?'selected':''; ?>>Processing</option>
                                <option value="shipped" <?= $order['status']=='shipped'?'selected':''; ?>>Shipped</option>
                                <option value="delivered" <?= $order['status']=='delivered'?'selected':''; ?>>Delivered</option>
                                <option value="cancelled" <?= $order['status']=='cancelled'?'selected':''; ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>