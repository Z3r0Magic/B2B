<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/lang.php';

restrictAccess('admin');

if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$orderId = (int)$_GET['id'];

// Получение информации о заказе
$stmt = $pdo->prepare("
    SELECT 
        orders.*,
        users.username AS customer,
        users.phone
    FROM orders
    LEFT JOIN users ON orders.user_id = users.id
    WHERE orders.id = ?
");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

// Получение товаров в заказе
$stmt = $pdo->prepare("
    SELECT 
        products.title,
        order_items.quantity,
        order_items.price
    FROM order_items
    LEFT JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('order_details') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
    
    <div class="container mt-5">
        <h1><?= t('order_details') ?> #<?= $order['id'] ?></h1>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title"><?= t('customer') ?>: <?= htmlspecialchars($order['customer']) ?></h5>
                <p class="card-text"><?= t('phone') ?>: <?= htmlspecialchars($order['phone']) ?></p>
                <p class="card-text"><?= t('date') ?>: <?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></p>
                <p class="card-text"><?= t('total') ?>: $<?= number_format($order['total_amount'], 2) ?></p>
                <p class="card-text"><?= t('status') ?>: <?= t($order['status']) ?></p>
            </div>
        </div>

        <h3 class="mt-4"><?= t('products') ?></h3>
        <table class="table table-striped mt-2">
            <thead>
                <tr>
                    <th><?= t('product') ?></th>
                    <th><?= t('quantity') ?></th>
                    <th><?= t('price') ?></th>
                    <th><?= t('total') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['title']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>