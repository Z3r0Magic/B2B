<?php
require_once 'includes/auth.php';
require_once 'includes/lang.php';
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
  header('Location: catalog.php');
  exit;
}

// Получение данных о товаре
$stmt = $pdo->prepare("
    SELECT products.*, categories.name AS category_name 
    FROM products 
    LEFT JOIN categories ON products.category_id = categories.id
    WHERE products.id = ?
");
$stmt->execute([$_GET['id']]);
$product = $stmt->fetch();

if (!$product) {
  header('Location: catalog.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['title']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'partials/navbar.php'; ?>

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <img 
          src="uploads/<?= htmlspecialchars($product['image_path']) ?>"
          class="img-fluid rounded"
          alt="<?= htmlspecialchars($product['title']) ?>"
        >
      </div>
      <div class="col-md-6">
        <h1><?= htmlspecialchars($product['title']) ?></h1>
        <p class="text-muted"><?= t('article') ?>: <?= htmlspecialchars($product['article']) ?></p>
        <p><?= t('description') ?>: <?= htmlspecialchars($product['description']) ?></p>
        <div class="row">
          <div class="col">
            <p><?= t('wholesale_price') ?>: <span class="text-success"><?= number_format($product['wholesale_price'], 2) ?> ₽</span></p>
          </div>
          <div class="col">
            <p><?= t('retail_price') ?>: <span class="text-danger"><?= number_format($product['retail_price'], 2) ?> ₽</span></p>
          </div>
        </div>
        <form action="cart_add.php" method="post">
          <div class="mb-3">
            <label class="form-label"><?= t('quantity') ?></label>
            <input 
              type="number" 
              name="quantity" 
              class="form-control" 
              value="1" 
              min="1" 
              max="<?= $product['stock'] ?>"
            >
          </div>
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <button type="submit" class="btn btn-primary w-100"><?= t('add_to_cart') ?></button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>