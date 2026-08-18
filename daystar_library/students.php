<?php
$page_title = 'Students';
$page_subtitle = 'Manage student records';
include 'includes/config.php';

// ---- PROTECTION ----
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
// --------------------

include 'includes/header.php';

$message = $_SESSION['message'] ?? '';
$msg_type = $_SESSION['msg_type'] ?? '';
unset($_SESSION['message'], $_SESSION['msg_type']);

$students = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>

<?php if ($message): ?>
    <script>showToast('<?php echo addslashes($message); ?>', '<?php echo $msg_type; ?>');</script>
<?php endif; ?>

<div class="section-header">
    <h2><i class="fas fa-users"></i> All Students</h2>
    <button class="btn btn-primary" onclick="openAddStudentModal()"><i class="fas fa-plus"></i> Add Student</button>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Student ID</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Department</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php while($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="editStudent(<?php echo $row['id']; ?>)"><i class="fas fa-edit"></i></button>
                                <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this student?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal-overlay" id="addStudentModal">
    <div class="modal">
        <div class="modal-header"><h3>Add Student</h3><button class="close-modal" onclick="document.getElementById('addStudentModal').classList.remove('active')">&times;</button></div>
        <div class="modal-body">
            <form action="add_student.php" method="POST">
                <div class="form-group"><label>Student ID <span class="required">*</span></label><input type="text" name="student_id" class="form-control" required></div>
                <div class="form-group"><label>Full Name <span class="required">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                <div class="form-group"><label>Email <span class="required">*</span></label><input type="email" name="email" class="form-control" required></div>
                <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control"></div>
                <div class="form-group"><label>Department</label><input type="text" name="department" class="form-control"></div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('addStudentModal').classList.remove('active')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddStudentModal() { document.getElementById('addStudentModal').classList.add('active'); }
function editStudent(id) { window.location.href = `edit_student.php?id=${id}`; }
</script>

<?php include 'includes/footer.php'; ?>