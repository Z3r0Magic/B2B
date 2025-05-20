<?php
require 'includes/lang.php';
require 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('login') ?> - <?= t('site_title') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<div class="container mt-5">
  <h2 class="text-center"><?= t('login') ?></h2>
  <form action="login_process.php" method="post" class="mt-4">
    <div class="mb-3">
      <label for="email" class="form-label"><?= t('email') ?></label>
      <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label"><?= t('password') ?></label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= t('login') ?></button>
  </form>
  <p class="mt-3"><?= t('no_account') ?> <a href="register.php"><?= t('register') ?></a></p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
