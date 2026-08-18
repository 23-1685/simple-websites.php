<?php
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $isbn = $_POST['isbn'] ?? '';
    $category_id = $_POST['category_id'] ?: null;
    $description = $_POST['description'] ?? '';
    $total_copies = (int)($_POST['total_copies'] ?? 1);
    $available_copies = (int)($_POST['available_copies'] ?? 1);

    if (empty($title) || empty($author)) {
        $_SESSION['message'] = 'Title and author are required.';
        $_SESSION['msg_type'] = 'error';
        header('Location: books.php');
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, category_id, description, total_copies, available_copies) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssii", $title, $author, $isbn, $category_id, $description, $total_copies, $available_copies);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Book added successfully.';
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error: ' . $stmt->error;
        $_SESSION['msg_type'] = 'error';
    }
    $stmt->close();
    header('Location: books.php');
    exit;
}