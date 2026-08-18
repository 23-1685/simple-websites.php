<?php
$page_title = 'Return Book';
$page_subtitle = 'Process a book return';
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
    WHERE l.status IN ('issued', 'overdue')
    ORDER BY l.due_date ASC
");
?>

<div class="card" style="max-width:700px; margin:0 auto;">
    <div class="card-header"><h3><i class="fas fa-undo-alt"></i> Return Book</h3></div>
    <div class="card-body">
        <?php if ($loans->num_rows == 0): ?>
            <div class="alert alert-info">No books currently issued.</div>
        <?php else: ?>
            <form action="return_book_process.php" method="POST">
                <div class="form-group">
                    <label>Select Loan <span class="required">*</span></label>
                    <select name="loan_id" class="form-control" required>
                        <option value="">-- Choose --</option>
                        <?php while($l = $loans->fetch_assoc()): ?>
                            <option value="<?php echo $l['id']; ?>">
                                <?php echo $l['book_title']; ?> - <?php echo $l['student_name']; ?> (due: <?php echo $l['due_date']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Return Date <span class="required">*</span></label>
                    <input type="date" name="return_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Return Book</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>