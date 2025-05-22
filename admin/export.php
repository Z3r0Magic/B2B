<?php
require_once '../includes/auth.php';
restrictAccess('admin');

// Статистика
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(total) FROM orders")->fetchColumn();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5><?= t('total_orders') ?></h5>
                <p><?= $totalOrders ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5><?= t('total_revenue') ?></h5>
                <p>$<?= number_format($revenue, 2) ?></p>
            </div>
        </div>
    </div>
</div>