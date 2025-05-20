<?php if (!is_logged_in()): ?>
    <li class="nav-item">
        <a class="nav-link" href="login.php"><?=t('login')?></a>
    </li>
<?php else: ?>
    <li class="nav-item">
        <a class="nav-link" href="dashboard.php"><?=t('dashboard')?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="logout.php"><?=t('logout')?></a>
    </li>
<?php endif; ?>
