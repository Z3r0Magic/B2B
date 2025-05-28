<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/db.php';

restrictAccess('admin');

// Получение всех заказов
$stmt = $pdo->prepare("
    SELECT 
        orders.id,
        orders.order_date,
        orders.total_amount,
        orders.status,
        users.username AS customer,
        GROUP_CONCAT(products.title SEPARATOR ', ') AS products
    FROM orders
    LEFT JOIN users ON orders.user_id = users.id
    LEFT JOIN order_items ON orders.id = order_items.order_id
    LEFT JOIN products ON order_items.product_id = products.id
    GROUP BY orders.id
    ORDER BY orders.order_date DESC
");
$stmt->execute();
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('manage_orders') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="container mt-5">
        <h1><?= t('manage_orders') ?></h1>
        
        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?= t('customer') ?></th>
                        <th><?= t('date') ?></th>
                        <th><?= t('total') ?></th>
                        <th><?= t('status') ?></th>
                        <th><?= t('products') ?></th>
                        <th><?= t('actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['customer']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></td>
                            <td>    <?= number_format($order['total_amount'], 2) ?> ₽</td>
                            <td>
                                <form action="update_order_status.php" method="post">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>
                                            <?= t('processing') ?>
                                        </option>
                                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>
                                            <?= t('completed') ?>
                                        </option>
                                        <option value="canceled" <?= $order['status'] === 'canceled' ? 'selected' : '' ?>>
                                            <?= t('canceled') ?>
                                        </option>
                                    </select>
                                </form>
                            </td>
                            <td><?= htmlspecialchars($order['products']) ?></td>
                            <td>
                                <a href="order_details.php?id=<?= $order['id'] ?>" class="btn btn-info btn-sm">
                                    <?= t('details') ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>