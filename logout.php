
<?php
session_start();

// Сохраняем язык перед выходом
$lang = $_SESSION['lang'] ?? 'en';

// Уничтожаем сессию
session_destroy();

// Перенаправляем на главную с сохранением языка
header("Location: /index.php?lang=$lang");
exit;
?>