<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

restrictAccess('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Проверка существования пользователя
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'editor')");
    $stmt->execute([$username, $password]);
    header('Location: manage_users.php');
    exit;
}

// Повышение пользователя до редактора
if (isset($_GET['promote'])) {
    $userId = (int)$_GET['promote'];
    $stmt = $pdo->prepare("UPDATE users SET role = 'editor' WHERE id = ?");
    $stmt->execute([$userId]);
    header('Location: manage_users.php');
    exit;
}