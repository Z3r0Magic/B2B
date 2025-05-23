<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

restrictAccess('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = (int)$_POST['order_id'];
    $newStatus = $_POST['status'];

    try {
        $pdo->beginTransaction();

        // Обновление статуса заказа
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $orderId]);

        // Если статус "completed", обновляем количество товаров
        if ($newStatus === 'completed') {
            // Получаем товары из заказа
            $stmt = $pdo->prepare("
                SELECT product_id, quantity 
                FROM order_items 
                WHERE order_id = ?
            ");
            $stmt->execute([$orderId]);
            $items = $stmt->fetchAll();

            // Обновляем stock для каждого товара
            foreach ($items as $item) {
                $stmt = $pdo->prepare("
                    UPDATE products 
                    SET stock = stock - ? 
                    WHERE id = ?
                ");
                $stmt->execute([$item['quantity'], $item['product_id']]);
            }
        }

        $pdo->commit();
        header("Location: orders.php?success=1");
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: orders.php?error=" . urlencode($e->getMessage()));
    }
    exit;
}

header("Location: orders.php");