<?php
include 'config/database.php';
include 'includes/header.php';
?>

<div class="main-container">
    <h1>Bus Schedules</h1>
    <p style="color: #666; margin-bottom: 30px;">View all available bus schedules at Daystar University</p>
    
    <!-- Filter by Day -->
    <div class="card">
        <h3 class="card-title">Filter by Day</h3>
        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin: 10px 0;">
            <a href="view_schedules.php?day=all" class="btn <?php echo (!isset($_GET['day']) || $_GET['day'] == 'all') ? 'btn-primary' : 'btn-info'; ?>">All Days</a>
            <a href="view_schedules.php?day=Monday" class="btn <?php echo (isset($_GET['day']) && $_GET['day'] == 'Monday') ? 'btn-primary' : 'btn-info'; ?>">Monday</a>
            <a href="view_schedules.php?day=Tuesday" class="btn <?php echo (isset($_GET['day']) && $_GET['day'] == 'Tuesday') ? 'btn-primary' : 'btn-info'; ?>">Tuesday</a>
            <a href="view_schedules.php?day=Wednesday" class="btn <?php echo (isset($_GET['day']) && $_GET['day'] == 'Wednesday') ? 'btn-primary' : 'btn-info'; ?>">Wednesday</a>
            <a href="view_schedules.php?day=Thursday" class="btn <?php echo (isset($_GET['day']) && $_GET['day'] == 'Thursday') ? 'btn-primary' : 'btn-info'; ?>">Thursday</a>
            <a href="view_schedules.php?day=Friday" class="btn <?php echo (isset($_GET['day']) && $_GET['day'] == 'Friday') ? 'btn-primary' : 'btn-info'; ?>">Friday</a>
            <a href="view_schedules.php?day=Saturday" class="btn <?php echo (isset($_GET['day']) && $_GET['day'] == 'Saturday') ? 'btn-primary' : 'btn-info'; ?>">Saturday</a>
            <a href="view_schedules.php?day=Sunday" class="btn <?php echo (isset($_GET['day']) && $_GET['day'] == 'Sunday') ? 'btn-primary' : 'btn-info'; ?>">Sunday</a>
        </div>
    </div>
    
    <!-- Regular Schedules -->
    <div class="card" style="margin-top: 20px;">
        <h3 class="card-title">🚌 Regular Bus Schedules</h3>
        
        <?php
        // Build query based on filter
        $day_filter = isset($_GET['day']) && $_GET['day'] != 'all' ? $_GET['day'] : '';
        
        if ($day_filter) {
            $query = "SELECT * FROM bus_schedules WHERE day_of_week = '$day_filter' AND bus_type = 'regular' AND active = TRUE ORDER BY departure_time";
        } else {
            $query = "SELECT * FROM bus_schedules WHERE bus_type = 'regular' AND active = TRUE ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), departure_time";
        }
        
        $regular_result = $conn->query($query);
        ?>
        
        <?php if ($regular_result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Route</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($schedule = $regular_result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $schedule['day_of_week']; ?></strong></td>
                            <td><?php echo $schedule['route']; ?></td>
                            <td><?php echo $schedule['departure_location']; ?></td>
                            <td><?php echo $schedule['arrival_location']; ?></td>
                            <td><span class="badge badge-confirmed"><?php echo date('h:i A', strtotime($schedule['departure_time'])); ?></span></td>
                            <td><?php echo date('h:i A', strtotime($schedule['arrival_time'])); ?></td>
                            <td><?php echo $schedule['capacity']; ?> seats</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                <?php if ($day_filter): ?>
                    No regular buses available on <?php echo $day_filter; ?>.
                <?php else: ?>
                    No regular buses available.
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Sports Schedules -->
    <div class="card" style="margin-top: 20px;">
        <h3 class="card-title">⚽ Sports Bus Schedules</h3>
        
        <?php
        if ($day_filter) {
            $query = "SELECT * FROM bus_schedules WHERE day_of_week = '$day_filter' AND bus_type = 'sports' AND active = TRUE ORDER BY departure_time";
        } else {
            $query = "SELECT * FROM bus_schedules WHERE bus_type = 'sports' AND active = TRUE ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), departure_time";
        }
        
        $sports_result = $conn->query($query);
        ?>
        
        <?php if ($sports_result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Route</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Capacity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($schedule = $sports_result->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $schedule['day_of_week']; ?></strong></td>
                            <td><?php echo $schedule['route']; ?></td>
                            <td><?php echo $schedule['departure_location']; ?></td>
                            <td><?php echo $schedule['arrival_location']; ?></td>
                            <td><span class="badge badge-confirmed"><?php echo date('h:i A', strtotime($schedule['departure_time'])); ?></span></td>
                            <td><?php echo date('h:i A', strtotime($schedule['arrival_time'])); ?></td>
                            <td><?php echo $schedule['capacity']; ?> seats</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                <?php if ($day_filter): ?>
                    No sports buses available on <?php echo $day_filter; ?>.
                <?php else: ?>
                    No sports buses available.
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Weekly Summary -->
    <div class="card" style="margin-top: 20px;">
        <h3 class="card-title">📅 Weekly Summary</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day):
                $count_query = "SELECT COUNT(*) as total FROM bus_schedules WHERE day_of_week = '$day' AND active = TRUE";
                $count_result = $conn->query($count_query);
                $count = $count_result->fetch_assoc()['total'];
                $is_today = ($day == date('l'));
            ?>
                <div style="background: <?php echo $is_today ? '#7b1fa2' : '#f5f5f5'; ?>; 
                            padding: 15px; 
                            border-radius: 10px; 
                            color: <?php echo $is_today ? 'white' : '#333'; ?>;
                            <?php echo $is_today ? 'border: 2px solid #7b1fa2;' : 'border: 1px solid #e0e0e0;'; ?>">
                    <h4 style="margin: 0 0 10px 0;">
                        <?php echo $day; ?>
                        <?php if ($is_today): ?>
                            <span style="font-size: 12px; background: rgba(255,255,255,0.3); padding: 2px 10px; border-radius: 20px;">Today</span>
                        <?php endif; ?>
                    </h4>
                    <p style="margin: 0; font-size: 24px; font-weight: bold;"><?php echo $count; ?></p>
                    <p style="margin: 0; font-size: 12px; opacity: 0.8;">schedules available</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Download Reports Section -->
<div class="card" style="margin-top: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 25px;">
    <h3 class="card-title" style="color: white; margin-top: 0;">📊 Download Weekly Reports</h3>
    <p style="margin-bottom: 20px; color: rgba(255,255,255,0.9);">Download comprehensive reports for the current week in CSV format</p>
    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
        <a href="download_report.php?type=schedules" class="btn" style="background: white; color: #7b1fa2; font-weight: bold; padding: 12px 25px; border-radius: 8px; text-decoration: none; display: inline-block;">
            📅 Schedule Report
        </a>
        <a href="download_report.php?type=bookings" class="btn" style="background: white; color: #7b1fa2; font-weight: bold; padding: 12px 25px; border-radius: 8px; text-decoration: none; display: inline-block;">
            📋 Bookings Report
        </a>
        <a href="download_report.php?type=full" class="btn" style="background: #ffd700; color: #7b1fa2; font-weight: bold; padding: 12px 25px; border-radius: 8px; text-decoration: none; display: inline-block;">
            📊 Full Report
        </a>
    </div>
</div>


<!-- Quick Actions -->
<div style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="booking.php" class="btn btn-primary">Book a Bus</a>
        <a href="manage_booking.php" class="btn btn-info">My Bookings</a>
    <?php else: ?>
        <a href="login.php" class="btn btn-primary">Login to Book</a>
        <a href="register.php" class="btn btn-success">Register Now</a>
    <?php endif; ?>
    <a href="index.php" class="btn btn-info">Back to Home</a>
</div>