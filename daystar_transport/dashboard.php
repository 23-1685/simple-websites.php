<?php
include 'config/database.php';
include 'includes/header.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$user_type = $_SESSION['user_type'];

// Get user's bookings count
$bookings_query = "SELECT COUNT(*) as total_bookings FROM bookings WHERE user_id = '$user_id'";
$bookings_result = $conn->query($bookings_query);
$total_bookings = $bookings_result->fetch_assoc()['total_bookings'];

// Get upcoming bookings
$upcoming_query = "SELECT b.*, s.route, s.departure_location, s.arrival_location, s.departure_time, s.day_of_week 
                   FROM bookings b 
                   JOIN bus_schedules s ON b.schedule_id = s.schedule_id 
                   WHERE b.user_id = '$user_id' AND b.status = 'confirmed' AND b.booking_date >= CURDATE()
                   ORDER BY b.booking_date ASC, s.departure_time ASC LIMIT 5";
$upcoming_result = $conn->query($upcoming_query);
?>

<div class="main-container">
    <h1>Welcome, <?php echo $full_name; ?>! 👋</h1>
    <p style="color: #666; margin-bottom: 30px;">User Type: <strong><?php echo ucfirst($user_type); ?></strong></p>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3><?php echo $total_bookings; ?></h3>
            <p>Total Bookings</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h3><?php echo $upcoming_result->num_rows; ?></h3>
            <p>Upcoming Trips</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h3>View</h3>
            <p>Available Schedules</p>
        </div>
    </div>
    
    <div class="card">
    <h3 class="card-title">Quick Actions</h3>
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        <a href="booking.php" class="btn btn-primary">Book a Bus</a>
        <a href="manage_booking.php" class="btn btn-info">My Bookings</a>
        <a href="view_schedules.php" class="btn btn-success">View Schedules</a>
        <a href="download_report.php?type=full" class="btn" style="background: #ffd700; color: #7b1fa2; font-weight: bold; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block;">
            📊 Download Report
        </a>
    </div>
</div>
    
    <?php if ($upcoming_result->num_rows > 0): ?>
        <div class="card">
            <h3 class="card-title">Upcoming Trips</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Route</th>
                        <th>Departure</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $upcoming_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                            <td><?php echo $booking['route']; ?></td>
                            <td><?php echo $booking['departure_location']; ?></td>
                            <td><?php echo date('h:i A', strtotime($booking['departure_time'])); ?></td>
                            <td><span class="badge badge-confirmed">Confirmed</span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            You have no upcoming trips. <a href="booking.php">Book a bus now!</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>