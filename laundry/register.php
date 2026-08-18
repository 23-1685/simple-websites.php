<?php
session_start();
require_once "db.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($username) || empty($fullname) || empty($email) || empty($phone) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if ($password != $confirm) {
        $errors[] = "Passwords do not match.";
    }

    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $errors[] = "Email already exists.";
    }
    mysqli_stmt_close($check);

    $check_user = mysqli_prepare($conn, "SELECT id FROM users WHERE username=?");
    mysqli_stmt_bind_param($check_user, "s", $username);
    mysqli_stmt_execute($check_user);
    mysqli_stmt_store_result($check_user);
    if (mysqli_stmt_num_rows($check_user) > 0) {
        $errors[] = "Username already exists.";
    }
    mysqli_stmt_close($check_user);

    if (count($errors) == 0) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, fullname, email, phone, password, role) VALUES (?, ?, ?, ?, ?, 'customer')");
        mysqli_stmt_bind_param($stmt, "sssss", $username, $fullname, $email, $phone, $hash);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php?registered=1");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Daystar Digital Laundry</title>
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
            padding: 2rem 0;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            width: 100%;
            max-width: 500px;
            padding: 2.5rem;
        }
        .register-title {
            font-weight: 800;
            color: #0f6c48;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.65rem 1rem;
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
        .login-link {
            color: #11998e;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="text-center mb-4">
        <span style="font-size: 2.5rem;">🧺</span>
        <h2 class="register-title">Create Account</h2>
        <p class="text-muted">Register to start managing your laundry orders</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 ps-3">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="username" class="form-label text-muted small fw-bold">USERNAME</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="johndoe" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="fullname" class="form-label text-muted small fw-bold">FULL NAME</label>
                <input type="text" name="fullname" id="fullname" class="form-control" placeholder="John Doe" required value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>">
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label text-muted small fw-bold">PHONE NUMBER</label>
            <input type="text" name="phone" id="phone" class="form-control" placeholder="+254 700 000000" required value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label text-muted small fw-bold">PASSWORD</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="col-md-6 mb-4">
                <label for="confirm_password" class="form-label text-muted small fw-bold">CONFIRM PASSWORD</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-submit w-100 mb-3">Register</button>
    </form>

    <div class="text-center mt-2">
        <p class="text-muted small">Already have an account? <a href="login.php" class="login-link">Sign in here</a></p>
        <p class="mb-0"><a href="index.php" class="text-muted small">← Back to home</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>