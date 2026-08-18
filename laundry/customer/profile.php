<?php
require "../includes/customer_header.php";
require "../db.php";

$id = $_SESSION['user_id'];
$error = "";
$success = "";

// Fetch current user details securely
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (isset($_POST['update'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Validations
    if (empty($fullname) || empty($email) || empty($phone)) {
        $error = "Full Name, Email, and Phone number are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Check if email is already taken by another user
        $stmt_email = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($stmt_email, "si", $email, $id);
        mysqli_stmt_execute($stmt_email);
        mysqli_stmt_store_result($stmt_email);
        
        if (mysqli_stmt_num_rows($stmt_email) > 0) {
            $error = "Email address is already in use by another user.";
        }
        mysqli_stmt_close($stmt_email);

        if (empty($error)) {
            if (!empty($password)) {
                if ($password != $confirm) {
                    $error = "Passwords do not match.";
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt_update = mysqli_prepare($conn, "UPDATE users SET fullname = ?, email = ?, phone = ?, password = ? WHERE id = ?");
                    mysqli_stmt_bind_param($stmt_update, "ssssi", $fullname, $email, $phone, $hash, $id);
                    if (mysqli_stmt_execute($stmt_update)) {
                        $success = "Profile and password updated successfully!";
                        $_SESSION['fullname'] = $fullname;
                    } else {
                        $error = "Failed to update profile. Please try again.";
                    }
                    mysqli_stmt_close($stmt_update);
                }
            } else {
                $stmt_update = mysqli_prepare($conn, "UPDATE users SET fullname = ?, email = ?, phone = ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt_update, "sssi", $fullname, $email, $phone, $id);
                if (mysqli_stmt_execute($stmt_update)) {
                    $success = "Profile updated successfully!";
                    $_SESSION['fullname'] = $fullname;
                } else {
                    $error = "Failed to update profile. Please try again.";
                }
                mysqli_stmt_close($stmt_update);
            }
            
            // Re-fetch updated user details
            if (empty($error)) {
                $stmt_refetch = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
                mysqli_stmt_bind_param($stmt_refetch, "i", $id);
                mysqli_stmt_execute($stmt_refetch);
                $result_refetch = mysqli_stmt_get_result($stmt_refetch);
                $user = mysqli_fetch_assoc($result_refetch);
                mysqli_stmt_close($stmt_refetch);
            }
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card card-custom p-4 p-md-5">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">Profile Settings</h3>
                    <p class="text-muted mb-0">Update your personal account details</p>
                </div>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">USERNAME</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                        <div class="form-text small text-muted">Username cannot be changed.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">FULL NAME</label>
                        <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">PHONE NUMBER</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold mb-3 text-dark">Change Password (Leave blank to keep current)</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">NEW PASSWORD</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted small fw-bold">CONFIRM NEW PASSWORD</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="dashboard.php" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 10px;">
                        ← Dashboard
                    </a>
                    <button type="submit" name="update" class="btn btn-primary px-5 py-2 fw-semibold" style="border-radius: 10px;">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require "../includes/customer_footer.php";
?>