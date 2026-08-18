<?php

require "../includes/admin_auth.php";

require "../db.php";

$id=$_POST['id'];

$status=$_POST['status'];

$stmt=mysqli_prepare(
$conn,
"UPDATE orders
SET status=?
WHERE id=?"
);

mysqli_stmt_bind_param(
$stmt,
"si",
$status,
$id
);

mysqli_stmt_execute($stmt);

header("Location: orders.php?updated=1");
exit();