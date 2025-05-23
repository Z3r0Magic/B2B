<?php
require_once 'includes/lang.php';
require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('register') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'partials/navbar.php'; ?>
  <div class="container mt-5">
    <h2 class="text-center"><?= t('register') ?></h2>
    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger mt-3">
        <?= htmlspecialchars($_GET['error']) ?>
      </div>
    <?php endif; ?>
    <form action="register_process.php" method="post" class="mt-4">
      <div class="mb-3">
        <label class="form-label"><?= t('username') ?></label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= t('password') ?></label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= t('confirm_password') ?></label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= t('phone') ?></label>
        <input 
            type="tel" 
            name="phone" 
            class="form-control" 
            pattern="\+?[0-9\s\-]+" 
            placeholder="+7 (XXX) XXX-XX-XX" 
            required>
        </div>
      <button type="submit" class="btn btn-primary"><?= t('register') ?></button>
    </form>
    <p class="mt-3"><?= t('already_have_account') ?> <a href="login.php"><?= t('login') ?></a></p>
  </div>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>