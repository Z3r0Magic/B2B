<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/db.php';

restrictAccess(['admin', 'editor']); // Разрешить доступ админам и редакторам

// Удаление товара
if (isset($_GET['delete_product'])) {
    $productId = (int)$_GET['delete_product'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    header('Location: add_product.php');
    exit;
}

// Получение списка товаров с категориями
$stmt = $pdo->query("
    SELECT products.*, categories.name AS category_name 
    FROM products 
    LEFT JOIN categories ON products.category_id = categories.id
");
$products = $stmt->fetchAll();

// Получение списка категорий для формы
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('manage_products') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <style>
    .actions-column {
      white-space: nowrap;
    }
    .actions-column .btn {
      margin-right: 5px;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/../includes/navbar.php'; ?>
  <div class="container mt-5">
    <h2><?= t('manage_products') ?></h2>
    
    <!-- Таблица товаров -->
    <table class="table table-striped mt-4">
      <thead>
        <tr>
          <th>ID</th>
          <th><?= t('title') ?></th>
          <th><?= t('article') ?></th>
          <th><?= t('wholesale_price') ?></th>
          <th><?= t('retail_price') ?></th>
          <th><?= t('quantity_per_box') ?></th>
          <th><?= t('category') ?></th>
          <th><?= t('stock') ?></th>
          <th class="actions-column"><?= t('actions') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
          <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['title']) ?></td>
            <td><?= htmlspecialchars($product['article']) ?></td>
            <td><?= number_format($product['wholesale_price'], 2) ?> ₽</td>
            <td><?= number_format($product['retail_price'], 2) ?> ₽</td>
            <td><?= $product['quantity_per_box'] ?></td>
            <td><?= htmlspecialchars($product['category_name']) ?></td>
            <td><?= htmlspecialchars($product['stock']) ?></td>
            <td class="actions-column">
              <a href="?edit_product=<?= $product['id'] ?>" class="btn btn-warning btn-sm"><?= t('edit') ?></a>
              <a href="?delete_product=<?= $product['id'] ?>" class="btn btn-danger btn-sm"><?= t('delete') ?></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
      <!--  -->
    <?php if (isset($_GET['edit_product'])): ?>
    <?php
    $editId = (int)$_GET['edit_product'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$editId]);
    $editProduct = $stmt->fetch();
    ?>

    <h3 class="mt-5"><?= t('edit_product') ?></h3>
    <form action="process_product.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= $editProduct['id'] ?>">
        <input type="hidden" name="old_image" value="<?= htmlspecialchars($editProduct['image_path']) ?>">
        
        <!-- Все поля формы -->
        <div class="mb-3">
            <label class="form-label"><?= t('title') ?></label>
            <input 
                type="text" 
                name="title" 
                class="form-control" 
                value="<?= htmlspecialchars($editProduct['title']) ?>" 
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label"><?= t('article') ?></label>
            <input 
                type="text" 
                name="article" 
                class="form-control" 
                value="<?= htmlspecialchars($editProduct['article']) ?>" 
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label"><?= t('wholesale_price') ?></label>
            <input 
                type="number" 
                step="0.01" 
                name="wholesale_price" 
                class="form-control" 
                value="<?= htmlspecialchars($editProduct['wholesale_price']) ?>" 
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label"><?= t('retail_price') ?></label>
            <input 
                type="number" 
                step="0.01" 
                name="retail_price" 
                class="form-control" 
                value="<?= htmlspecialchars($editProduct['retail_price']) ?>" 
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label"><?= t('quantity_per_box') ?></label>
            <input 
                type="number" 
                name="quantity_per_box" 
                class="form-control" 
                value="<?= htmlspecialchars($editProduct['quantity_per_box']) ?>" 
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label"><?= t('stock') ?></label>
            <input 
                type="number" 
                name="stock" 
                class="form-control" 
                value="<?= htmlspecialchars($editProduct['stock']) ?>" 
                min="0" 
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label"><?= t('category') ?></label>
            <select name="category_id" class="form-select" required>
                <?php foreach ($categories as $category): ?>
                    <option 
                        value="<?= $category['id'] ?>"
                        <?= $category['id'] == $editProduct['category_id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label"><?= t('description') ?></label>
            <textarea 
                name="description" 
                class="form-control" 
                rows="3"
            ><?= htmlspecialchars($editProduct['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label"><?= t('image') ?></label>
            <input type="file" name="image" class="form-control" accept="image/*">
             <?php if ($editProduct['image_path']): ?>
                <small class="text-muted">
                    <?= t('current_image') ?>: 
                    <a href="../uploads/<?= htmlspecialchars($editProduct['image_path']) ?>" target="_blank">
                        <?= htmlspecialchars($editProduct['image_path']) ?>
                    </a>
                </small>
            <?php endif; ?>
        </div>

        <button type="submit" name="update" class="btn btn-primary"><?= t('update_product') ?></button>
        <a href="add_product.php" class="btn btn-secondary"><?= t('cancel') ?></a>
    </form>
<?php endif; ?>
<!--  -->
    <!-- Форма добавления товара -->
    <h3 class="mt-5"><?= t('add_product') ?></h3>
    <form action="process_product.php" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label"><?= t('title') ?></label>
        <input type="text" name="title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= t('article') ?></label>
        <input type="text" name="article" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= t('wholesale_price') ?></label>
        <input type="number" step="0.01" name="wholesale_price" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= t('retail_price') ?></label>
        <input type="number" step="0.01" name="retail_price" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= t('quantity_per_box') ?></label>
        <input type="number" name="quantity_per_box" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= t('category') ?></label>
        <select name="category_id" class="form-select" required>
          <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= t('description') ?></label>
        <textarea name="description" class="form-control" rows="3"></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= t('stock') ?></label>
        <input 
        type="number" 
        name="stock" 
        class="form-control" 
        value="0" 
        min="0" 
        required>
      </div>

      <div class="mb-3">
        <label class="form-label"><?= t('image') ?></label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
      </div>
      
      

      <button type="submit" class="btn btn-primary"><?= t('add_product') ?></button>
    </form>
  </div>
  

  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>