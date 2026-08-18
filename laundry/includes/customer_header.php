<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$currentUser = $_SESSION['fullname'] ?? 'Customer';
$currentFile = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Panel - Daystar Digital Laundry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f4f7f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .navbar-custom .navbar-brand {
            font-weight: 800;
            color: white;
            font-size: 1.4rem;
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .navbar-custom .nav-link:hover, .navbar-custom .nav-link.active {
            color: white !important;
            background: rgba(255,255,255,0.15);
        }
        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            background: white;
        }
        .footer-custom {
            margin-top: auto;
            background-color: #e9ecef;
            color: #6c757d;
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

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 mb-4">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            🧺 Daystar Laundry
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#customerNavbar" aria-controls="customerNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="customerNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'new_order.php' ? 'active' : '' ?>" href="new_order.php">
                        <i class="bi bi-plus-circle me-1"></i> New Order
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'my_orders.php' ? 'active' : '' ?>" href="my_orders.php">
                        <i class="bi bi-journal-text me-1"></i> My Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'track_order.php' ? 'active' : '' ?>" href="track_order.php">
                        <i class="bi bi-geo-alt me-1"></i> Track Order
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentFile == 'profile.php' ? 'active' : '' ?>" href="profile.php">
                        <i class="bi bi-person me-1"></i> Profile
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 fw-bold">
                    <i class="bi bi-person-circle me-1"></i> <?= htmlspecialchars($currentUser) ?>
                </span>
                <a href="../logout.php" class="btn btn-outline-light btn-sm fw-bold px-3 py-2" style="border-radius: 8px;">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container pb-5">
