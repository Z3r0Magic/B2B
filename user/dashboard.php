<?php
require_once '../includes/auth.php';
restrictAccess('user'); // Проверка роли
?>
<!-- HTML с приветствием пользователя и его заказами -->