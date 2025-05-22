<?php
session_start(); // Добавьте эту строку в начало файла
require_once 'db.php';

// Авторизация
function login($username, $password) {
    global $pdo;
    
    // Используйте имя поля, которое хранится в БД (username или email)
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
function restrictAccess($requiredRole) {
    if ($_SESSION['user']['role'] !== $requiredRole) {
        header('Location: /login.php');
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