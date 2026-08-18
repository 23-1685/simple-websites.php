<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daystar Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <main class="main-content" id="mainContent">
        <div class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 id="pageTitle"><?php echo $page_title ?? 'Dashboard'; ?></h1>
                <p id="pageSubtitle"><?php echo $page_subtitle ?? 'Overview'; ?></p>
            </div>
            <div class="topbar-right">
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                    <a href="logout.php" class="btn btn-sm btn-secondary"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm btn-primary"><i class="fas fa-sign-in-alt"></i> Login</a>
                <?php endif; ?>
                <span class="date-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <?php echo date('l, F j, Y'); ?>
                </span>
            </div>
        </div>
        <div id="pageContent"></div>