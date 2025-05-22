<?php
require_once 'includes/lang.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Проверка данных
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Валидация
        if (empty($username) || empty($password) || empty($confirmPassword)) {
            throw new Exception(t('fill_all_fields'));
        }

        if ($password !== $confirmPassword) {
            throw new Exception(t('passwords_not_match'));
        }

        if (strlen($password) < 6) {
            throw new Exception(t('password_min_length'));
        }

        // Проверка уникальности username
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn()) {
            throw new Exception(t('username_taken'));
        }

        // Хеширование пароля
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Сохранение в БД
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password_hash, role) 
            VALUES (?, ?, 'user')
        ");
        $stmt->execute([$username, $passwordHash]);

        header('Location: login.php?success=1');
        
        // После успешной регистрации
        if (isset($_SESSION['redirect'])) {
            $redirect = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
            header("Location: $redirect");
            exit;
        } else {
            header('Location: catalog.php');
        }
            
        exit;

    } catch (Exception $e) {
        header('Location: register.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}

header('Location: register.php');