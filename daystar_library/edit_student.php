<?php
$page_title = 'Edit Student';
$page_subtitle = 'Update student details';
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
    $_SESSION['message'] = 'Invalid student ID.';
    $_SESSION['msg_type'] = 'error';
    header('Location: students.php');
    exit;
}

$student = $conn->query("SELECT * FROM students WHERE id = $id")->fetch_assoc();
if (!$student) {
    $_SESSION['message'] = 'Student not found.';
    $_SESSION['msg_type'] = 'error';
    header('Location: students.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];

    $stmt = $conn->prepare("UPDATE students SET student_id=?, full_name=?, email=?, phone=?, department=? WHERE id=?");
    $stmt->bind_param("sssssi", $student_id, $full_name, $email, $phone, $department, $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Student updated.';
        $_SESSION['msg_type'] = 'success';
        header('Location: students.php');
        exit;
    } else {
        $error = $stmt->error;
    }
    $stmt->close();
}
?>

<div class="card" style="max-width:700px; margin:0 auto;">
    <div class="card-header"><h3>Edit Student</h3></div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Student ID *</label><input type="text" name="student_id" class="form-control" value="<?php echo htmlspecialchars($student['student_id']); ?>" required></div>
            <div class="form-group"><label>Full Name *</label><input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($student['full_name']); ?>" required></div>
            <div class="form-group"><label>Email *</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>"></div>
            <div class="form-group"><label>Department</label><input type="text" name="department" class="form-control" value="<?php echo htmlspecialchars($student['department']); ?>"></div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="students.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>