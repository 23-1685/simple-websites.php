<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="brand-text">
            Daystar<span>Library</span>
            <span class="brand-sub">Management System</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="books.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'books.php' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i> Books
        </a>
        <a href="students.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Students
        </a>

        <div class="nav-label" style="margin-top:20px;">Transactions</div>
        <a href="loans.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'loans.php' ? 'active' : ''; ?>">
            <i class="fas fa-hand-holding-heart"></i> Loans
        </a>
        <a href="issue.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'issue.php' ? 'active' : ''; ?>">
            <i class="fas fa-sign-out-alt"></i> Issue Book
        </a>
        <a href="return.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'return.php' ? 'active' : ''; ?>">
            <i class="fas fa-undo-alt"></i> Return Book
        </a>

        <!-- NEW REPORTS SECTION -->
        <div class="nav-label" style="margin-top:20px;">Reports</div>
        <a href="report.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'report.php' ? 'active' : ''; ?>">
            <i class="fas fa-file-download"></i> Download Reports
        </a>
    </nav>

    <div class="sidebar-footer">
        &copy; <?php echo date('Y'); ?> Daystar University
    </div>
</aside>