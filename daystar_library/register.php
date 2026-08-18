<?php
// Register page – only works if no admin exists yet
include 'includes/config.php';

// Check if any admin exists
$result = $conn->query("SELECT COUNT(*) as count FROM admins");
$admin_count = $result->fetch_assoc()['count'];

if ($admin_count > 0) {
    // If admins exist, redirect to login
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Validate
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if username or email already exists (though no admin should exist)
        $check = $conn->prepare("SELECT id FROM admins WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = 'Username or email already taken.';
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admins (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password_hash);
            if ($stmt->execute()) {
                $success = 'Admin account created! You can now <a href="login.php">login</a>.';
                // Optionally, auto-login after registration? We'll let user login manually.
            } else {
                $error = 'Registration failed: ' . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Daystar Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .register-box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 420px; }
        .register-box h1 { font-size: 24px; margin-bottom: 8px; color: #1a3c6e; }
        .register-box p { color: #666; margin-bottom: 25px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; font-size: 13px; color: #333; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #1a3c6e; box-shadow: 0 0 0 3px rgba(26,60,110,0.1); }
        .btn { width: 100%; padding: 12px; background: #1a3c6e; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #0f2747; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .login-link { text-align: center; margin-top: 18px; font-size: 14px; }
        .login-link a { color: #1a3c6e; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
        .logo { text-align: center; margin-bottom: 20px; }
        .logo i { font-size: 48px; color: #e8a838; }
        .logo span { font-size: 20px; font-weight: 700; color: #1a3c6e; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="register-box">
        <div class="logo">
            <i class="fas fa-book-open"></i>
            <span>Daystar Library</span>
        </div>
        <h1>Create Admin Account</h1>
        <p>First‑time setup – register the system administrator.</p>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Password (min 6 chars)</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>