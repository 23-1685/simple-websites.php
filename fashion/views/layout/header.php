<?php startSession(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Must Have Collection</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="index.php?page=home">👗 Fashion Must Have</a>
            </div>
            <ul class="nav-links">
                <li><a href="index.php?page=home">Home</a></li>
                <li><a href="index.php?page=shop">Shop</a></li>
                <li><a href="index.php?page=cart" class="cart-link">
                    <i class="fas fa-shopping-cart"></i> Cart (<span id="cart-count"><?= getCartCount(); ?></span>)
                </a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="index.php?page=my-orders">My Orders</a></li>
                    <li><a href="index.php?page=profile"><?= htmlspecialchars(getUserName()); ?></a></li>
                    <li><a href="index.php?page=logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=login">Login</a></li>
                    <li><a href="index.php?page=register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>