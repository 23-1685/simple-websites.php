<?php
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $check = $conn->query("SELECT COUNT(*) as cnt FROM loans WHERE student_id = $id")->fetch_assoc()['cnt'];
    if ($check > 0) {
        $_SESSION['message'] = 'Cannot delete: student has loan records.';
        $_SESSION['msg_type'] = 'error';
    } else {
        $conn->query("DELETE FROM students WHERE id = $id");
        $_SESSION['message'] = 'Student deleted.';
        $_SESSION['msg_type'] = 'success';
    }
} else {
    $_SESSION['message'] = 'Invalid ID.';
    $_SESSION['msg_type'] = 'error';
}
header('Location: students.php');
exit;