<?php require_once 'layout/header.php'; ?>
<h1>My Orders</h1>
<?php if (empty($orders)): ?>
    <p>You haven't placed any orders yet.</p>
<?php else: ?>
    <table class="orders-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id']; ?></td>
                    <td><?= date('M d, Y', strtotime($order['created_at'])); ?></td>
                    <td><?= formatPrice($order['total_amount']); ?></td>
                    <td><span class="status-<?= $order['status']; ?>"><?= ucfirst($order['status']); ?></span></td>
                    <td><a href="index.php?page=order-detail&id=<?= $order['id']; ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php require_once 'layout/footer.php'; ?>