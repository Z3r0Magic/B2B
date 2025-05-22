<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Получаем товары из корзины
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$cartItems = $stmt->fetchAll();

if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

// Создаем заказ
$pdo->beginTransaction();
try {
    // Рассчитываем итоговую сумму
    $total = 0;
    foreach ($cartItems as $item) {
        $product = $pdo->query("SELECT wholesale_price FROM products WHERE id = {$item['product_id']}")->fetch();
        $total += $product['wholesale_price'] * $item['quantity'];
    }

    // Добавляем заказ
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user']['id'], $total]);
    $orderId = $pdo->lastInsertId();

    // Добавляем товары в order_items
    foreach ($cartItems as $item) {
        $product = $pdo->query("SELECT * FROM products WHERE id = {$item['product_id']}")->fetch();
        $stmt = $pdo->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $product['wholesale_price']]);
    }

    // Очищаем корзину
    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$_SESSION['user']['id']]);

    $pdo->commit();
    header('Location: account.php');
} catch (Exception $e) {
    $pdo->rollBack();
    die("Ошибка оформления заказа: " . $e->getMessage());
}