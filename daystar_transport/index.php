<?php
include 'config/database.php';
include 'includes/header.php';
?>

<div class="main-container">
    <h1>Welcome to Daystar University Transport System</h1>
    <p style="font-size: 18px; margin: 20px 0; color: #555;">
        Book your bus transportation easily and conveniently
    </p>

    <div class="grid" style="margin-top: 30px;">
        <div class="card">
            <h3 class="card-title">🚌 Regular Schedules</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Mon-Fri:</strong> 5:00 AM (Arthi River → Nairobi)
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Mon-Fri:</strong> 5:00 PM (Nairobi → Arthi River)
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Mon:</strong> 11:00 AM (Arthi River → Nairobi)
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Tue:</strong> 1:00 PM (Arthi River → Nairobi)
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Wed:</strong> 11:00 AM (Arthi River → Nairobi)
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Thu:</strong> 1:00 PM (Arthi River → Nairobi)
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Fri:</strong> 11:00 AM (Arthi River → Nairobi)
                </li>
            </ul>
        </div>

        <div class="card">
            <h3 class="card-title">⚽ Sports Schedules</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Tue-Thu:</strong> 2:00 PM (Arthi River → Sports Grounds)
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>Weekends:</strong> Sports and other activities
                </li>
            </ul>
        </div>

        <div class="card">
    <h3 class="card-title">📋 Quick Actions</h3>
    <?php if(isset($_SESSION['user_id'])): ?>
        <a href="booking.php" class="btn btn-primary" style="display: block; text-align: center; margin: 10px 0;">
            Book a Bus
        </a>
        <a href="manage_booking.php" class="btn btn-info" style="display: block; text-align: center; margin: 10px 0;">
            View My Bookings
        </a>
        <a href="download_report.php?type=full" class="btn" style="display: block; text-align: center; margin: 10px 0; background: #ffd700; color: #7b1fa2; font-weight: bold; padding: 12px; border-radius: 8px; text-decoration: none;">
            📊 Download Weekly Report
        </a>
    <?php else: ?>
        <a href="register.php" class="btn btn-primary" style="display: block; text-align: center; margin: 10px 0;">
            Register Now
        </a>
        <a href="login.php" class="btn btn-info" style="display: block; text-align: center; margin: 10px 0;">
            Login
        </a>
    <?php endif; ?>
</div>