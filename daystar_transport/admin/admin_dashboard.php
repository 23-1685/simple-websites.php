<?php
include '../config/database.php';
include '../includes/header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Get admin info
$admin_id = $_SESSION['user_id'];
$admin_name = $_SESSION['full_name'];

// ===== STATISTICS =====

// Total Users
$users_query = "SELECT COUNT(*) as total FROM users";
$users_result = $conn->query($users_query);
$total_users = $users_result->fetch_assoc()['total'];

// Total Students
$students_query = "SELECT COUNT(*) as total FROM users WHERE user_type = 'student'";
$students_result = $conn->query($students_query);
$total_students = $students_result->fetch_assoc()['total'];

// Total Staff
$staff_query = "SELECT COUNT(*) as total FROM users WHERE user_type = 'staff'";
$staff_result = $conn->query($staff_query);
$total_staff = $staff_result->fetch_assoc()['total'];

// Total Residents
$residents_query = "SELECT COUNT(*) as total FROM users WHERE user_type = 'resident'";
$residents_result = $conn->query($residents_query);
$total_residents = $residents_result->fetch_assoc()['total'];

// Total Schedules
$schedules_query = "SELECT COUNT(*) as total FROM bus_schedules";
$schedules_result = $conn->query($schedules_query);
$total_schedules = $schedules_result->fetch_assoc()['total'];

// Active Schedules
$active_schedules_query = "SELECT COUNT(*) as total FROM bus_schedules WHERE active = TRUE";
$active_schedules_result = $conn->query($active_schedules_query);
$active_schedules = $active_schedules_result->fetch_assoc()['total'];

// Total Bookings
$bookings_query = "SELECT COUNT(*) as total FROM bookings";
$bookings_result = $conn->query($bookings_query);
$total_bookings = $bookings_result->fetch_assoc()['total'];

// Today's Bookings
$today = date('Y-m-d');
$today_bookings_query = "SELECT COUNT(*) as total FROM bookings WHERE booking_date = '$today'";
$today_bookings_result = $conn->query($today_bookings_query);
$today_bookings = $today_bookings_result->fetch_assoc()['total'];

// Confirmed Bookings
$confirmed_query = "SELECT COUNT(*) as total FROM bookings WHERE status = 'confirmed'";
$confirmed_result = $conn->query($confirmed_query);
$confirmed_bookings = $confirmed_result->fetch_assoc()['total'];

// Cancelled Bookings
$cancelled_query = "SELECT COUNT(*) as total FROM bookings WHERE status = 'cancelled'";
$cancelled_result = $conn->query($cancelled_query);
$cancelled_bookings = $cancelled_result->fetch_assoc()['total'];

// Pending Bookings
$pending_query = "SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'";
$pending_result = $conn->query($pending_query);
$pending_bookings = $pending_result->fetch_assoc()['total'];

// ===== RECENT BOOKINGS =====
$recent_bookings_query = "SELECT b.*, u.full_name, u.email, u.phone, u.user_type, 
                          s.route, s.departure_location, s.arrival_location, 
                          s.departure_time, s.day_of_week
                          FROM bookings b 
                          JOIN users u ON b.user_id = u.user_id 
                          JOIN bus_schedules s ON b.schedule_id = s.schedule_id 
                          ORDER BY b.booking_date DESC, b.booking_time DESC 
                          LIMIT 10";
$recent_bookings = $conn->query($recent_bookings_query);

// ===== WEEKLY BOOKINGS CHART DATA =====
$week_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$weekly_data = [];
foreach ($week_days as $day) {
    $count_query = "SELECT COUNT(*) as total FROM bookings b 
                    JOIN bus_schedules s ON b.schedule_id = s.schedule_id 
                    WHERE s.day_of_week = '$day'";
    $count_result = $conn->query($count_query);
    $weekly_data[$day] = $count_result->fetch_assoc()['total'];
}

// ===== BUS TYPE STATISTICS =====
$regular_buses = $conn->query("SELECT COUNT(*) as total FROM bus_schedules WHERE bus_type = 'regular' AND active = TRUE")->fetch_assoc()['total'];
$sports_buses = $conn->query("SELECT COUNT(*) as total FROM bus_schedules WHERE bus_type = 'sports' AND active = TRUE")->fetch_assoc()['total'];
?>

<div class="main-container">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; border-radius: 15px; color: white;">
        <div>
            <h1 style="color: white; margin: 0;">👑 Admin Dashboard</h1>
            <p style="color: rgba(255,255,255,0.9); margin: 5px 0 0 0;">Welcome back, <strong><?php echo $admin_name; ?></strong>!</p>
        </div>
        <div>
            <a href="../logout.php" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">Logout</a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3><?php echo $total_users; ?></h3>
            <p>Total Users</p>
            <small style="color: rgba(255,255,255,0.8);">
                👨‍🎓 <?php echo $total_students; ?> Students | 
                👨‍🏫 <?php echo $total_staff; ?> Staff | 
                🏠 <?php echo $total_residents; ?> Residents
            </small>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h3><?php echo $total_bookings; ?></h3>
            <p>Total Bookings</p>
            <small style="color: rgba(255,255,255,0.8);">
                ✅ <?php echo $confirmed_bookings; ?> Confirmed | 
                ❌ <?php echo $cancelled_bookings; ?> Cancelled
            </small>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h3><?php echo $today_bookings; ?></h3>
            <p>Today's Bookings</p>
            <small style="color: rgba(255,255,255,0.8);"><?php echo date('F d, Y'); ?></small>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h3><?php echo $active_schedules; ?>/<?php echo $total_schedules; ?></h3>
            <p>Active Schedules</p>
            <small style="color: rgba(255,255,255,0.8);">
                🚌 <?php echo $regular_buses; ?> Regular | 
                ⚽ <?php echo $sports_buses; ?> Sports
            </small>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h3 class="card-title">⚡ Quick Actions</h3>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="manage_schedules.php" class="btn btn-primary">
                📅 Manage Schedules
            </a>
            <a href="view_bookings.php" class="btn btn-info">
                📋 View All Bookings
            </a>
            <a href="../download_report.php?type=full" class="btn" style="background: #ffd700; color: #7b1fa2; font-weight: bold;">
                📊 Download Full Report
            </a>
            <a href="../view_schedules.php" class="btn btn-success">
                📖 View Schedules
            </a>
        </div>
    </div>

    <!-- Weekly Booking Chart -->
    <div class="card">
        <h3 class="card-title">📊 Weekly Bookings Overview</h3>
        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; margin-top: 20px;">
            <?php foreach ($week_days as $day): ?>
                <?php 
                $count = $weekly_data[$day];
                $max_count = max($weekly_data) > 0 ? max($weekly_data) : 1;
                $height_percentage = ($count / $max_count) * 100;
                $is_today = ($day == date('l'));
                ?>
                <div style="text-align: center;">
                    <div style="background: <?php echo $is_today ? '#7b1fa2' : '#e0e0e0'; ?>; 
                                height: <?php echo max(10, $height_percentage); ?>px; 
                                width: 100%; 
                                border-radius: 5px 5px 0 0;
                                transition: all 0.3s;">
                    </div>
                    <div style="padding: 5px 0;">
                        <strong style="font-size: 14px; <?php echo $is_today ? 'color: #7b1fa2;' : ''; ?>">
                            <?php echo substr($day, 0, 3); ?>
                        </strong>
                        <br>
                        <span style="font-size: 12px; <?php echo $is_today ? 'color: #7b1fa2; font-weight: bold;' : ''; ?>">
                            <?php echo $count; ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <p style="text-align: center; color: #666; font-size: 14px; margin-top: 15px;">
            📌 Today is <strong style="color: #7b1fa2;"><?php echo date('l'); ?></strong>
        </p>
    </div>

    <!-- Booking Status Distribution -->
    <div class="card">
        <h3 class="card-title">📈 Booking Status Distribution</h3>
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 15px;">
            <div style="flex: 1; min-width: 150px; background: #d4edda; padding: 15px; border-radius: 10px; text-align: center;">
                <h4 style="color: #155724; margin: 0;"><?php echo $confirmed_bookings; ?></h4>
                <p style="color: #155724; margin: 5px 0 0 0;">✅ Confirmed</p>
            </div>
            <div style="flex: 1; min-width: 150px; background: #f8d7da; padding: 15px; border-radius: 10px; text-align: center;">
                <h4 style="color: #721c24; margin: 0;"><?php echo $cancelled_bookings; ?></h4>
                <p style="color: #721c24; margin: 5px 0 0 0;">❌ Cancelled</p>
            </div>
            <div style="flex: 1; min-width: 150px; background: #d1ecf1; padding: 15px; border-radius: 10px; text-align: center;">
                <h4 style="color: #0c5460; margin: 0;"><?php echo $pending_bookings; ?></h4>
                <p style="color: #0c5460; margin: 5px 0 0 0;">⏳ Pending</p>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card">
        <h3 class="card-title">🔄 Recent Bookings</h3>
        <?php if ($recent_bookings->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Passenger</th>
                        <th>Email</th>
                        <th>Route</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Seat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $booking['booking_id']; ?></td>
                            <td><?php echo $booking['full_name']; ?></td>
                            <td><?php echo $booking['email']; ?></td>
                            <td><?php echo $booking['route']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                            <td><?php echo date('h:i A', strtotime($booking['departure_time'])); ?></td>
                            <td><?php echo $booking['seat_number']; ?></td>
                            <td>
                                <?php if ($booking['status'] == 'confirmed'): ?>
                                    <span class="badge badge-confirmed">Confirmed</span>
                                <?php elseif ($booking['status'] == 'cancelled'): ?>
                                    <span class="badge badge-cancelled">Cancelled</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">Pending</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div style="margin-top: 15px; text-align: right;">
                <a href="view_bookings.php" class="btn btn-info">View All Bookings →</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No bookings found yet.</div>
        <?php endif; ?>
    </div>

    <!-- System Info -->
    <div class="card" style="background: #f8f9fa; border: 1px solid #e0e0e0;">
        <h3 class="card-title">🖥️ System Information</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <div>
                <strong>System Name:</strong>
                <p>Daystar Transport System</p>
            </div>
            <div>
                <strong>Version:</strong>
                <p>1.0.0</p>
            </div>
            <div>
                <strong>Admin Since:</strong>
                <p><?php echo date('F d, Y'); ?></p>
            </div>
            <div>
                <strong>Database:</strong>
                <p>MySQL</p>
            </div>
            <div>
                <strong>Total Schedules:</strong>
                <p><?php echo $total_schedules; ?></p>
            </div>
            <div>
                <strong>Total Users:</strong>
                <p><?php echo $total_users; ?></p>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>