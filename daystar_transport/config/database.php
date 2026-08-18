<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'daystar_transport';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>