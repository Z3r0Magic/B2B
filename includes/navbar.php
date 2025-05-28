<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/"><?= t('site_title') ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="/catalog.php"><?= t('catalog') ?></a>
        </li>
      </ul>

      <!-- Языковой переключатель -->
      <ul class="navbar-nav me-2">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <?= t('language') ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="?lang=en">English</a></li>
            <li><a class="dropdown-item" href="?lang=ru">Русский</a></li>
          </ul>
        </li>
      </ul>

      <!-- Кнопки авторизации -->
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user'])): ?>
          <?php if ($_SESSION['user']['role'] === 'user'): ?>
            <!-- Для обычных пользователей -->
            <li class="nav-item">
              <a class="nav-link" href="cart.php">
                <?= t('cart') ?>
                <span class="badge bg-danger">
                  <?php
                    $stmt = $pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?");
                    $stmt->execute([$_SESSION['user']['id']]);
                    echo $stmt->fetchColumn();
                  ?>
                </span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="account.php"><?= t('my_account') ?></a>
            </li>
          <?php endif; ?>

          <!-- Выпадающее меню пользователя -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <?= htmlspecialchars($_SESSION['user']['username']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <!-- Общие пункты -->
              <li><a class="dropdown-item" href="/logout.php"><?= t('logout') ?></a></li>

              <!-- Пункты управления (admin/editor) -->
              <?php if (in_array($_SESSION['user']['role'], ['admin', 'editor'])): ?>
                <li><hr class="dropdown-divider"></li>
                <?php if ($_SESSION['user']['role'] === 'editor'): ?>
                  <li><a class="dropdown-item" href="/admin/add_product.php"><?= t('manage_products') ?></a></li>
                <?php else: ?>
                  <li><a class="dropdown-item" href="/admin/dashboard.php"><?= t('admin_panel') ?></a></li>
                  <li><a class="dropdown-item" href="/admin/add_product.php"><?= t('manage_products') ?></a></li>
                  <li><a class="dropdown-item" href="/admin/manage_users.php"><?= t('manage_users') ?></a></li>
                  <li><a class="dropdown-item" href="/admin/orders.php"><?= t('manage_orders') ?></a></li>
                <?php endif; ?>
              <?php endif; ?>
            </ul>
          </li>
        <?php else: ?>
          <!-- Для гостей -->
          <li class="nav-item">
            <a class="nav-link" href="login.php"><?= t('login') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register.php"><?= t('register') ?></a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>