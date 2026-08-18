<?php
$page_title = 'Dashboard';
$page_subtitle = 'Overview of library statistics';
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

include 'includes/header.php';

// Fetch statistics
$total_books = $conn->query("SELECT SUM(total_copies) as total FROM books")->fetch_assoc()['total'] ?? 0;
$total_students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'] ?? 0;
$total_issued = $conn->query("SELECT COUNT(*) as total FROM loans WHERE status IN ('issued', 'overdue')")->fetch_assoc()['total'] ?? 0;
$total_available = $conn->query("SELECT SUM(available_copies) as total FROM books")->fetch_assoc()['total'] ?? 0;

// Recent loans
$recent_loans = $conn->query("
    SELECT l.*, b.title as book_title, s.full_name as student_name 
    FROM loans l
    JOIN books b ON l.book_id = b.id
    JOIN students s ON l.student_id = s.id
    ORDER BY l.created_at DESC LIMIT 5
");
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-book"></i></div>
        <div class="stat-info"><h3><?php echo $total_books; ?></h3><p>Total Books</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-users"></i></div>
        <div class="stat-info"><h3><?php echo $total_students; ?></h3><p>Total Students</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-hand-holding-heart"></i></div>
        <div class="stat-info"><h3><?php echo $total_issued; ?></h3><p>Books Issued</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info"><h3><?php echo $total_available; ?></h3><p>Available Books</p></div>
    </div>
</div>

<!-- Recent Loans -->
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-clock"></i> Recent Loans</h3>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Book</th><th>Student</th><th>Issue Date</th><th>Due Date</th><th>Status</th></tr></thead>
                <tbody>
                    <?php if ($recent_loans->num_rows > 0): ?>
                        <?php while($row = $recent_loans->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo $row['issue_date']; ?></td>
                                <td><?php echo $row['due_date']; ?></td>
                                <td><span class="status-badge <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No recent loans</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>