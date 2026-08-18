<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Admin auth checks
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
if ($_SESSION['role'] != "admin") {
    header("Location: ../customer/dashboard.php");
    exit();
}
$adminUser = $_SESSION['fullname'] ?? 'Admin';
$currentFile = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Daystar Digital Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-admin {
            background-color: #1e293b;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .navbar-admin .navbar-brand {
            font-weight: 800;
            color: #38ef7d !important;
            font-size: 1.4rem;
        }
        .navbar-admin .nav-link {
            color: rgba(255,255,255,0.75) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s;
            margin: 0 0.1rem;
        }
        .navbar-admin .nav-link:hover, .navbar-admin .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.1);
        }
        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            background: white;
        }
        .footer-admin {
            margin-top: auto;
            background-color: #1e293b;
            color: #94a3b8;
            padding: 1.5rem 0;
            font-size: 0.9rem;
        }
        .badge-status-pending { background-color: #ffc107; color: #000; }
        .badge-status-processing { background-color: #0d6efd; color: #fff; }
        .badge-status-ready { background-color: #198754; color: #fff; }
        .badge-status-completed { background-color: #6c757d; color: #fff; }
        .badge-status-cancelled { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-admin py-3 mb-4">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            🧺 Laundry Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'customers.php' ? 'active' : '' ?>" href="customers.php">
                        <i class="bi bi-people me-1"></i> Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'orders.php' ? 'active' : '' ?>" href="orders.php">
                        <i class="bi bi-cart me-1"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'services.php' ? 'active' : '' ?>" href="services.php">
                        <i class="bi bi-tags me-1"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Reports
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 fw-bold">
                    <i class="bi bi-shield-lock me-1"></i> <?= htmlspecialchars($adminUser) ?>
                </span>
                <a href="../logout.php" class="btn btn-outline-danger btn-sm fw-bold px-3 py-2" style="border-radius: 8px;">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container pb-5">
