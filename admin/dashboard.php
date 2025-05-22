<?php
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

// Проверка прав администратора
restrictAccess('admin');
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('dashboard') ?> - <?= t('site_title') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>
<body>
  <!-- Навигационная панель (та же, что и на главной странице) -->
  <?php include __DIR__ . '/../partials/navbar.php'; ?>

  <div class="container mt-5">
    <h1 class="text-center mb-4"><?= t('dashboard') ?></h1>
    
    <!-- Блоки аналитики -->
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title"><?= t('total_orders') ?></h5>
            <p class="display-4">1,234</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4 mb-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title"><?= t('total_revenue') ?></h5>
            <p class="display-4">$12,345</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-4 mb-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title"><?= t('active_users') ?></h5>
            <p class="display-4">567</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
