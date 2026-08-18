<?php
$page_title = 'Edit Book';
$page_subtitle = 'Update book details';
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

include 'includes/header.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['message'] = 'Invalid book ID.';
    $_SESSION['msg_type'] = 'error';
    header('Location: books.php');
    exit;
}

$book = $conn->query("SELECT * FROM books WHERE id = $id")->fetch_assoc();
if (!$book) {
    $_SESSION['message'] = 'Book not found.';
    $_SESSION['msg_type'] = 'error';
    header('Location: books.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $category_id = $_POST['category_id'] ?: null;
    $description = $_POST['description'];
    $total_copies = (int)$_POST['total_copies'];
    $available_copies = (int)$_POST['available_copies'];

    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, isbn=?, category_id=?, description=?, total_copies=?, available_copies=? WHERE id=?");
    $stmt->bind_param("sssssiii", $title, $author, $isbn, $category_id, $description, $total_copies, $available_copies, $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Book updated.';
        $_SESSION['msg_type'] = 'success';
        header('Location: books.php');
        exit;
    } else {
        $error = $stmt->error;
    }
    $stmt->close();
}
?>

<div class="card" style="max-width:700px; margin:0 auto;">
    <div class="card-header"><h3>Edit Book</h3></div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Title *</label><input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required></div>
            <div class="form-group"><label>Author *</label><input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required></div>
            <div class="form-group"><label>ISBN</label><input type="text" name="isbn" class="form-control" value="<?php echo htmlspecialchars($book['isbn']); ?>"></div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <option value="">Select</option>
                    <?php
                    $cats = $conn->query("SELECT id, name FROM categories ORDER BY name");
                    while($c = $cats->fetch_assoc()):
                        $sel = ($c['id'] == $book['category_id']) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $sel; ?>><?php echo $c['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Total Copies *</label><input type="number" name="total_copies" class="form-control" value="<?php echo $book['total_copies']; ?>" min="1" required></div>
                <div class="form-group"><label>Available Copies *</label><input type="number" name="available_copies" class="form-control" value="<?php echo $book['available_copies']; ?>" min="0" required></div>
            </div>
            <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($book['description']); ?></textarea></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="books.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>