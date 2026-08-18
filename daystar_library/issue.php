<?php
$page_title = 'Issue Book';
$page_subtitle = 'Lend a book to a student';
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

include 'includes/header.php';

$books = $conn->query("SELECT id, title, available_copies FROM books WHERE available_copies > 0 ORDER BY title");
$students = $conn->query("SELECT id, student_id, full_name FROM students ORDER BY full_name");
?>

<div class="card" style="max-width:700px; margin:0 auto;">
    <div class="card-header"><h3><i class="fas fa-sign-out-alt"></i> Issue Book</h3></div>
    <div class="card-body">
        <?php if ($books->num_rows == 0): ?>
            <div class="alert alert-warning">No books available for issue.</div>
        <?php else: ?>
            <form action="issue_book_process.php" method="POST">
                <div class="form-group">
                    <label>Select Book <span class="required">*</span></label>
                    <select name="book_id" class="form-control" required>
                        <option value="">-- Choose --</option>
                        <?php while($b = $books->fetch_assoc()): ?>
                            <option value="<?php echo $b['id']; ?>"><?php echo $b['title']; ?> (<?php echo $b['available_copies']; ?> available)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Student <span class="required">*</span></label>
                    <select name="student_id" class="form-control" required>
                        <option value="">-- Choose --</option>
                        <?php while($s = $students->fetch_assoc()): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo $s['full_name']; ?> (<?php echo $s['student_id']; ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Issue Date <span class="required">*</span></label>
                        <input type="date" name="issue_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Due Date <span class="required">*</span></label>
                        <input type="date" name="due_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Issue Book</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>