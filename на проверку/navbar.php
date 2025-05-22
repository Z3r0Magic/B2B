

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
      
      <!-- Языковой переключатель (абсолютные пути) -->
      <ul class="navbar-nav me-2">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <?= t('language') ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/?lang=en">English</a></li>
            <li><a class="dropdown-item" href="/?lang=ru">Русский</a></li>
          </ul>
        </li>
      </ul>

      <!-- Кнопки авторизации -->
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <?= htmlspecialchars($_SESSION['user']['username']) ?>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="/logout.php"><?= t('logout') ?></a></li>
              <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                <li><a class="dropdown-item" href="/admin/dashboard.php"><?= t('admin_panel') ?></a></li>
                <li><a class="dropdown-item" href="/admin/manage_users.php"><?= t('manage_users') ?></a></li>
                <li><a class="dropdown-item" href="/admin/add_product.php"><?= t('manage_products') ?></a></li>
              <?php endif; ?>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/login.php"><?= t('login') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/register.php"><?= t('register') ?></a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Подключение Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
