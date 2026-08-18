<?php
require "../includes/customer_header.php";
require "../db.php";

$id = $_SESSION['user_id'];

// Secure query with parameters to avoid injection issues
$stmt_orders = mysqli_prepare($conn, "
    SELECT orders.*, services.service_name 
    FROM orders 
    JOIN services ON orders.service_id = services.id 
    WHERE orders.user_id = ? 
    ORDER BY orders.id DESC
");
mysqli_stmt_bind_param($stmt_orders, "i", $id);
mysqli_stmt_execute($stmt_orders);
$sql = mysqli_stmt_get_result($stmt_orders);
?>

<div class="row">
    <div class="col-12">
        <?php if (isset($_GET['placed'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Order successfully placed! You can track its progress below.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">My Laundry Orders</h3>
                    <p class="text-muted mb-0">List of all your active and previous orders</p>
                </div>
                <a href="new_order.php" class="btn btn-success fw-bold px-3 py-2" style="border-radius: 8px;">
                    <i class="bi bi-plus-circle me-1"></i> New Order
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Order No</th>
                            <th class="py-3">Service</th>
                            <th class="py-3">Quantity</th>
                            <th class="py-3">Amount</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Dropoff Date</th>
                            <th class="py-3">Pickup Date</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($sql) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($sql)): ?>
                                <tr>
                                    <td class="fw-bold text-success"><?= htmlspecialchars($row['order_number']) ?></td>
                                    <td><?= htmlspecialchars($row['service_name']) ?></td>
                                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                                    <td>KSh <?= number_format($row['total_amount'], 2) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = 'badge-status-pending';
                                        switch(strtolower($row['status'])) {
                                            case 'processing': $statusClass = 'badge-status-processing'; break;
                                            case 'ready': $statusClass = 'badge-status-ready'; break;
                                            case 'completed': $statusClass = 'badge-status-completed'; break;
                                            case 'cancelled': $statusClass = 'badge-status-cancelled'; break;
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?> px-3 py-2" style="border-radius: 6px; font-weight: 600;">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['dropoff_date']) ?></td>
                                    <td><?= $row['pickup_date'] ? htmlspecialchars($row['pickup_date']) : '<span class="text-muted italic">Pending status</span>' ?></td>
                                    <td>
                                        <form action="track_order.php" method="POST" class="d-inline">
                                            <input type="hidden" name="order" value="<?= htmlspecialchars($row['order_number']) ?>">
                                            <button type="submit" name="track" class="btn btn-outline-success btn-sm px-3" style="border-radius: 6px;">
                                                <i class="bi bi-geo-alt"></i> Track
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-basket" style="font-size: 3rem;"></i>
                                    <p class="mt-3 mb-0 fw-semibold">No orders found</p>
                                    <p class="small text-muted mb-0">Place your first order to get started!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
mysqli_stmt_close($stmt_orders);
require "../includes/customer_footer.php";
?>