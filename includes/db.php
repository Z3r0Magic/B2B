<?php
$host = 'localhost';
$dbname = 'b2b_store';
$user = 'root'; // Логин по умолчанию
$pass = ''; // Пароль по умолчанию

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>