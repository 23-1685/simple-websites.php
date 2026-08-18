<?php
// process_mpesa_payment.php - MOCK VERSION (For Learning)
session_start();
require_once "db.php";

// Get order ID from URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id == 0) {
    die("<div class='alert alert-danger'>No order specified. <a href='admin/dashboard.php'>Go back</a></div>");
}

// Fetch order details
$query = "SELECT o.*, u.fullname as customer_name, u.phone 
          FROM orders o 
          LEFT JOIN users u ON o.user_id = u.id 
          WHERE o.id = $order_id";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    die("<div class='alert alert-danger'>Order not found.</div>");
}

// Check if already paid
if (isset($order['payment_status']) && $order['payment_status'] == 'Paid') {
    die("<div class='alert alert-warning'>This order is already paid.</div>");
}

$error_message = "";
$success_message = "";
$show_form = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone_number = trim($_POST['phone_number']);
    
    // Basic phone validation (just for demo)
    $phone_number = preg_replace('/\s+/', '', $phone_number);
    if (strlen($phone_number) < 10) {
        $error_message = "❌ Please enter a valid phone number (e.g., 0712345678).";
    } else {
        // ✅ SIMULATE M-PESA PAYMENT SUCCESS
        // This simulates the 3-5 seconds it takes for M-Pesa to process
        sleep(2); // Fake processing delay
        
        // Generate a fake transaction reference
        $transaction_ref = "MP" . date('Ymd') . strtoupper(substr(uniqid(), -6));
        
        // Update order as paid
        $update = "UPDATE orders SET payment_status='Paid', status='Processing' WHERE id=$order_id";
        mysqli_query($conn, $update);
        
        // Update payments table if it exists
        $check_payments = mysqli_query($conn, "SHOW TABLES LIKE 'payments'");
        if (mysqli_num_rows($check_payments) > 0) {
            $update_payment = "UPDATE payments SET 
                                payment_method='M-Pesa', 
                                paid_at=NOW(),
                                mpesa_phone='$phone_number',
                                mpesa_receipt_number='$transaction_ref'
                              WHERE order_id=$order_id";
            mysqli_query($conn, $update_payment);
            
            // If no payment record exists, create one
            if (mysqli_affected_rows($conn) == 0) {
                $insert = "INSERT INTO payments (order_id, payment_method, paid_at, mpesa_phone, mpesa_receipt_number) 
                           VALUES ($order_id, 'M-Pesa', NOW(), '$phone_number', '$transaction_ref')";
                mysqli_query($conn, $insert);
            }
        }
        
        $success_message = "✅ M-Pesa payment successful! Transaction Ref: #$transaction_ref";
        $show_form = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>M-Pesa Payment - Daystar Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #11998e, #38ef7d);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .payment-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            padding: 2.5rem;
            max-width: 500px;
            width: 100%;
        }
        .btn-success-custom {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            color: white;
            width: 100%;
        }
        .btn-success-custom:hover {
            color: white;
            transform: translateY(-2px);
        }
        .amount-display {
            font-size: 2rem;
            font-weight: 800;
            color: #0f6c48;
        }
        .phone-input {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        .simulation-badge {
            background: #f8f9fa;
            border: 2px dashed #ffc107;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        .simulation-badge span {
            background: #ffc107;
            color: #000;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="payment-card">
    <div class="text-center mb-4">
        <span style="font-size: 3rem;">📱</span>
        <h2 class="fw-bold text-dark">M-Pesa Payment</h2>
        <p class="text-muted">Pay for your laundry using M-Pesa</p>
    </div>

    <!-- SIMULATION BADGE -->
    <div class="simulation-badge">
        <span>🎓 DEMO MODE</span>
        <p class="mb-0 mt-1 small text-muted">This is a simulated M-Pesa payment for learning purposes.</p>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?= $success_message ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <div class="alert alert-info mt-2">
            <small>📌 <strong>Order #<?= $order_id ?></strong> - Amount: KES <?= number_format($order['total_amount'], 2) ?></small>
        </div>
        <div class="text-center mt-3">
            <a href="admin/dashboard.php" class="btn btn-outline-secondary w-100">Go to Dashboard</a>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <div class="text-center mt-2">
            <a href="process_mpesa_payment.php?order_id=<?= $order_id ?>" class="btn btn-outline-secondary btn-sm">Try Again</a>
        </div>
    <?php endif; ?>

    <?php if ($show_form && empty($success_message)): ?>
        <div class="mb-4">
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Order #</small>
                    <p class="fw-bold">#<?= $order['id'] ?></p>
                </div>
                <div class="col-6 text-end">
                    <small class="text-muted">Amount</small>
                    <p class="amount-display">KES <?= number_format($order['total_amount'], 2) ?></p>
                </div>
            </div>
        </div>

        <hr>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">M-PESA PHONE NUMBER</label>
                <input type="text" name="phone_number" class="form-control phone-input" 
                       placeholder="e.g., 0712345678" required 
                       value="<?= isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : '' ?>">
                <small class="text-muted">Enter any phone number for this simulation.</small>
            </div>

            <button type="submit" class="btn btn-success-custom">
                💳 Simulate M-Pesa Payment
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="process_payment.php?order_id=<?= $order_id ?>" class="text-muted small text-decoration-none">← Use another payment method</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>