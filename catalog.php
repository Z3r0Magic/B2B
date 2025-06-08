<?php
require_once 'includes/auth.php';
require_once 'includes/lang.php';
require_once 'includes/db.php';

// Получение параметров фильтрации
$search = $_GET['search'] ?? '';
$category_id = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'title';
$stock_range = $_GET['stock'] ?? '';

// Базовый SQL-запрос
$sql = "
    SELECT products.*, categories.name AS category_name 
    FROM products 
    LEFT JOIN categories ON products.category_id = categories.id
    WHERE 1=1
";

$params = [];

// Добавление условий фильтрации
if (!empty($search)) {
    $sql .= " AND products.title LIKE ?";
    $params[] = "%$search%";
}

if (!empty($category_id)) {
    $sql .= " AND products.category_id = ?";
    $params[] = $category_id;
}

// Фильтр по остаткам
$stock_conditions = [
    '1-50' => 'stock BETWEEN 1 AND 50',
    '50-100' => 'stock BETWEEN 50 AND 100',
    '100-500' => 'stock BETWEEN 100 AND 500'
];

if (!empty($stock_range) && isset($stock_conditions[$stock_range])) {
    $sql .= " AND " . $stock_conditions[$stock_range];
}

// Сортировка
$sort_options = [
    'price_asc' => 'wholesale_price ASC',
    'price_desc' => 'wholesale_price DESC',
    'title' => 'title ASC'
];
$order = $sort_options[$sort] ?? 'title ASC';
$sql .= " ORDER BY $order";

// Выполнение запроса
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Получение категорий для фильтра
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('catalog') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 15px;
        overflow: hidden;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .card-img-top {
        object-fit: cover;
        height: 250px;
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
    .filter-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
    }
    .stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  
  <div class="container mt-5">
    <h1 class="mb-4"><?= t('catalog') ?></h1>
    
    <!-- Фильтры -->
<div class="filter-section">
    <form method="GET" class="row g-3 align-items-center">
        <div class="col-12 col-md-3">
            <input type="text" name="search" class="form-control" 
                   placeholder="<?= t('search_placeholder') ?>" 
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        
        <div class="col-6 col-md-2">
            <select name="category" class="form-select">
                <option value=""><?= t('all_categories') ?></option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" 
                        <?= $category_id == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-6 col-md-2">
            <select name="stock" class="form-select">
                <option value=""><?= t('all_stock') ?></option>
                <option value="1-50" <?= $stock_range == '1-50' ? 'selected' : '' ?>>
                    30-50 <?= t('items') ?>
                </option>
                <option value="50-100" <?= $stock_range == '50-100' ? 'selected' : '' ?>>
                    50-100 <?= t('items') ?>
                </option>
                <option value="100-500" <?= $stock_range == '100-500' ? 'selected' : '' ?>>
                    100-500 <?= t('items') ?>
                </option>
            </select>
        </div>
        
        <div class="col-8 col-md-3">
            <select name="sort" class="form-select">
                <option value="title" <?= $sort == 'title' ? 'selected' : '' ?>>
                    <?= t('sort_by_name') ?>
                </option>
                <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>
                    <?= t('price_low_high') ?>
                </option>
                <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>
                    <?= t('price_high_low') ?>
                </option>
            </select>
        </div>
        
        <div class="col-4 col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <?= t('apply_filters') ?>
            </button>
        </div>
    </form>
</div>

    <!-- Список товаров -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100 product-card">
                    <?php if ($product['stock'] > 0): ?>
                        <span class="badge bg-success stock-badge">
                            <?= $product['stock'] ?> <?= t('in_stock') ?>
                        </span>
                    <?php endif; ?>
                    
                    <img src="uploads/<?= htmlspecialchars($product['image_path']) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($product['title']) ?>">
                    
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['title']) ?></h5>
                        <p class="text-muted small mb-2">
                            <?= t('category') ?>: <?= htmlspecialchars($product['category_name']) ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-primary fw-bold">
                                    <?= number_format($product['wholesale_price'], 2) ?> ₽
                                </span>
                                <span class="text-muted small d-block">
                                    <?= t('per_box') ?> (<?= $product['quantity_per_box'] ?> шт.)
                                </span>
                            </div>
                            <div class="btn-group">
                                <a href="product.php?id=<?= $product['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <?= t('details') ?>
                                </a>
                                <button class="btn btn-sm btn-primary add-to-cart" 
                                        data-product-id="<?= $product['id'] ?>">
                                    <?= t('buy') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('product-id');
        $.post('cart_add.php', { product_id: productId, quantity: 1 }, function() {
            alert('<?= t('product_added') ?>');
        });
    });
  </script>
</body>
</html>