<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../models/Customer.php';

if ($page === 'logout') {
    logoutUser();
}

if ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        if (loginUser($email, $password, $pdo)) {
            $redirect = $_SESSION['redirect_after_login'] ?? 'index.php?page=home';
            unset($_SESSION['redirect_after_login']);
            redirect($redirect);
        } else {
            $error = "Invalid email or password.";
        }
    }
    require_once __DIR__ . '/../views/account/login.php';
}

if ($page === 'register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'phone' => $_POST['phone'] ?? ''
        ];
        if (registerUser($data, $pdo)) {
            loginUser($data['email'], $_POST['password'], $pdo);
            redirect('index.php?page=home');
        } else {
            $error = "Registration failed. Email might be taken.";
        }
    }
    require_once __DIR__ . '/../views/account/register.php';
}

if ($page === 'profile') {
    requireLogin();
    $customerModel = new Customer($pdo);
    $user = $customerModel->findById(getUserId());
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'phone' => $_POST['phone']
        ];
        $customerModel->update(getUserId(), $data);
        $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
        $success = "Profile updated!";
        $user = $customerModel->findById(getUserId());
    }
    require_once __DIR__ . '/../views/account/profile.php';
}

if ($page === 'my-orders') {
    requireLogin();
    require_once __DIR__ . '/../models/Order.php';
    $orderModel = new Order($pdo);
    $orders = $orderModel->getByCustomer(getUserId());
    require_once __DIR__ . '/../views/account/my-orders.php';
}
?>