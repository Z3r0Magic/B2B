<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Проверка роли
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;


}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $productId = (int)$_POST['product_id'];
        $quantity = (int)($_POST['quantity'] ?? 1);

        // Проверка наличия товара
        $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $stock = $stmt->fetchColumn();

        if ($quantity > $stock) {
            throw new Exception(t('not_enough_stock'));
        }

        // Добавление в корзину
        $stmt = $pdo->prepare("
            INSERT INTO cart (user_id, product_id, quantity) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?
        ");
        
        $stmt->execute([
            $_SESSION['user']['id'],
            $productId,
            $quantity,
            $quantity
        ]);

        header('Location: cart.php');
    } catch (Exception $e) {
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '&error=' . urlencode($e->getMessage()));
    }
    exit;
}

header('Location: catalog.php');