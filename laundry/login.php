<?php
session_start();
require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, username, fullname, password, role FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == "admin") {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: customer/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Daystar Digital Laundry</title>
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
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
        }
        .login-title {
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
        .register-link {
            color: #11998e;
            text-decoration: none;
            font-weight: 600;
        }
        .register-link:hover {
            text-decoration: underline;
        }
        /* 🔑 ADDED STYLE FOR THE FORGOT PASSWORD LINK */
        .forgot-link {
            color: #11998e;
            font-weight: 500;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .forgot-link:hover {
            text-decoration: underline;
            color: #0d7a64;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <span style="font-size: 3rem;">🧺</span>
        <h2 class="login-title">Daystar Laundry</h2>
        <p class="text-muted">Sign in to manage your orders</p>
    </div>

    <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Registration successful! Please login below.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="email" class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>
        
        <div class="mb-3"> <!-- Changed from mb-4 to mb-3 for better spacing -->
            <label for="password" class="form-label text-muted small fw-bold">PASSWORD</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            
            <!-- 🔑 ADDED FORGOT PASSWORD LINK HERE -->
            <div class="text-end mt-1">
                <a href="forgot_password.php" class="forgot-link">🔑 Forgot your password?</a>
            </div>
        </div>

        <button type="submit" class="btn btn-submit w-100 mb-3">Sign In</button>
    </form>

    <div class="text-center mt-3">
        <p class="text-muted small">Don't have an account? <a href="register.php" class="register-link">Register here</a></p>
        <p class="mb-0"><a href="index.php" class="text-muted small">← Back to home</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>