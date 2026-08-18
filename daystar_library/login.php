<?php
include 'includes/config.php';

// If already logged in, go to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password.';
    } else {
        // Fetch admin by username
        $stmt = $conn->prepare("SELECT id, username, password_hash FROM admins WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $admin['password_hash'])) {
                // Login success
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Daystar Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .login-box h1 { font-size: 24px; margin-bottom: 8px; color: #1a3c6e; }
        .login-box p { color: #666; margin-bottom: 25px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-weight: 600; font-size: 13px; color: #333; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px 14px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #1a3c6e; box-shadow: 0 0 0 3px rgba(26,60,110,0.1); }
        .btn { width: 100%; padding: 12px; background: #1a3c6e; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #0f2747; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .register-link { text-align: center; margin-top: 18px; font-size: 14px; }
        .register-link a { color: #1a3c6e; text-decoration: none; font-weight: 600; }
        .register-link a:hover { text-decoration: underline; }
        .logo { text-align: center; margin-bottom: 20px; }
        .logo i { font-size: 48px; color: #e8a838; }
        .logo span { font-size: 20px; font-weight: 700; color: #1a3c6e; margin-left: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">
            <i class="fas fa-book-open"></i>
            <span>Daystar Library</span>
        </div>
        <h1>Admin Login</h1>
        <p>Enter your credentials to manage the library.</p>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username or Email</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>