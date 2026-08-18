<?php
require_once 'models/Customer.php';

function loginUser($email, $password, $pdo) {
    $customerModel = new Customer($pdo);
    $user = $customerModel->findByEmail($email);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        return true;
    }
    return false;
}

function registerUser($data, $pdo) {
    $customerModel = new Customer($pdo);
    // Check if email exists
    if ($customerModel->findByEmail($data['email'])) {
        return false; // Email already taken
    }
    // Hash password
    $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
    unset($data['password']);
    return $customerModel->create($data);
}

function logoutUser() {
    session_destroy();
    redirect('index.php?page=home');
}

function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('index.php?page=login');
    }
}
?>