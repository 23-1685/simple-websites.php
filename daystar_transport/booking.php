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

// Get current day of week
$current_day = date('l'); // Monday, Tuesday, etc.
$current_date = date('Y-m-d');

// Get available schedules for today
$schedules_query = "SELECT * FROM bus_schedules WHERE day_of_week = '$current_day' AND active = TRUE ORDER BY departure_time";
$schedules_result = $conn->query($schedules_query);

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['schedule_id'])) {
    $schedule_id = mysqli_real_escape_string($conn, $_POST['schedule_id']);
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $seat_number = rand(1, 40); // Random seat assignment for demo
    
    // Check if seat is available
    $check_query = "SELECT * FROM bookings WHERE schedule_id = '$schedule_id' 
                   AND booking_date = '$booking_date' AND seat_number = '$seat_number'";
    $check_result = $conn->query($check_query);
    
    if ($check_result->num_rows > 0) {
        // Try another seat
        $seat_number = rand(1, 40);
    }
    
    // Insert booking
    $insert_query = "INSERT INTO bookings (user_id, schedule_id, booking_date, seat_number, status) 
                    VALUES ('$user_id', '$schedule_id', '$booking_date', '$seat_number', 'confirmed')";
    
    if ($conn->query($insert_query)) {
        $booking_id = $conn->insert_id;
        $success = "Booking confirmed! Your seat number is #$seat_number";
    } else {
        $error = "Booking failed: " . $conn->error;
    }
}
?>

<div class="main-container">
    <h1>Book a Bus</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <h3 class="card-title">Available Schedules for <?php echo $current_day; ?></h3>
        <p style="color: #666; margin-bottom: 20px;">Today is <?php echo date('F d, Y'); ?></p>
        
        <?php if ($schedules_result->num_rows > 0): ?>
            <?php while ($schedule = $schedules_result->fetch_assoc()): ?>
                <div class="booking-card">
                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap;">
                        <div>
                            <h4 style="color: #7b1fa2;"><?php echo $schedule['route']; ?></h4>
                            <p>
                                <strong>From:</strong> <?php echo $schedule['departure_location']; ?><br>
                                <strong>To:</strong> <?php echo $schedule['arrival_location']; ?><br>
                                <strong>Time:</strong> <?php echo date('h:i A', strtotime($schedule['departure_time'])); ?><br>
                                <?php if ($schedule['bus_type'] == 'sports'): ?>
                                    <span class="badge badge-confirmed">Sports Bus</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">Regular Bus</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <form method="POST" action="" onsubmit="return confirm('Confirm booking for <?php echo $schedule['route']; ?>?');">
                                <input type="hidden" name="schedule_id" value="<?php echo $schedule['schedule_id']; ?>">
                                <input type="hidden" name="booking_date" value="<?php echo $current_date; ?>">
                                <button type="submit" class="btn btn-primary">Book Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">
                No buses available today. Please check the schedule for other days.
            </div>
        <?php endif; ?>
    </div>
    
    <div class="card" style="margin-top: 20px;">
        <h3 class="card-title">Weekly Schedule Overview</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Departure Location</th>
                    <th>Arrival Location</th>
                    <th>Departure Time</th>
                    <th>Bus Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $all_schedules = $conn->query("SELECT * FROM bus_schedules WHERE active = TRUE ORDER BY day_of_week, departure_time");
                while ($schedule = $all_schedules->fetch_assoc()):
                ?>
                    <tr>
                        <td><strong><?php echo $schedule['day_of_week']; ?></strong></td>
                        <td><?php echo $schedule['departure_location']; ?></td>
                        <td><?php echo $schedule['arrival_location']; ?></td>
                        <td><?php echo date('h:i A', strtotime($schedule['departure_time'])); ?></td>
                        <td><?php echo ucfirst($schedule['bus_type']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>