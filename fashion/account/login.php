<?php require_once 'layout/header.php'; ?>
<h1>Login</h1>
<?php if (isset($error)): ?>
    <div class="error"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>
<form method="POST" class="auth-form">
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit" class="btn-primary">Login</button>
    <p>Don't have an account? <a href="index.php?page=register">Register</a></p>
</form>
<?php require_once 'layout/footer.php'; ?>