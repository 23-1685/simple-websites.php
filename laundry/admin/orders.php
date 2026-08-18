<?php
require "../includes/admin_header.php";
require "../db.php";

$orders = mysqli_query($conn, "
    SELECT orders.*, users.fullname, services.service_name 
    FROM orders 
    JOIN users ON users.id = orders.user_id 
    JOIN services ON services.id = orders.service_id 
    ORDER BY orders.id DESC
");
?>

<div class="row">
    <div class="col-12">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Order deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Order status updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Manage Laundry Orders</h3>
                    <p class="text-muted mb-0">Update orders status, track dropoffs/pickups, or delete records</p>
                </div>
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-cart-check-fill" style="font-size: 1.5rem;"></i>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">Order No</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">Service Details</th>
                            <th class="py-3">Price Details</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Update Status</th>
                            <th class="py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($orders) > 0): ?>
                            <?php while ($o = mysqli_fetch_assoc($orders)): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-success"><?= htmlspecialchars($o['order_number']) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($o['fullname']) ?></div>
                                        <div class="small text-muted">Dropoff: <?= htmlspecialchars($o['dropoff_date']) ?></div>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($o['service_name']) ?></div>
                                        <div class="small text-muted">Qty: <?= htmlspecialchars($o['quantity']) ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">KSh <?= number_format($o['total_amount'], 2) ?></div>
                                        <div class="small text-muted">Pickup: <?= $o['pickup_date'] ? htmlspecialchars($o['pickup_date']) : 'Pending' ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = 'badge-status-pending';
                                        switch(strtolower($o['status'])) {
                                            case 'processing': $statusClass = 'badge-status-processing'; break;
                                            case 'ready': $statusClass = 'badge-status-ready'; break;
                                            case 'completed': $statusClass = 'badge-status-completed'; break;
                                            case 'cancelled': $statusClass = 'badge-status-cancelled'; break;
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?> px-3 py-2" style="border-radius: 6px; font-weight: 600;">
                                            <?= htmlspecialchars($o['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form action="update_status.php" method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="id" value="<?= $o['id'] ?>">
                                            <select name="status" class="form-select form-select-sm me-2" style="border-radius: 6px; width: 130px;">
                                                <option value="Pending" <?= $o['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="Processing" <?= $o['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                                <option value="Ready" <?= $o['status'] == 'Ready' ? 'selected' : '' ?>>Ready</option>
                                                <option value="Completed" <?= $o['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                                <option value="Cancelled" <?= $o['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm px-2" style="border-radius: 6px;">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        <a href="delete_order.php?id=<?= $o['id'] ?>" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 6px;" onclick="return confirm('Are you sure you want to delete order #<?= htmlspecialchars($o['order_number']) ?>?');">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-cart" style="font-size: 3rem;"></i>
                                    <p class="mt-3 mb-0 fw-semibold">No orders placed yet</p>
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
require "../includes/admin_footer.php";
?>