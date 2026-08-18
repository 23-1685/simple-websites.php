<?php
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loan_id = (int)$_POST['loan_id'];
    $return_date = $_POST['return_date'];

    if ($loan_id <= 0 || empty($return_date)) {
        $_SESSION['message'] = 'Invalid input.';
        $_SESSION['msg_type'] = 'error';
        header('Location: return.php');
        exit;
    }

    $loan = $conn->query("SELECT book_id, due_date FROM loans WHERE id = $loan_id AND status IN ('issued', 'overdue')")->fetch_assoc();
    if (!$loan) {
        $_SESSION['message'] = 'Loan not found or already returned.';
        $_SESSION['msg_type'] = 'error';
        header('Location: return.php');
        exit;
    }

    $fine = 0;
    if (strtotime($return_date) > strtotime($loan['due_date'])) {
        $days_late = ceil((strtotime($return_date) - strtotime($loan['due_date'])) / (60*60*24));
        $fine = $days_late * 0.50;
    }

    $stmt = $conn->prepare("UPDATE loans SET return_date = ?, status = 'returned', fine = ? WHERE id = ?");
    $stmt->bind_param("sdi", $return_date, $fine, $loan_id);
    if ($stmt->execute()) {
        $conn->query("UPDATE books SET available_copies = available_copies + 1 WHERE id = {$loan['book_id']}");
        $_SESSION['message'] = 'Book returned successfully. Fine: $' . number_format($fine, 2);
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error: ' . $stmt->error;
        $_SESSION['msg_type'] = 'error';
    }
    $stmt->close();
    header('Location: return.php');
    exit;
}