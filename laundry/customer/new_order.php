<?php
require "../includes/customer_header.php";
require "../db.php";

$error = "";
$success = "";

if (isset($_POST['submit'])) {
    $user = $_SESSION['user_id'];
    $service = intval($_POST['service']);
    $quantity = floatval($_POST['quantity']);
    $dropoff = $_POST['dropoff'];
    $pickup = !empty($_POST['pickup']) ? $_POST['pickup'] : null;
    $notes = trim($_POST['notes']);

    if ($quantity <= 0) {
        $error = "Quantity must be greater than zero.";
    } elseif (empty($dropoff)) {
        $error = "Dropoff date is required.";
    } else {
        // Securely fetch service price
        $stmt_price = mysqli_prepare($conn, "SELECT price FROM services WHERE id = ?");
        if (!$stmt_price) {
            die("Prepare failed (price): " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt_price, "i", $service);
        mysqli_stmt_execute($stmt_price);
        $res_price = mysqli_stmt_get_result($stmt_price);

        if ($row_price = mysqli_fetch_assoc($res_price)) {
            $total = $row_price['price'] * $quantity;
            $order = "AM" . time();

            // Prepare the INSERT statement
            $stmt = mysqli_prepare($conn,
                "INSERT INTO orders (order_number, user_id, service_id, quantity, total_amount, dropoff_date, pickup_date, notes)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            if (!$stmt) {
                die("Prepare failed (insert): " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param(
                $stmt,
                "siiddsss",
                $order,
                $user,
                $service,
                $quantity,
                $total,
                $dropoff,
                $pickup,
                $notes
            );

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>window.location.href='my_orders.php?placed=1';</script>";
                exit();
            } else {
                $error = "Failed to place order. Please try again.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Selected service does not exist.";
        }
        mysqli_stmt_close($stmt_price);
    }
}
?>

<!-- HTML form remains unchanged (same as your original) -->
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-10">
        <div class="card card-custom p-4 p-md-5">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-plus-circle-fill" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">Place New Order</h3>
                    <p class="text-muted mb-0">Input your laundry details below to schedule a wash</p>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">SELECT SERVICE</label>
                    <select name="service" class="form-select form-control" required>
                        <?php
                        $services = mysqli_query($conn, "SELECT * FROM services");
                        while ($s = mysqli_fetch_assoc($services)) {
                            $selected = (isset($_POST['service']) && $_POST['service'] == $s['id']) ? 'selected' : '';
                            echo "<option value='{$s['id']}' {$selected}>" . htmlspecialchars($s['service_name']) . " (KSh " . number_format($s['price']) . "/" . htmlspecialchars($s['unit']) . ")</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">QUANTITY (WEIGHT / PIECES)</label>
                    <div class="input-group">
                        <input type="number" step="0.1" name="quantity" class="form-control" placeholder="e.g. 5.5" required value="<?= isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '' ?>">
                        <span class="input-group-text bg-light text-muted fw-bold">Amount</span>
                    </div>
                    <div class="form-text text-muted small">Enter the total weight in Kgs or the number of items.</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted small fw-bold">DROPOFF DATE</label>
                        <input type="date" name="dropoff" class="form-control" required value="<?= isset($_POST['dropoff']) ? htmlspecialchars($_POST['dropoff']) : date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted small fw-bold">EXPECTED PICKUP DATE (OPTIONAL)</label>
                        <input type="date" name="pickup" class="form-control" value="<?= isset($_POST['pickup']) ? htmlspecialchars($_POST['pickup']) : '' ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">SPECIAL INSTRUCTIONS</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="e.g. Wash with cold water, extra starch, separate whites, etc."><?= isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '' ?></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="dashboard.php" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 10px;">
                        Cancel
                    </a>
                    <button type="submit" name="submit" class="btn btn-success px-5 py-2 fw-semibold" style="border-radius: 10px;">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require "../includes/customer_footer.php";
?>