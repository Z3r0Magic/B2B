<?php
require_once 'includes/lang.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login($username, $password)) {
        switch ($_SESSION['user']['role']) {
            case 'admin':
                header('Location: admin/dashboard.php');
                break;
            case 'editor':
                header('Location: admin/add_product.php'); 
                break;
            default:
                header('Location: index.php');
        }
        exit;
    } else {
        $_SESSION['error'] = t('login_failed'); 
        header('Location: login.php');
        exit;
    }
}

header('Location: login.php');
?>