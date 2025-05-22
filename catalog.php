<?php
require_once 'includes/auth.php';
require_once 'includes/lang.php';
require_once 'includes/db.php';

// Получение товаров
$stmt = $pdo->query("
    SELECT products.*, categories.name AS category_name 
    FROM products 
    LEFT JOIN categories ON products.category_id = categories.id
");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('catalog') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-card {
      transition: transform 0.2s;
    }
    .product-card:hover {
      transform: translateY(-5px);
    }
  </style>
</head>
<body>
  <?php include 'partials/navbar.php'; ?>
  
  <div class="container mt-5">
    <h1 class="mb-4"><?= t('catalog') ?></h1>
    
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($products as $product): ?>
        <div class="col">
          <div class="card h-100 product-card">
            <img 
              src="/uploads/<?= htmlspecialchars($product['image_path']) ?>" 
              class="card-img-top" 
              alt="<?= htmlspecialchars($product['title']) ?>"
              style="height: 200px; object-fit: cover"
            >
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
              <p class="text-muted"><?= t('wholesale_price') ?>: $<?= number_format($product['wholesale_price'], 2) ?></p>
              <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary"><?= t('details') ?></a>
              <button 
                class="btn btn-primary add-to-cart" 
                data-product-id="<?= $product['id'] ?>"
              >
                <?= t('buy') ?>
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Добавление в корзину через AJAX
    $(document).on('click', '.add-to-cart', function() {
      const productId = $(this).data('product-id');
      $.post('cart_add.php', { product_id: productId, quantity: 1 }, function() {
        alert('<?= t('product_added') ?>');
      });
    });
  </script>
</body>
</html>