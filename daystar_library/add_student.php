<?php
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $department = $_POST['department'] ?? '';

    if (empty($student_id) || empty($full_name) || empty($email)) {
        $_SESSION['message'] = 'Student ID, name, and email are required.';
        $_SESSION['msg_type'] = 'error';
        header('Location: students.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, email, phone, department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $student_id, $full_name, $email, $phone, $department);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Student added successfully.';
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error: ' . $stmt->error;
        $_SESSION['msg_type'] = 'error';
    }
    $stmt->close();
    header('Location: students.php');
    exit;
}