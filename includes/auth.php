<?php
session_start();
require_once 'db.php';

// Авторизация
function login($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?"); 
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        return true;
    }
    return false;
}
// Проверка роли
function restrictAccess($allowedRoles) {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }

    // Проверяем, входит ли роль пользователя в разрешенные
    if (!in_array($_SESSION['user']['role'], (array)$allowedRoles)) {
        header('Location: /403.php'); // Страница "Доступ запрещен"
        exit;
    }
}

function restrictAccessToUser() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
        header('Location: login.php');
        exit;
    }
}
?>