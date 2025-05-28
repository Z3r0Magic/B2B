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
        products.article,
        products.stock,
        categories.name AS category_name,
        order_items.quantity,
        order_items.price
    FROM order_items    
    LEFT JOIN products ON order_items.product_id = products.id
    LEFT JOIN categories ON products.category_id = categories.id
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
    <style>
        .order-status {
            font-size: 0.9rem;
            padding: 0.35rem 0.65rem;
        }
        .total-summary {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
    </style>
</head>
<body class="bg-light">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
    
    <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2"><?= t('order_details') ?> #<?= $order['id'] ?></h1>
            <a href="orders.php" class="btn btn-outline-secondary">
                ← <?= t('back_to_orders') ?>
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><?= t('order_information') ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4"><?= t('customer') ?></dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($order['customer']) ?></dd>

                            <dt class="col-sm-4"><?= t('phone') ?></dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($order['phone']) ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4"><?= t('date') ?></dt>
                            <dd class="col-sm-8"><?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></dd>

                            <dt class="col-sm-4"><?= t('status') ?></dt>
                            <dd class="col-sm-8">
                                <?php 
                                    $statusClass = [
                                        'pending' => 'bg-warning text-dark',
                                        'processing' => 'bg-primary',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger'
                                    ][$order['status']] ?? 'bg-secondary';
                                ?>
                                <span class="badge <?= $statusClass ?> order-status">
                                    <?= t($order['status']) ?>
                                </span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><?= t('products') ?></h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><?= t('product') ?></th>
                            <th><?= t('article') ?></th>
                            <th><?= t('category') ?></th>
                            <th class="text-center"><?= t('quantity') ?></th>
                            <th class="text-center"><?= t('stock') ?></th>
                            <th class="text-end"><?= t('price') ?></th>
                            <th class="text-end"><?= t('total') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['title']) ?></td>
                                <td><?= htmlspecialchars($item['article']) ?></td>
                                <td><?= htmlspecialchars($item['category_name']) ?></td> 
                                <td class="text-center"><?= $item['quantity'] ?></td>
                                <td class="text-center"><?= $item['stock'] ?></td>
                                <td class="text-end"><?= number_format($item['price'], 2) ?> ₽</td>
                                <td class="text-end fw-bold"><?= number_format($item['price'] * $item['quantity'], 2) ?> ₽</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                <div class="row justify-content-end">
                    <div class="col-auto total-summary">
                        <h5 class="mb-0">
                            <?= t('total_order_amount') ?>: 
                            <span class="text-primary"><?= number_format($order['total_amount'], 2) ?> ₽</span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>