<?php
// reset_password.php
session_start();
ob_start();

// ✅ FIXED: Using your correct database file
require_once __DIR__ . "/db.php";

$error = "";
$success = "";
$email = "";

// Validate the token
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']);
    $query = "SELECT * FROM password_resets WHERE token='$token' AND expires_at > NOW()";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 0) {
        $error = "❌ Invalid or expired token. Please request a new reset link.";
    } else {
        $reset_data = mysqli_fetch_assoc($result);
        $email = $reset_data['email'];
    }
} else {
    $error = "No token provided.";
}

// Handle the password update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Update the user's password
    $update = "UPDATE users SET password='$new_password' WHERE email='$email'";
    if (mysqli_query($conn, $update)) {
        // Delete the used token
        mysqli_query($conn, "DELETE FROM password_resets WHERE email='$email'");
        $success = "✅ Password updated successfully! You can now login with your new password.";
    } else {
        $error = "❌ Failed to update password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Daystar Laundry</title>
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
        .reset-card {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            padding: 2.5rem;
            max-width: 450px;
            width: 100%;
        }
        .reset-title {
            font-weight: 800;
            color: #0f6c48;
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
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

<div class="reset-card">
    <div class="text-center mb-4">
        <span style="font-size: 3rem;">🔐</span>
        <h2 class="reset-title">Set New Password</h2>
        <p class="text-muted">Enter your new password below.</p>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="text-center mt-3">
            <a href="login.php" class="btn btn-submit w-100">Go to Login →</a>
        </div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="text-center mt-3">
            <a href="forgot_password.php" class="btn btn-outline-secondary w-100">Request New Link</a>
        </div>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <div class="mb-3">
                <label for="password" class="form-label text-muted small fw-bold">NEW PASSWORD</label>
                <input type="password" name="password" id="password" class="form-control" minlength="6" required placeholder="Minimum 6 characters">
                <small class="text-muted">Password must be at least 6 characters long.</small>
            </div>
            <button type="submit" class="btn btn-submit w-100">Update Password</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php" class="text-muted small text-decoration-none">← Back to Login</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>