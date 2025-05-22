<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/db.php';

restrictAccess('admin');

// Удаление товара
if (isset($_GET['delete_product'])) {
    $productId = (int)$_GET['delete_product'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    header('Location: add_product.php');
    exit;
}

// Получение списка товаров
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('manage_products') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>
  <div class="container mt-5">
    <h2><?= t('manage_products') ?></h2>
    
    <!-- Таблица товаров -->
    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th>ID</th>
          <th><?= t('title') ?></th>
          <th><?= t('price') ?></th>
          <th><?= t('actions') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
          <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['title']) ?></td>
            <td>$<?= number_format($product['price'], 2) ?></td>
            <td>
              <a href="?delete_product=<?= $product['id'] ?>" class="btn btn-danger btn-sm"><?= t('delete') ?></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Форма добавления товара -->
    <h3 class="mt-5"><?= t('add_product') ?></h3>
    <form action="process_product.php" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label"><?= t('title') ?></label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= t('price') ?></label>
        <input type="number" step="0.01" name="price" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= t('image') ?></label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
      </div>
      <button type="submit" class="btn btn-primary"><?= t('add_product') ?></button>
    </form>
  </div>
</body>
</html>