<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/lang.php';

restrictAccess('admin');

// Включение вывода ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Повышение пользователя до редактора
    if (isset($_GET['promote'])) {
        $userId = (int)$_GET['promote'];
        $stmt = $pdo->prepare("UPDATE users SET role = 'editor' WHERE id = ?");
        $stmt->execute([$userId]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception(t('user_not_found_or_already_editor'));
        }
        
        header('Location: manage_users.php?success=' . urlencode(t('user_promoted')));
        exit;
    }

    // Добавление нового редактора
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];

        // Проверка уникальности имени пользователя
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception(t('username_taken'));
        }

        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role, phone) VALUES (?, ?, 'editor', ?)");
        $stmt->execute([$username, $password, $phone]);
        
        header('Location: manage_users.php?success=' . urlencode(t('editor_added')));
        exit;
    }
} catch (Exception $e) {
    header('Location: manage_users.php?error=' . urlencode($e->getMessage()));
    exit;
}

header('Location: manage_users.php');