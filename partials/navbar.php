<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><?= t('site_title') ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="catalog.php"><?= t('catalog') ?></a>
        </li>
        <?php if (!is_logged_in()): ?>
          <li class="nav-item">
            <a class="nav-link" href="login.php"><?= t('login') ?></a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><?= t('dashboard') ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php"><?= t('logout') ?></a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
