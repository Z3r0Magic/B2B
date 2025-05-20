
<?php
require_once 'includes/lang.php';
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('site_title') ?></title>
  <link href="css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<div class="container mt-5 text-center">
  <h1><?= t('site_title') ?></h1>
  <p><?= t('welcome_message') ?></p>
  <a class="btn btn-primary" href="catalog.php"><?= t('catalog') ?></a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
