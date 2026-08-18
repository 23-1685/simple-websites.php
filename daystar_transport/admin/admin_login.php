<?php
include '../config/database.php';
include '../includes/header.php';

// If already logged in as admin, redirect to dashboard
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'admin') {
    header('Location: admin_dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password';
    } else {
        // Check if user exists and is admin
        $query = "SELECT * FROM users WHERE email = '$email' AND user_type = 'admin'";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['email'] = $user['email'];
                
                header('Location: admin_dashboard.php');
                exit();
            } else {
                $error = 'Invalid password';
            }
        } else {
            $error = 'Admin account not found';
        }
    }
}
?>

<div class="main-container auth-container">
    <h2>👑 Admin Login</h2>
    <p style="color: #666; margin-bottom: 20px;">Login to access the admin dashboard</p>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required placeholder="admin@daystar.edu">
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Login as Admin</button>
    </form>
    
    <p style="text-align: center; margin-top: 20px;">
        <a href="../index.php">← Back to Home</a>
    </p>
</div>

<?php include '../includes/footer.php'; ?>