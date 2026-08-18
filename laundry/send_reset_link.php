<?php
// send_reset_link.php - PRESENTATION SAFE MODE
// No email required - displays link directly on screen

session_start();
ob_start();

// ✅ FIXED: Use the correct database file
require_once __DIR__ . "/db.php";

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: forgot_password.php");
    exit();
}

$email = mysqli_real_escape_string($conn, $_POST['email']);

// 1. Check if email exists in the Users table
$check_query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($result) == 0) {
    header("Location: forgot_password.php?error=email_not_found");
    exit();
}

// 2. Generate a secure token
$token = bin2hex(random_bytes(50));
$expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

// 3. Delete old tokens for this email
mysqli_query($conn, "DELETE FROM password_resets WHERE email='$email'");

// 4. Insert the new token
$insert_token = "INSERT INTO password_resets (email, token, expires_at) VALUES ('$email', '$token', '$expires_at')";
mysqli_query($conn, $insert_token);

// 5. Generate the reset link (using your actual folder name)
$reset_link = "http://localhost/laundry/reset_password.php?token=" . $token;

// 6. DISPLAY THE LINK DIRECTLY (No email needed for presentation)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset Link - Daystar Laundry</title>
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
        .card-custom {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            padding: 2.5rem;
            max-width: 600px;
            width: 100%;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary-custom:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
        }
        .link-box {
            background: #f8f9fa;
            border: 2px dashed #11998e;
            border-radius: 10px;
            padding: 15px;
            word-break: break-all;
            font-size: 0.9rem;
            color: #0f6c48;
        }
    </style>
</head>
<body>
<div class="card-custom">
    <div class="text-center mb-4">
        <span style="font-size: 3rem;">📧</span>
        <h2 class="fw-bold text-dark">Reset Link Generated</h2>
        <p class="text-muted">Your password reset link is ready. Click it to set a new password.</p>
    </div>

    <div class="alert alert-info">
        <strong>📌 Presentation Mode:</strong> 
        Since we are in a live demo environment, the reset link is displayed below instead of being sent via email.
        <br><small>In production, this link would be sent to <strong><?= htmlspecialchars($email) ?></strong></small>
    </div>

    <div class="link-box mb-4">
        <strong>🔗 Your Reset Link (click to proceed):</strong><br>
        <a href="<?= $reset_link ?>" target="_blank" class="fw-bold" style="color: #11998e;"><?= $reset_link ?></a>
    </div>

    <div class="text-center">
        <a href="<?= $reset_link ?>" class="btn btn-primary-custom">✅ Click Here to Reset Password</a>
    </div>

    <div class="text-center mt-3">
        <a href="forgot_password.php" class="text-muted small text-decoration-none">← Try with another email</a>
        <br>
        <a href="login.php" class="text-muted small text-decoration-none">← Back to Login</a>
    </div>
</div>
</body>
</html>
<?php
exit();
?>