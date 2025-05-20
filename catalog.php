
<?php
require_once 'includes/lang.php';
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('catalog') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<div class="container mt-5">
  <h1><?= t('catalog') ?></h1>
  <p>Каталог товаров будет здесь.</p>
</div>
</body>
</html>
