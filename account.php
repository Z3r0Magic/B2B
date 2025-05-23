
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/auth.php';
require_once 'includes/lang.php';
require_once 'includes/db.php';

// Проверка авторизации
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Получаем заказы пользователя
$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare("
    SELECT 
        orders.id,
        orders.order_date,
        orders.total_amount,
        orders.status,
        GROUP_CONCAT(products.title SEPARATOR ', ') AS products
    FROM orders
    LEFT JOIN order_items ON orders.id = order_items.order_id
    LEFT JOIN products ON order_items.product_id = products.id
    WHERE orders.user_id = ?
    GROUP BY orders.id
    ORDER BY orders.order_date DESC
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('my_account') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-card {
            transition: transform 0.2s;
            border: 1px solid #dee2e6;
        }
        .order-card:hover {
            transform: translateY(-5px);
        }
        .status-badge {
            font-size: 0.9em;
            padding: 0.35em 0.65em;
        }
    </style>
</head>
<body>
    <?php include 'partials/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4"><?= t('order_history') ?></h1>
        
        <?php if (empty($orders)): ?>
            <div class="alert alert-info"><?= t('no_orders') ?></div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($orders as $order): ?>
                    <div class="col">
                        <div class="card h-100 order-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span class="text-muted"><?= t('order') ?> #<?= $order['id'] ?></span>
                                <span class="badge <?= getStatusClass($order['status']) ?> status-badge">
                                    <?= t($order['status']) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= date('d.m.Y H:i', strtotime($order['order_date'])) ?></h5>
                                <p class="card-text">
                                    <strong><?= t('products') ?>:</strong> 
                                    <?= htmlspecialchars($order['products']) ?><br>
                                    <strong><?= t('total') ?>:</strong> 
                                    <?= number_format($order['total_amount'], 2) ?> ₽
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

<?php
// Функция для определения класса статуса
function getStatusClass($status) {
    switch ($status) {
        case 'completed': return 'bg-success';
        case 'processing': return 'bg-primary';
        case 'canceled': return 'bg-danger';
        default: return 'bg-secondary';
    }
}
?>