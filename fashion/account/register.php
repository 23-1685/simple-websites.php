<?php require_once 'layout/header.php'; ?>
<h1>Register</h1>
<?php if (isset($error)): ?>
    <div class="error"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" class="auth-form">
    <div class="form-group">
        <label>First Name</label>
        <input type="text" name="first_name" required>
    </div>
    <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="last_name" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit" class="btn-primary">Register</button>
    <p>Already have an account? <a href="index.php?page=login">Login</a></p>
</form>
<?php require_once 'layout/footer.php'; ?>