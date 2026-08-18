<?php
// Database Configuration - Daystar Digital Laundry

$host = "localhost";
$user = "root";
$password = "";
$database = "laundry_db";

// 1. Enable strict error reporting for development (helps catch SQL bugs early)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 2. Establish the connection
$conn = mysqli_connect($host, $user, $password, $database);

// 3. Check connection (this is your existing logic)
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// 4. 🔥 NEW: Set the character set to UTF-8 (supports emojis, special characters, and African names)
if (!mysqli_set_charset($conn, "utf8mb4")) {
    die("Error loading character set utf8mb4: " . mysqli_error($conn));
}

// 5. Set timezone (Your existing line - keep it!)
date_default_timezone_set("Africa/Nairobi");

// Optional: Uncomment the line below to confirm your DB is working during development
// echo "✅ Database connected successfully!";
?>