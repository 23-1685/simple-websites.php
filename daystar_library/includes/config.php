<?php
// Database configuration
$host = 'localhost';
$dbname = 'daystar_library';
$username = 'root';      // default XAMPP user
$password = '';          // default XAMPP password is empty

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>