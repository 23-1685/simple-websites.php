<?php
$page_title = 'Books';
$page_subtitle = 'Manage book collection';
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

include 'includes/header.php';

// Handle messages from actions
$message = $_SESSION['message'] ?? '';
$msg_type = $_SESSION['msg_type'] ?? '';
unset($_SESSION['message'], $_SESSION['msg_type']);

// Fetch all books with category name
$books = $conn->query("
    SELECT b.*, c.name as category_name 
    FROM books b 
    LEFT JOIN categories c ON b.category_id = c.id 
    ORDER BY b.id DESC
");
?>

<?php if ($message): ?>
    <script>showToast('<?php echo addslashes($message); ?>', '<?php echo $msg_type; ?>');</script>
<?php endif; ?>

<div class="section-header">
    <h2><i class="fas fa-book"></i> All Books</h2>
    <button class="btn btn-primary" onclick="openAddBookModal()"><i class="fas fa-plus"></i> Add New Book</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr><th>Title</th><th>Author</th><th>ISBN</th><th>Category</th><th>Total</th><th>Available</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if ($books->num_rows > 0): ?>
                        <?php while($row = $books->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['author']); ?></td>
                                <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                                <td><?php echo htmlspecialchars($row['category_name'] ?? 'N/A'); ?></td>
                                <td><?php echo $row['total_copies']; ?></td>
                                <td><?php echo $row['available_copies']; ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm" onclick="editBook(<?php echo $row['id']; ?>)"><i class="fas fa-edit"></i></button>
                                    <a href="delete_book.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this book?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No books found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Book Modal -->
<div class="modal-overlay" id="addBookModal">
    <div class="modal">
        <div class="modal-header">
            <h3>Add New Book</h3>
            <button class="close-modal" onclick="document.getElementById('addBookModal').classList.remove('active')">&times;</button>
        </div>
        <div class="modal-body">
            <form action="add_book.php" method="POST">
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Author <span class="required">*</span></label>
                    <input type="text" name="author" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>ISBN</label>
                        <input type="text" name="isbn" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control">
                            <option value="">Select</option>
                            <?php
                            $cats = $conn->query("SELECT id, name FROM categories ORDER BY name");
                            while($c = $cats->fetch_assoc()):
                            ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Total Copies <span class="required">*</span></label>
                        <input type="number" name="total_copies" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Available Copies <span class="required">*</span></label>
                        <input type="number" name="available_copies" class="form-control" value="1" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Book</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('addBookModal').classList.remove('active')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddBookModal() {
    document.getElementById('addBookModal').classList.add('active');
}
function editBook(id) {
    window.location.href = `edit_book.php?id=${id}`;
}
</script>

<?php include 'includes/footer.php'; ?>