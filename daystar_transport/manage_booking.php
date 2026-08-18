<?php
include 'config/database.php';
include 'includes/header.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle cancellation
if (isset($_GET['cancel']) && isset($_GET['booking_id'])) {
    $booking_id = mysqli_real_escape_string($conn, $_GET['booking_id']);
    
    // Verify this booking belongs to the user
    $verify_query = "SELECT * FROM bookings WHERE booking_id = '$booking_id' AND user_id = '$user_id'";
    $verify_result = $conn->query($verify_query);
    
    if ($verify_result->num_rows > 0) {
        $update_query = "UPDATE bookings SET status = 'cancelled' WHERE booking_id = '$booking_id'";
        if ($conn->query($update_query)) {
            $success = "Booking cancelled successfully!";
        } else {
            $error = "Failed to cancel booking: " . $conn->error;
        }
    } else {
        $error = "You don't have permission to cancel this booking.";
    }
}

// Get all bookings for the user
$bookings_query = "SELECT b.*, s.route, s.departure_location, s.arrival_location, 
                   s.departure_time, s.day_of_week, s.bus_type
                   FROM bookings b 
                   JOIN bus_schedules s ON b.schedule_id = s.schedule_id 
                   WHERE b.user_id = '$user_id' 
                   ORDER BY b.booking_date DESC, s.departure_time DESC";
$bookings_result = $conn->query($bookings_query);
?>

<div class="main-container">
    <h1>My Bookings</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <h3 class="card-title">Your Booking History</h3>
        
        <?php if ($bookings_result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Route</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Seat</th>
                        <th>Bus Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $booking['booking_id']; ?></td>
                            <td><?php echo $booking['route']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                            <td><?php echo $booking['day_of_week']; ?></td>
                            <td><?php echo date('h:i A', strtotime($booking['departure_time'])); ?></td>
                            <td>Seat <?php echo $booking['seat_number']; ?></td>
                            <td>
                                <?php if ($booking['bus_type'] == 'sports'): ?>
                                    <span class="badge badge-confirmed">Sports</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">Regular</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($booking['status'] == 'confirmed'): ?>
                                    <span class="badge badge-confirmed">Confirmed</span>
                                <?php elseif ($booking['status'] == 'cancelled'): ?>
                                    <span class="badge badge-cancelled">Cancelled</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($booking['status'] == 'confirmed'): ?>
                                    <a href="manage_booking.php?cancel=1&booking_id=<?php echo $booking['booking_id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Are you sure you want to cancel this booking?');">
                                        Cancel
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">No action</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                You have no bookings yet. <a href="booking.php">Book a bus now!</a>
            </div>
        <?php endif; ?>
    </div>
    
    <div style="margin-top: 20px;">
        <a href="booking.php" class="btn btn-primary">Book New Trip</a>
        <a href="dashboard.php" class="btn btn-info">Back to Dashboard</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>