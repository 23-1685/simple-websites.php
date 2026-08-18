<?php
include "includes/header.php";
require_once "db.php";

$result = null;
$searched = false;
$orderNo = "";

if (isset($_POST['track']) || isset($_GET['order'])) {
    $orderNo = isset($_POST['order']) ? trim($_POST['order']) : trim($_GET['order']);
    $searched = true;

    // Secure query with parameters to avoid SQL injection
    $stmt = mysqli_prepare($conn, "
        SELECT orders.*, services.service_name 
        FROM orders 
        JOIN services ON orders.service_id = services.id 
        WHERE orders.order_number = ?
    ");
    mysqli_stmt_bind_param($stmt, "s", $orderNo);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
}
?>

<div class="row justify-content-center mt-5">
    <div class="col-lg-8 col-md-10">
        <!-- Tracker Input Card -->
        <div class="card shadow border-0 rounded-4 p-4 mb-4" style="background: white;">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-geo-alt-fill" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">Public Order Tracker</h3>
                    <p class="text-muted mb-0">Track the real-time progress of your laundry order</p>
                </div>
            </div>

            <form method="POST" action="track_order.php" class="row g-2">
                <div class="col-sm-9">
                    <input type="text" name="order" class="form-control form-control-lg" placeholder="Enter Order Number (e.g. AM1718000000)" value="<?= htmlspecialchars($orderNo) ?>" required style="border-radius: 10px;">
                </div>
                <div class="col-sm-3">
                    <button type="submit" name="track" class="btn btn-success btn-lg w-100 fw-semibold" style="border-radius: 10px;">
                        Track Order
                    </button>
                </div>
            </form>
        </div>

        <!-- Tracker Result Card -->
        <?php if ($searched): ?>
            <?php if ($result): ?>
                <?php
                $status = strtolower($result['status']);
                $steps = ['pending', 'processing', 'ready', 'completed'];
                $statusIndex = array_search($status, $steps);
                if ($statusIndex === false) {
                    $statusIndex = -1; // Cancelled
                }
                ?>
                <div class="card shadow border-0 rounded-4 p-4 p-md-5 mb-5" style="background: white;">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div>
                            <span class="text-muted small fw-bold">ORDER NUMBER</span>
                            <h4 class="fw-bold text-success mb-0"><?= htmlspecialchars($result['order_number']) ?></h4>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small fw-bold">TOTAL AMOUNT</span>
                            <h4 class="fw-bold text-dark mb-0">KSh <?= number_format($result['total_amount'], 2) ?></h4>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Service:</strong> <?= htmlspecialchars($result['service_name']) ?></li>
                                <li class="mb-2"><strong>Quantity:</strong> <?= htmlspecialchars($result['quantity']) ?></li>
                                <li><strong>Dropoff:</strong> <?= htmlspecialchars($result['dropoff_date']) ?></li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Pickup Date:</strong> <?= $result['pickup_date'] ? htmlspecialchars($result['pickup_date']) : 'Pending status' ?></li>
                                <?php if (!empty($result['notes'])): ?>
                                    <li><strong>Instructions:</strong> <span class="text-muted italic">"<?= htmlspecialchars($result['notes']) ?>"</span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <?php if ($status == 'cancelled'): ?>
                        <div class="alert alert-danger text-center py-4 rounded-3" role="alert">
                            <i class="bi bi-x-circle-fill" style="font-size: 2rem;"></i>
                            <h5 class="fw-bold mt-2 mb-1">Order Cancelled</h5>
                            <p class="mb-0 small">This order has been cancelled. Please contact customer care.</p>
                        </div>
                    <?php else: ?>
                        <!-- Visual Timeline -->
                        <div class="timeline-container mt-4 mb-2">
                            <div class="timeline-track"></div>
                            <div class="row text-center position-relative" style="z-index: 2;">
                                <?php foreach ($steps as $idx => $stepName): ?>
                                    <?php
                                    $isPast = $idx <= $statusIndex;
                                    $isCurrent = $idx == $statusIndex;
                                    $stepClass = $isPast ? 'step-completed' : 'step-pending';
                                    if ($isCurrent) $stepClass = 'step-current';
                                    
                                    $iconMap = [
                                        'pending' => 'bi-hourglass-split',
                                        'processing' => 'bi-gear-wide-connected',
                                        'ready' => 'bi-check2-square',
                                        'completed' => 'bi-box-seam-fill'
                                    ];
                                    ?>
                                    <div class="col-3">
                                        <div class="timeline-node mx-auto d-flex align-items-center justify-content-center <?= $stepClass ?>">
                                            <i class="bi <?= $iconMap[$stepName] ?>"></i>
                                        </div>
                                        <div class="timeline-label mt-3 fw-bold small text-capitalize <?= $isPast ? 'text-success' : 'text-muted' ?>">
                                            <?= $stepName ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="card shadow border-0 rounded-4 p-5 text-center text-muted mb-5">
                    <i class="bi bi-search" style="font-size: 3rem; color: #ced4da;"></i>
                    <h5 class="fw-bold mt-3 text-dark">Order Not Found</h5>
                    <p class="mb-0 small">Please double-check the order number and try again.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="text-center mt-3 mb-5">
            <a href="index.php" class="btn btn-outline-secondary px-4" style="border-radius: 10px;">← Back to Home</a>
        </div>
    </div>
</div>

<!-- Import Bootstrap Icons for root tracking page -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .timeline-container {
        position: relative;
        padding: 1.5rem 0;
    }
    .timeline-track {
        position: absolute;
        top: 50%;
        left: 12.5%;
        right: 12.5%;
        height: 4px;
        background-color: #dee2e6;
        transform: translateY(-50%);
        z-index: 1;
    }
    .timeline-node {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #e9ecef;
        border: 4px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        color: #6c757d;
        font-size: 1.2rem;
        transition: all 0.3s;
    }
    .timeline-node.step-completed {
        background-color: #38ef7d;
        color: white;
        box-shadow: 0 4px 10px rgba(56, 239, 125, 0.4);
    }
    .timeline-node.step-current {
        background-color: #11998e;
        color: white;
        transform: scale(1.15);
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.5);
        border-color: #38ef7d;
    }
    .timeline-label {
        font-size: 0.85rem;
    }
</style>

<?php include "includes/footer.php"; ?>
