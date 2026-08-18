<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

// Get the page and action from URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Route to the appropriate controller
switch ($page) {
    case 'home':
        require_once __DIR__ . '/controllers/HomeController.php';
        break;
    case 'shop':
    case 'product':
        require_once __DIR__ . '/controllers/ProductController.php';
        break;
    case 'cart':
        require_once __DIR__ . '/controllers/CartController.php';
        break;
    case 'checkout':
        require_once __DIR__ . '/controllers/CheckoutController.php';
        break;
    case 'order-confirmation':
        require_once __DIR__ . '/views/order-confirmation.php';
        break;
    case 'login':
    case 'register':
    case 'logout':
    case 'profile':
    case 'my-orders':
        require_once __DIR__ . '/controllers/AuthController.php';
        break;
    default:
        require_once __DIR__ . '/views/404.php';
        break;
}
?>