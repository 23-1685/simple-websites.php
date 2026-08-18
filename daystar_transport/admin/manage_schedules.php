<?php
include '../config/database.php';
include '../includes/header.php';

// Check if admin is logged in (simple check - you can improve this)
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

// Handle add schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_schedule'])) {
    $route = mysqli_real_escape_string($conn, $_POST['route']);
    $departure_location = mysqli_real_escape_string($conn, $_POST['departure_location']);
    $arrival_location = mysqli_real_escape_string($conn, $_POST['arrival_location']);
    $departure_time = mysqli_real_escape_string($conn, $_POST['departure_time']);
    $arrival_time = mysqli_real_escape_string($conn, $_POST['arrival_time']);
    $day_of_week = mysqli_real_escape_string($conn, $_POST['day_of_week']);
    $bus_type = mysqli_real_escape_string($conn, $_POST['bus_type']);
    $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    
    $insert_query = "INSERT INTO bus_schedules (route, departure_location, arrival_location, 
                     departure_time, arrival_time, day_of_week, bus_type, capacity) 
                     VALUES ('$route', '$departure_location', '$arrival_location', 
                     '$departure_time', '$arrival_time', '$day_of_week', '$bus_type', '$capacity')";
    
    if ($conn->query($insert_query)) {
        $success = "Schedule added successfully!";
    } else {
        $error = "Failed to add schedule: " . $conn->error;
    }
}

// Handle delete schedule
if (isset($_GET['delete']) && isset($_GET['schedule_id'])) {
    $schedule_id = mysqli_real_escape_string($conn, $_GET['schedule_id']);
    
    $delete_query = "DELETE FROM bus_schedules WHERE schedule_id = '$schedule_id'";
    if ($conn->query($delete_query)) {
        $success = "Schedule deleted successfully!";
    } else {
        $error = "Failed to delete schedule: " . $conn->error;
    }
}

// Handle toggle active status
if (isset($_GET['toggle']) && isset($_GET['schedule_id'])) {
    $schedule_id = mysqli_real_escape_string($conn, $_GET['schedule_id']);
    
    $toggle_query = "UPDATE bus_schedules SET active = NOT active WHERE schedule_id = '$schedule_id'";
    if ($conn->query($toggle_query)) {
        $success = "Schedule status updated!";
    } else {
        $error = "Failed to update schedule status: " . $conn->error;
    }
}

// Get all schedules
$schedules_query = "SELECT * FROM bus_schedules ORDER BY day_of_week, departure_time";
$schedules_result = $conn->query($schedules_query);
?>

<div class="main-container">
    <h1>Manage Bus Schedules</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <!-- Add New Schedule Form -->
    <div class="card">
        <h3 class="card-title">Add New Schedule</h3>
        <form method="POST" action="">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Route *</label>
                    <input type="text" name="route" class="form-control" required 
                           placeholder="e.g., Arthi River to Nairobi">
                </div>
                <div class="form-group">
                    <label>Day of Week *</label>
                    <select name="day_of_week" class="form-control" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Departure Location *</label>
                    <input type="text" name="departure_location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Arrival Location *</label>
                    <input type="text" name="arrival_location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Departure Time *</label>
                    <input type="time" name="departure_time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Arrival Time *</label>
                    <input type="time" name="arrival_time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Bus Type *</label>
                    <select name="bus_type" class="form-control" required>
                        <option value="regular">Regular</option>
                        <option value="sports">Sports</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="40">
                </div>
            </div>
            <button type="submit" name="add_schedule" class="btn btn-success">Add Schedule</button>
        </form>
    </div>
    
    <!-- Existing Schedules -->
    <div class="card" style="margin-top: 20px;">
        <h3 class="card-title">All Schedules</h3>
        
        <?php if ($schedules_result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Route</th>
                        <th>Day</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($schedule = $schedules_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $schedule['schedule_id']; ?></td>
                            <td><?php echo $schedule['route']; ?></td>
                            <td><?php echo $schedule['day_of_week']; ?></td>
                            <td><?php echo $schedule['departure_location']; ?></td>
                            <td><?php echo $schedule['arrival_location']; ?></td>
                            <td><?php echo date('h:i A', strtotime($schedule['departure_time'])); ?></td>
                            <td>
                                <?php if ($schedule['bus_type'] == 'sports'): ?>
                                    <span class="badge badge-confirmed">Sports</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">Regular</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($schedule['active']): ?>
                                    <span class="badge badge-confirmed">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-cancelled">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="manage_schedules.php?toggle=1&schedule_id=<?php echo $schedule['schedule_id']; ?>" 
                                   class="btn btn-info" style="padding: 5px 10px; font-size: 12px;">
                                    <?php echo $schedule['active'] ? 'Deactivate' : 'Activate'; ?>
                                </a>
                                <a href="manage_schedules.php?delete=1&schedule_id=<?php echo $schedule['schedule_id']; ?>" 
                                   class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;"
                                   onclick="return confirm('Are you sure you want to delete this schedule?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No schedules found. Add one above!</div>
        <?php endif; ?>
    </div>
    
    <div style="margin-top: 20px;">
        <a href="admin_dashboard.php" class="btn btn-info">Back to Admin Dashboard</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>