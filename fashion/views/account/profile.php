<?php require_once __DIR__ . '/../layout/header.php'; ?>
<h1>My Profile</h1>
<?php if (isset($success)): ?>
    <div class="success"><?= htmlspecialchars($success); ?></div>
<?php endif; ?>
<form method="POST" class="auth-form">
    <div class="form-group">
        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>
    </div>
    <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required>
    </div>
    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>">
    </div>
    <button type="submit" class="btn-primary">Update Profile</button>
</form>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>