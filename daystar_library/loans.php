<?php
$page_title = 'Loans';
$page_subtitle = 'All borrowing transactions';
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

include 'includes/header.php';

$loans = $conn->query("
    SELECT l.*, b.title as book_title, s.full_name as student_name
    FROM loans l
    JOIN books b ON l.book_id = b.id
    JOIN students s ON l.student_id = s.id
    ORDER BY l.created_at DESC
");
?>

<div class="section-header">
    <h2><i class="fas fa-hand-holding-heart"></i> Loan History</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>Book</th><th>Student</th><th>Issue Date</th><th>Due Date</th><th>Return Date</th><th>Status</th><th>Fine</th></tr>
                </thead>
                <tbody>
                    <?php if ($loans->num_rows > 0): ?>
                        <?php while($row = $loans->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo $row['issue_date']; ?></td>
                                <td><?php echo $row['due_date']; ?></td>
                                <td><?php echo $row['return_date'] ?? '-'; ?></td>
                                <td><span class="status-badge <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                                <td><?php echo number_format($row['fine'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No loans found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>