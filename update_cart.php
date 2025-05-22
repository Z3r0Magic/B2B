<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Проверка авторизации
if (!isset($_SESSION['user'])) { // ← Добавлена закрывающая скобка
    header('Location: login.php');
    exit;
}

// Обработка данных из формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;

    // Валидация данных
    if ($cart_id && $quantity > 0) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$quantity, $cart_id, $_SESSION['user']['id']]);
    }
}

// Возврат в корзину
header('Location: cart.php');