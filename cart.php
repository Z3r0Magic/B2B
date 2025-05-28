<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/auth.php';
require_once 'includes/lang.php';
require_once 'includes/db.php';

// Проверка авторизации и роли
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

// Исправленный SQL-запрос (добавлена запятая)
$stmt = $pdo->prepare("
    SELECT 
        cart.id,
        cart.quantity,
        products.id AS product_id,
        products.title,
        products.wholesale_price,
        products.image_path,
        products.stock,
        products.quantity_per_box, -- ЗАПЯТАЯ ДОБАВЛЕНА
        products.article
    FROM cart
    LEFT JOIN products 
        ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$_SESSION['user']['id']]);
$cartItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('cart') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-img-top {
      height: 200px;
      object-fit: contain;
    }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="container mt-5">
    <h1 class="mb-4"><?= t('cart') ?></h1>
    
    <?php if (empty($cartItems)): ?>
      <div class="alert alert-info"><?= t('cart_empty') ?></div>
    <?php else: ?>
      <div class="row">
        <?php foreach ($cartItems as $item): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              <?php if (!empty($item['image_path'])): ?>
                <img src="/uploads/<?= htmlspecialchars($item['image_path']) ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($item['title']) ?>">
              <?php else: ?>
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center">
                  <span class="text-muted"><?= t('no_image') ?></span>
                </div>
              <?php endif; ?>

              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                <div class="card-text">
                  <p class="mb-1">
                    <strong><?= t('price') ?>:</strong> 
                    <?= htmlspecialchars($item['wholesale_price']) ?> ₽
                  </p>
                  <p class="mb-1">
                    <strong><?= t('article') ?>:</strong> 
                    <?= htmlspecialchars($item['article'] ?? 'N/A') ?> <!-- Убрано ₽, добавлена проверка -->
                  </p>
                  <p class="mb-1">
                    <strong><?= t('quantity') ?>:</strong> 
                    <?= htmlspecialchars($item['quantity']) ?> 
                  </p>
                  <p class="mb-1">
                    <strong><?= t('per_boxs') ?>:</strong> 
                    <?= htmlspecialchars($item['quantity_per_box']) ?> 
                  </p>
                  <p class="mb-1 <?= $item['stock'] < $item['quantity'] ? 'text-danger' : '' ?>">
                    <strong><?= t('stock') ?>:</strong> 
                    <?= htmlspecialchars($item['stock']) ?>
                  </p>
                </div>
              </div>

              <div class="card-footer bg-white">
                <form action="update_cart.php" method="POST">
                  <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                  <div class="input-group">
                    <input type="number" 
                           name="quantity" 
                           value="<?= $item['quantity'] ?>" 
                           min="1" 
                           max="<?= $item['stock'] ?>" 
                           class="form-control">
                    <button type="submit" class="btn btn-primary"><?= t('update') ?></button>
                  </div>
                </form>
                <a href="remove_from_cart.php?id=<?= $item['id'] ?>" 
                   class="btn btn-danger mt-2 w-100">
                  <?= t('remove') ?>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="mt-4 p-3 bg-light rounded">
        <h4 class="mb-3"><?= t('total') ?>:</h4>
       <p class="fs-5">
        <?= t('total_items') ?>: 
        <?php 
          $totalItems = array_sum(array_map(
            function ($item) { 
              return $item['quantity'] * $item['quantity_per_box']; 
            }, 
            $cartItems
          ));
          echo $totalItems;
        ?> (<?= htmlspecialchars($item['quantity'])  ?> <?= t('boxes') ?>)<br>
        
        <?= t('total_sum') ?>: 
        <?php 
          $total = array_sum(array_map(
            function ($item) { 
              return $item['wholesale_price'] * $item['quantity']; 
            }, 
            $cartItems
          ));
          echo $total;
        ?> ₽
      </p>
        <a href="checkout.php" class="btn btn-success btn-lg"><?= t('checkout') ?></a>
      </div>
    <?php endif; ?>
  </div>

  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>