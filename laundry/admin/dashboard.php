<?php
require "../includes/admin_header.php";
require "../db.php";

// --- STATS QUERIES (Existing) ---
$totalCustomers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM users WHERE role='customer'")
);

$totalOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM orders")
);

$pending = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM orders WHERE status='Pending'")
);

$completed = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM orders WHERE status='Completed'")
);

$revenue = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(total_amount) total FROM orders WHERE status='Completed'")
);

// 🆕 ADDED: Fetch the 5 most recent orders for the dashboard table
$recentOrdersQuery = "SELECT o.id, o.total_amount, o.status, o.payment_status, u.fullname 
                      FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      ORDER BY o.id DESC 
                      LIMIT 5";
$recentOrdersResult = mysqli_query($conn, $recentOrdersQuery);
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom p-4 bg-white text-dark">
            <h2 class="fw-bold mb-1">Admin Control Center 🎛️</h2>
            <p class="text-muted mb-0">Overview of the Daystar Digital Laundry Management System. Monitor customers, order volume, status, and system revenue.</p>
        </div>
    </div>
</div>

<!-- STATS CARDS (Unchanged) -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 p-3 bg-primary text-white border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small fw-bold">TOTAL CUSTOMERS</span>
                    <h2 class="fw-bold mb-0 mt-1"><?= $totalCustomers['total'] ?></h2>
                    <a href="customers.php" class="text-white-50 small d-block mt-3 text-decoration-none hover-underline">View All Customers →</a>
                </div>
                <div class="bg-white bg-opacity-20 rounded p-3">
                    <i class="bi bi-people-fill" style="font-size: 2.2rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 p-3 bg-success text-white border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small fw-bold">TOTAL ORDERS</span>
                    <h2 class="fw-bold mb-0 mt-1"><?= $totalOrders['total'] ?></h2>
                    <a href="orders.php" class="text-white-50 small d-block mt-3 text-decoration-none hover-underline">Manage Orders →</a>
                </div>
                <div class="bg-white bg-opacity-20 rounded p-3">
                    <i class="bi bi-cart-fill" style="font-size: 2.2rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 p-3 bg-warning text-dark border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-dark-50 small fw-bold">PENDING ORDERS</span>
                    <h2 class="fw-bold mb-0 mt-1"><?= $pending['total'] ?></h2>
                    <a href="orders.php" class="text-dark-50 small d-block mt-3 text-decoration-none hover-underline">Review Pending →</a>
                </div>
                <div class="bg-dark bg-opacity-10 rounded p-3">
                    <i class="bi bi-hourglass-split" style="font-size: 2.2rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 p-3 bg-info text-white border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-white-50 small fw-bold">TOTAL REVENUE</span>
                    <h2 class="fw-bold mb-0 mt-1">KSh <?= number_format($revenue['total'] ?? 0) ?></h2>
                    <a href="reports.php" class="text-white-50 small d-block mt-3 text-decoration-none hover-underline">Export Report →</a>
                </div>
                <div class="bg-white bg-opacity-20 rounded p-3">
                    <i class="bi bi-cash-stack" style="font-size: 2.2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🆕 ADDED: RECENT ORDERS TABLE WITH "TAKE PAYMENT" BUTTON -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">📋 Recent Orders</h5>
                <a href="orders.php" class="text-decoration-none small">View All →</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($recentOrdersResult) > 0): ?>
                            <?php while ($order = mysqli_fetch_assoc($recentOrdersResult)): ?>
                                <tr>
                                    <td><strong>#<?= $order['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($order['fullname'] ?? 'Guest') ?></td>
                                    <td>KSh <?= number_format($order['total_amount'], 2) ?></td>
                                    <td>
                                        <span class="badge <?= $order['status'] == 'Pending' ? 'bg-warning text-dark' : ($order['status'] == 'Completed' ? 'bg-success' : 'bg-secondary') ?>">
                                            <?= $order['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= ($order['payment_status'] ?? 'Pending') == 'Paid' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= ($order['payment_status'] ?? 'Pending') == 'Paid' ? '✅ Paid' : '⏳ Pending' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if (($order['payment_status'] ?? 'Pending') != 'Paid' && $order['status'] != 'Cancelled'): ?>
                                            <!-- 🆕 TAKE PAYMENT BUTTON - Links directly to your payment page -->
                                            <a href="../process_payment.php?order_id=<?= $order['id'] ?>" class="btn btn-success btn-sm">
                                                💰 Pay Now
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Fully Paid</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No orders placed yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- QUICK ACTIONS (UPDATED with M-Pesa Payment Link) -->
<div class="row">
    <div class="col-12">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3 text-dark">Quick Actions</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <a href="orders.php" class="btn btn-outline-dark w-100 py-3 fw-semibold">
                        <i class="bi bi-pencil-square me-2"></i> Update Order Status
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="services.php" class="btn btn-outline-dark w-100 py-3 fw-semibold">
                        <i class="bi bi-tags me-2"></i> Add / Edit Services
                    </a>
                </div>
                <!-- 🆕 DIRECT PAYMENT SHORTCUT -->
                <div class="col-md-3">
                    <a href="../process_payment.php" class="btn btn-outline-success w-100 py-3 fw-semibold">
                        <i class="bi bi-credit-card me-2"></i> Process Payments
                    </a>
                </div>
                <!-- 🆕 M-PESA PAYMENT BUTTON -->
                <div class="col-md-3">
                    <a href="../process_mpesa_payment.php" class="btn btn-outline-success w-100 py-3 fw-semibold">
                        <i class="bi bi-phone me-2"></i> M-Pesa Payment
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="reports.php" class="btn btn-outline-dark w-100 py-3 fw-semibold">
                        <i class="bi bi-download me-2"></i> Download Sales Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-underline:hover {
        text-decoration: underline !important;
    }
</style>

<?php
require "../includes/admin_footer.php";
?>