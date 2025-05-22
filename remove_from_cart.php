<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Удаление товара
if (isset($_GET['id'])) {
    $cart_id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $_SESSION['user']['id']]);
}

// Возврат в корзину
header('Location: cart.php');