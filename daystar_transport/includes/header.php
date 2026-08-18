<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daystar University Transport System</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">
                🚌 Daystar <span>Transport</span>
            </a>
            <nav class="nav">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="booking.php">Book a Bus</a>
                    <a href="view_schedules.php">Schedules</a>
                    <a href="manage_booking.php">My Bookings</a>
                    <a href="logout.php" style="color: #e74c3c;">Logout</a>
                <?php else: ?>
                    <a href="index.php">Home</a>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <div class="container">