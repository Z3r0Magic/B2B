<?php
// Генерируем хеш для пароля admin123
$newHash = password_hash('admin123', PASSWORD_DEFAULT);
echo "Скопируйте этот хеш в БД: <strong>" . $newHash . "</strong>";
?>