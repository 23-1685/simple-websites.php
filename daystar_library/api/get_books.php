<?php
header('Content-Type: application/json');
include '../includes/config.php';

$result = $conn->query("SELECT id, title, author, available_copies FROM books ORDER BY title");
$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}
echo json_encode($books);