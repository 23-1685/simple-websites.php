<?php
include '../config/database.php';
include '../includes/header.php';

// Only allow admin registration if no admin exists (security)
$check_admin = "SELECT COUNT(*) as count FROM users WHERE user_type = 'admin'";
$admin_result = $conn->query($check_admin);
$admin_count = $admin_result->fetch_assoc()['count'];

$error = '';
$success = '';

// If admin already exists, show message
if ($admin_count > 0) {
    $error = 'An admin already exists. Please contact the system administrator.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $admin_count == 0) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $admin_secret = mysqli_real_escape_string($conn, $_POST['admin_secret']);
    
    // Admin secret key (change this to something secure)
    $SECRET_KEY = 'DaystarAdmin2026';
    
    // Validation
    if (empty($full_name) || empty($email) || empty($phone) || empty($password)) {
        $error = 'Please fill all required fields';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($admin_secret !== $SECRET_KEY) {
        $error = 'Invalid admin secret key';
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM users WHERE email = '$email'";
        $check_result = $conn->query($check_query);
        
        if ($check_result->num_rows > 0) {
            $error = 'Email already registered';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert admin user
            $insert_query = "INSERT INTO users (full_name, email, phone, user_type, password) 
                           VALUES ('$full_name', '$email', '$phone', 'admin', '$hashed_password')";
            
            if ($conn->query($insert_query)) {
                $success = 'Admin registration successful! Please login.';
                header('refresh:2;url=admin_login.php');
            } else {
                $error = 'Registration failed: ' . $conn->error;
            }
        }
    }
}
?>

<div class="main-container auth-container">
    <h2>👑 Admin Registration</h2>
    <p style="color: #666; margin-bottom: 20px;">Register as a system administrator</p>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if ($admin_count == 0): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Phone Number *</label>
                <input type="tel" name="phone" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Admin Secret Key *</label>
                <input type="password" name="admin_secret" class="form-control" required>
                <small style="color: #666;">Contact system administrator for the secret key</small>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Register as Admin</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Already have an admin account? <a href="admin_login.php">Login here</a>
        </p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>