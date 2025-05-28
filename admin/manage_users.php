<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/lang.php';
require_once __DIR__ . '/../includes/db.php';

restrictAccess('admin'); // Только администратор

// Удаление пользователя
// В manage_users.php
if (isset($_GET['delete_user'])) {
    $userId = (int)$_GET['delete_user'];
    
    // Удаление связанных заказов и элементов заказов
    $pdo->beginTransaction();
    try {
        // Удаление элементов заказов
        $stmt = $pdo->prepare("DELETE oi FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            WHERE o.user_id = ?");
        $stmt->execute([$userId]);
        
        // Удаление заказов
        $stmt = $pdo->prepare("DELETE FROM orders WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        // Удаление пользователя
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->execute([$userId]);
        
        $pdo->commit();
        header('Location: manage_users.php?success='.urlencode(t('user_deleted')));
    } catch (Exception $e) {
        $pdo->rollBack();
        header('Location: manage_users.php?error='.urlencode(t('delete_error')));
    }
    exit;
}

// Получение списка редакторов и пользователей
$stmt = $pdo->query("SELECT * FROM users WHERE role IN ('editor', 'user')");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="UTF-8">
  <title><?= t('manage_users') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include __DIR__ . '/../includes/navbar.php'; ?> 
  <div class="container mt-5">
    <h2><?= t('manage_users') ?></h2>
    
    <!-- Таблица пользователей -->
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th><?= t('username') ?></th>
          <th><?= t('role') ?></th>
          <th><?= t('phone') ?></th>
          <th><?= t('actions') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= $user['role'] ?></td>
            <td><?= $user['phone'] ?></td>
            <td>
               <?php if ($user['role'] === 'editor'): ?>
              <a href="?delete_user=<?= $user['id'] ?>" class="btn btn-danger btn-sm"><?= t('delete') ?></a>
              <a href="process_user.php?demote=<?= $user['id'] ?>" class="btn btn-warning btn-sm"><?= t('demote') ?></a>
            <?php else: ?>
              <a href="process_user.php?promote=<?= $user['id'] ?>" class="btn btn-success btn-sm"><?= t('promote_to_editor') ?></a>
            <?php endif; ?>
            
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Форма добавления редактора -->
    <h3 class="mt-5"><?= t('add_editor') ?></h3>
    <form action="process_user.php" method="post">
      <div class="mb-3">
        <label class="form-label"><?= t('username') ?></label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label"><?= t('password') ?></label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
          <label class="form-label"><?= t('phone') ?></label>
          <input type="tel" name="phone" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary"><?= t('add_editor') ?></button>
    </form>
  </div>
</body>
</html>