<?php
session_start();
require_once "db.php";

// Get the order ID from URL (e.g., ?order_id=101)
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// If no order ID is provided, show an error
if ($order_id == 0) {
    die("<div class='alert alert-danger'>No order specified. <a href='admin/dashboard.php'>Go back to dashboard</a></div>");
}

// Using 'users' table instead of 'customers'
$query = "SELECT o.*, u.fullname as customer_name 
          FROM orders o 
          LEFT JOIN users u ON o.user_id = u.id 
          WHERE o.id = $order_id";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if (!$order) {
    die("<div class='alert alert-danger'>Order not found. <a href='admin/dashboard.php'>Go back to dashboard</a></div>");
}

// If payment is already done, block it
if (isset($order['payment_status']) && $order['payment_status'] == 'Paid') {
    die("<div class='alert alert-warning'>This order is already paid. <a href='admin/dashboard.php'>Go back to dashboard</a></div>");
}

// Handle the POST request (payment submission)
$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];
    $amount = $order['total_amount'];

    // 🔥 NEW: If M-Pesa is selected, redirect to the M-Pesa payment page
    if ($payment_method == 'M-Pesa') {
        header("Location: process_mpesa_payment.php?order_id=" . $order_id);
        exit();
    }

    if ($payment_method == 'Cash') {
        // Instantly mark as paid
        $update = "UPDATE orders SET payment_status='Paid' WHERE id=$order_id";
        mysqli_query($conn, $update);
        
        // Check if payments table exists, if not, skip this part
        $check_payments_table = mysqli_query($conn, "SHOW TABLES LIKE 'payments'");
        if (mysqli_num_rows($check_payments_table) > 0) {
            $update_payment = "UPDATE payments SET payment_method='Cash', paid_at=NOW() WHERE order_id=$order_id";
            mysqli_query($conn, $update_payment);
        }
        
        $success_message = "✅ Cash payment recorded successfully! Order #$order_id is now complete.";
        
    } elseif ($payment_method == 'Online') {
        // SIMULATE ONLINE PAYMENT GATEWAY
        $card_number = $_POST['card_number'] ?? '';
        $expiry = $_POST['expiry'] ?? '';
        $cvv = $_POST['cvv'] ?? '';

        // Basic validation (check if it's 16 digits)
        $card_number_clean = preg_replace('/\s+/', '', $card_number);
        if (strlen($card_number_clean) != 16 || !is_numeric($card_number_clean)) {
            $error_message = "❌ Invalid card number. Must be 16 digits.";
        } else {
            // Simulate processing...
            sleep(2); // Pretend we are contacting the bank
            
            $update = "UPDATE orders SET payment_status='Paid' WHERE id=$order_id";
            mysqli_query($conn, $update);
            
            // Check if payments table exists
            $check_payments_table = mysqli_query($conn, "SHOW TABLES LIKE 'payments'");
            if (mysqli_num_rows($check_payments_table) > 0) {
                $update_payment = "UPDATE payments SET payment_method='Online (Mock)', paid_at=NOW() WHERE order_id=$order_id";
                mysqli_query($conn, $update_payment);
            }
            
            $transaction_ref = "TX-" . strtoupper(uniqid());
            $success_message = "✅ Payment Successful! Transaction Ref: #$transaction_ref";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daystar Laundry - Payment</title>
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            width: 100%;
            max-width: 550px;
            padding: 2.5rem;
        }
        .payment-title {
            font-weight: 800;
            color: #0f6c48;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(56, 239, 125, 0.25);
            border-color: #38ef7d;
        }
        .btn-submit {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            color: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
            color: white;
        }
        .btn-cancel {
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
        }
        .amount-display {
            font-size: 2rem;
            font-weight: 800;
            color: #0f6c48;
        }
        .online-fields {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px dashed #ced4da;
        }
        .online-fields.show {
            display: block;
        }
        .mpesa-fields {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px dashed #28a745;
        }
        .mpesa-fields.show {
            display: block;
        }
        .simulation-badge {
            background: #fff3cd;
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
        <span style="font-size: 3rem;">💰</span>
        <h2 class="payment-title">Payment</h2>
        <p class="text-muted">Complete the transaction for Order #<?php echo $order['id']; ?></p>
    </div>

    <!-- Success Message -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="text-center mt-3">
            <a href="admin/dashboard.php" class="btn btn-outline-success">← Back to Dashboard</a>
        </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error_message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Payment Form (only show if not already paid) -->
    <?php if (empty($success_message) && (!isset($order['payment_status']) || $order['payment_status'] != 'Paid')): ?>
        
        <!-- SIMULATION BADGE -->
        <div class="simulation-badge">
            <span>🎓 DEMO MODE</span>
            <p class="mb-0 mt-1 small text-muted">All payments in this system are simulated for learning purposes.</p>
        </div>

        <div class="mb-4">
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Customer</small>
                    <p class="fw-bold"><?= htmlspecialchars($order['customer_name'] ?? 'Guest') ?></p>
                </div>
                <div class="col-6 text-end">
                    <small class="text-muted">Total Amount</small>
                    <p class="amount-display">KES <?= number_format($order['total_amount'], 2) ?></p>
                </div>
            </div>
        </div>

        <hr>

        <form method="POST" action="" id="paymentForm">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold">SELECT PAYMENT METHOD</label>
                <select name="payment_method" id="payment_method" class="form-control" required onchange="togglePaymentFields()">
                    <option value="Cash">💵 Cash on Pickup</option>
                    <option value="Online">💳 Online (Card Simulation)</option>
                    <option value="M-Pesa">📱 M-Pesa (STK Push Simulation)</option>
                </select>
            </div>

            <!-- Online Payment Fields (Hidden by default) -->
            <div id="online_fields" class="online-fields">
                <p class="text-muted small mb-3"><i class="bi bi-info-circle"></i> This is a mock payment gateway for demonstration purposes.</p>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Card Number</label>
                    <input type="text" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">Expiry (MM/YY)</label>
                        <input type="text" name="expiry" class="form-control" placeholder="08/26" maxlength="5">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">CVV</label>
                        <input type="password" name="cvv" class="form-control" placeholder="***" maxlength="4">
                    </div>
                </div>
                <div class="alert alert-info">
                    <small>🔒 <strong>Demo Mode:</strong> Enter any 16-digit number (e.g., 1234567890123456) to simulate a successful payment.</small>
                </div>
            </div>

            <!-- M-Pesa Payment Fields (Hidden by default) -->
            <div id="mpesa_fields" class="mpesa-fields">
                <p class="text-muted small mb-3"><i class="bi bi-info-circle"></i> An M-Pesa STK Push will be sent to your phone.</p>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">M-Pesa Phone Number</label>
                    <input type="text" name="mpesa_phone" class="form-control" placeholder="0712345678">
                    <small class="text-muted">Enter the Safaricom number registered with M-Pesa.</small>
                </div>
                <div class="alert alert-success">
                    <small>📱 <strong>Demo Mode:</strong> Click confirm to simulate an M-Pesa payment. You will be redirected to the M-Pesa payment page.</small>
                </div>
            </div>

            <button type="submit" class="btn btn-submit w-100 mt-3">✅ Confirm Payment</button>
        </form>

        <div class="text-center mt-3">
            <a href="admin/dashboard.php" class="btn btn-outline-secondary w-100 btn-cancel">← Cancel & Return to Dashboard</a>
        </div>
    <?php endif; ?>
</div>

<script>
function togglePaymentFields() {
    var method = document.getElementById('payment_method').value;
    var onlineFields = document.getElementById('online_fields');
    var mpesaFields = document.getElementById('mpesa_fields');
    
    // Hide all fields first
    onlineFields.classList.remove('show');
    mpesaFields.classList.remove('show');
    
    // Show the relevant fields based on selection
    if (method === 'Online') {
        onlineFields.classList.add('show');
    } else if (method === 'M-Pesa') {
        mpesaFields.classList.add('show');
    }
}

// Auto-format card number with spaces
document.addEventListener('DOMContentLoaded', function() {
    var cardInput = document.querySelector('input[name="card_number"]');
    if (cardInput) {
        cardInput.addEventListener('input', function(e) {
            var value = this.value.replace(/\s+/g, '').replace(/[^0-9]/g, '');
            var formatted = '';
            for (var i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += value[i];
            }
            this.value = formatted;
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>