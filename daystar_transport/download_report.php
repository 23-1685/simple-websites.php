<?php
include 'config/database.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the report type
$report_type = isset($_GET['type']) ? $_GET['type'] : 'schedules';

// Get current week dates
$today = date('Y-m-d');
$week_start = date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime('sunday this week'));

// Set filename
$filename = "Daystar_Transport_Report_" . date('Y-m-d') . ".csv";

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Create output stream
$output = fopen('php://output', 'w');

// Add UTF-8 BOM for Excel compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

if ($report_type == 'schedules') {
    // ========== SCHEDULES REPORT ==========
    
    // Add title
    fputcsv($output, ['DAYSTAR UNIVERSITY TRANSPORT SYSTEM']);
    fputcsv($output, ['WEEKLY SCHEDULES REPORT']);
    fputcsv($output, ['Report Date: ' . date('F d, Y')]);
    fputcsv($output, ['Week: ' . date('F d', strtotime($week_start)) . ' - ' . date('F d, Y', strtotime($week_end))]);
    fputcsv($output, []);
    
    // Column headers
    fputcsv($output, [
        'Schedule ID',
        'Day of Week',
        'Route',
        'Departure Location',
        'Arrival Location',
        'Departure Time',
        'Arrival Time',
        'Bus Type',
        'Capacity',
        'Status'
    ]);
    
    // Get all schedules
    $query = "SELECT * FROM bus_schedules ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), departure_time";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['schedule_id'],
                $row['day_of_week'],
                $row['route'],
                $row['departure_location'],
                $row['arrival_location'],
                date('h:i A', strtotime($row['departure_time'])),
                date('h:i A', strtotime($row['arrival_time'])),
                ucfirst($row['bus_type']),
                $row['capacity'],
                $row['active'] ? 'Active' : 'Inactive'
            ]);
        }
    }
    
    // Add summary
    fputcsv($output, []);
    fputcsv($output, ['SUMMARY']);
    fputcsv($output, ['Total Schedules: ' . $result->num_rows]);
    
    // Count by type
    $regular_count = $conn->query("SELECT COUNT(*) as count FROM bus_schedules WHERE bus_type = 'regular' AND active = TRUE")->fetch_assoc()['count'];
    $sports_count = $conn->query("SELECT COUNT(*) as count FROM bus_schedules WHERE bus_type = 'sports' AND active = TRUE")->fetch_assoc()['count'];
    
    fputcsv($output, ['Regular Buses: ' . $regular_count]);
    fputcsv($output, ['Sports Buses: ' . $sports_count]);
    
} elseif ($report_type == 'bookings') {
    // ========== BOOKINGS REPORT ==========
    
    // Add title
    fputcsv($output, ['DAYSTAR UNIVERSITY TRANSPORT SYSTEM']);
    fputcsv($output, ['WEEKLY BOOKINGS REPORT']);
    fputcsv($output, ['Report Date: ' . date('F d, Y')]);
    fputcsv($output, ['Week: ' . date('F d', strtotime($week_start)) . ' - ' . date('F d, Y', strtotime($week_end))]);
    fputcsv($output, []);
    
    // Column headers
    fputcsv($output, [
        'Booking ID',
        'Passenger Name',
        'Email',
        'Phone',
        'User Type',
        'Route',
        'Departure Location',
        'Arrival Location',
        'Day',
        'Date',
        'Time',
        'Seat Number',
        'Bus Type',
        'Booking Status',
        'Booking Date'
    ]);
    
    // Get all bookings for the week
    $query = "SELECT b.*, u.full_name, u.email, u.phone, u.user_type, 
                     s.route, s.departure_location, s.arrival_location, s.day_of_week, 
                     s.departure_time, s.bus_type
              FROM bookings b 
              JOIN users u ON b.user_id = u.user_id 
              JOIN bus_schedules s ON b.schedule_id = s.schedule_id 
              WHERE b.booking_date BETWEEN '$week_start' AND '$week_end'
              ORDER BY b.booking_date DESC, s.departure_time";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['booking_id'],
                $row['full_name'],
                $row['email'],
                $row['phone'],
                ucfirst($row['user_type']),
                $row['route'],
                $row['departure_location'],
                $row['arrival_location'],
                $row['day_of_week'],
                date('M d, Y', strtotime($row['booking_date'])),
                date('h:i A', strtotime($row['departure_time'])),
                $row['seat_number'],
                ucfirst($row['bus_type']),
                ucfirst($row['status']),
                date('M d, Y h:i A', strtotime($row['booking_time']))
            ]);
        }
    }
    
    // Add summary
    fputcsv($output, []);
    fputcsv($output, ['SUMMARY']);
    fputcsv($output, ['Total Bookings: ' . $result->num_rows]);
    
    // Count by status
    $confirmed = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'confirmed' AND booking_date BETWEEN '$week_start' AND '$week_end'")->fetch_assoc()['count'];
    $cancelled = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'cancelled' AND booking_date BETWEEN '$week_start' AND '$week_end'")->fetch_assoc()['count'];
    $pending = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending' AND booking_date BETWEEN '$week_start' AND '$week_end'")->fetch_assoc()['count'];
    
    fputcsv($output, ['Confirmed: ' . $confirmed]);
    fputcsv($output, ['Cancelled: ' . $cancelled]);
    fputcsv($output, ['Pending: ' . $pending]);
    
} elseif ($report_type == 'full') {
    // ========== FULL REPORT ==========
    
    // Add title
    fputcsv($output, ['DAYSTAR UNIVERSITY TRANSPORT SYSTEM']);
    fputcsv($output, ['COMPLETE WEEKLY REPORT']);
    fputcsv($output, ['Report Date: ' . date('F d, Y')]);
    fputcsv($output, ['Week: ' . date('F d', strtotime($week_start)) . ' - ' . date('F d, Y', strtotime($week_end))]);
    fputcsv($output, []);
    
    // ===== SCHEDULES SECTION =====
    fputcsv($output, ['=== BUS SCHEDULES ===']);
    fputcsv($output, [
        'Day',
        'Route',
        'From',
        'To',
        'Time',
        'Type',
        'Status'
    ]);
    
    $sched_query = "SELECT * FROM bus_schedules ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), departure_time";
    $sched_result = $conn->query($sched_query);
    
    if ($sched_result->num_rows > 0) {
        while ($row = $sched_result->fetch_assoc()) {
            fputcsv($output, [
                $row['day_of_week'],
                $row['route'],
                $row['departure_location'],
                $row['arrival_location'],
                date('h:i A', strtotime($row['departure_time'])),
                ucfirst($row['bus_type']),
                $row['active'] ? 'Active' : 'Inactive'
            ]);
        }
    }
    fputcsv($output, []);
    
    // ===== BOOKINGS SECTION =====
    fputcsv($output, ['=== WEEKLY BOOKINGS ===']);
    fputcsv($output, [
        'Booking ID',
        'Passenger',
        'Route',
        'Date',
        'Time',
        'Seat',
        'Status'
    ]);
    
    $book_query = "SELECT b.booking_id, u.full_name, s.route, b.booking_date, s.departure_time, b.seat_number, b.status
                   FROM bookings b 
                   JOIN users u ON b.user_id = u.user_id 
                   JOIN bus_schedules s ON b.schedule_id = s.schedule_id 
                   WHERE b.booking_date BETWEEN '$week_start' AND '$week_end'
                   ORDER BY b.booking_date DESC";
    $book_result = $conn->query($book_query);
    
    if ($book_result->num_rows > 0) {
        while ($row = $book_result->fetch_assoc()) {
            fputcsv($output, [
                $row['booking_id'],
                $row['full_name'],
                $row['route'],
                date('M d, Y', strtotime($row['booking_date'])),
                date('h:i A', strtotime($row['departure_time'])),
                $row['seat_number'],
                ucfirst($row['status'])
            ]);
        }
    } else {
        fputcsv($output, ['No bookings this week']);
    }
    fputcsv($output, []);
    
    // ===== STATISTICS =====
    fputcsv($output, ['=== STATISTICS ===']);
    
    $total_schedules = $conn->query("SELECT COUNT(*) as count FROM bus_schedules WHERE active = TRUE")->fetch_assoc()['count'];
    $total_bookings_week = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_date BETWEEN '$week_start' AND '$week_end'")->fetch_assoc()['count'];
    $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
    $confirmed_week = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'confirmed' AND booking_date BETWEEN '$week_start' AND '$week_end'")->fetch_assoc()['count'];
    
    fputcsv($output, ['Total Active Schedules: ' . $total_schedules]);
    fputcsv($output, ['Total Bookings This Week: ' . $total_bookings_week]);
    fputcsv($output, ['Confirmed Bookings: ' . $confirmed_week]);
    fputcsv($output, ['Total Registered Users: ' . $total_users]);
}

// Close the output
fclose($output);
exit();
?>