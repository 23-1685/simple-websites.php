<?php
session_start();
require_once "../db.php";

// Admin auth check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn, "DELETE FROM orders WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: orders.php?deleted=1");
    } else {
        header("Location: orders.php?error=delete_failed");
    }
    mysqli_stmt_close($stmt);
} else {
    header("Location: orders.php");
}
exit();
?>
