<!-- forgot_password.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Daystar Laundry - Reset Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Forgot Password?</h4>
        </div>
        <div class="card-body">
            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Reset link sent to your email. Check your inbox!</div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger">❌ Email not found in our system. Please try again.</div>
            <?php endif; ?>
            <form action="send_reset_link.php" method="POST">
                <div class="mb-3">
                    <label>Registered Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="student@daystar.ac.ke">
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                <p class="mt-3 text-center"><a href="login.php">← Back to Login</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>