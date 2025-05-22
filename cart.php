<?php
require_once 'includes/auth.php';
require_once 'includes/lang.php';
require_once 'includes/db.php';

// Проверка авторизации и роли
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

// Получение товаров в корзине
$stmt = $pdo->prepare("
    SELECT 
      cart.id,
      cart.quantity,
      products.id AS product_id,
      products.title,
      products.wholesale_price,
      products.image_path,
      products.stock
    FROM cart
    LEFT JOIN products ON cart.product_id = products.id
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="container mt-5">
    <h1 class="mb-4"><?= t('cart') ?></h1>
    
    <?php if (empty($cartItems)): ?>
      <div class="alert alert-info"><?= t('cart_empty') ?></div>
    <?php else: ?>
      <div class="row">
        <div class="col-md-8">
          <?php foreach ($cartItems as $item): ?>
            <div class="card mb-3">
              <div class="row g-0">
                <div class="col-md-4">
                  <img 
                    src="/uploads/<?= htmlspecialchars($item['image_path']) ?>" 
                    class="img-fluid rounded-start" 
                    alt="<?= htmlspecialchars($item['title']) ?>"
                  >
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                    <p class="card-text">
                      <?= t('price') ?>: $<?= number_format($item['wholesale_price'], 2) ?>
                    </p>
                    <form action="cart_update.php" method="post" class="d-inline">
                      <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                      <input 
                        type="number" 
                        name="quantity" 
                        value="<?= $item['quantity'] ?>" 
                        min="1" 
                        max="<?= $item['stock'] ?>"
                        class="form-control d-inline w-25"
                      >
                      <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <?= t('update') ?>
                      </button>
                    </form>
                    <a 
                      href="cart_remove.php?product_id=<?= $item['product_id'] ?>" 
                      class="btn btn-sm btn-danger"
                    >
                      <?= t('remove') ?>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"><?= t('total') ?></h5>
              <?php
                $total = 0;
                foreach ($cartItems as $item) {
                  $total += $item['wholesale_price'] * $item['quantity'];
                }
              ?>
              <p class="fs-4">$<?= number_format($total, 2) ?></p>
              <a href="checkout.php" class="btn btn-success w-100"><?= t('checkout') ?></a>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>