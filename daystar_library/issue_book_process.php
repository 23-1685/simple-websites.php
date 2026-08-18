<?php
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = (int)$_POST['book_id'];
    $student_id = (int)$_POST['student_id'];
    $issue_date = $_POST['issue_date'];
    $due_date = $_POST['due_date'];

    if ($book_id <= 0 || $student_id <= 0 || empty($issue_date) || empty($due_date)) {
        $_SESSION['message'] = 'All fields are required.';
        $_SESSION['msg_type'] = 'error';
        header('Location: issue.php');
        exit;
    }

    $book = $conn->query("SELECT available_copies FROM books WHERE id = $book_id")->fetch_assoc();
    if (!$book || $book['available_copies'] < 1) {
        $_SESSION['message'] = 'No available copies.';
        $_SESSION['msg_type'] = 'error';
        header('Location: issue.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO loans (book_id, student_id, issue_date, due_date, status) VALUES (?, ?, ?, ?, 'issued')");
    $stmt->bind_param("iiss", $book_id, $student_id, $issue_date, $due_date);
    if ($stmt->execute()) {
        $conn->query("UPDATE books SET available_copies = available_copies - 1 WHERE id = $book_id");
        $_SESSION['message'] = 'Book issued successfully.';
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error: ' . $stmt->error;
        $_SESSION['msg_type'] = 'error';
    }
    $stmt->close();
    header('Location: issue.php');
    exit;
}