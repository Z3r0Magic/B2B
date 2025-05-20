
<?php
session_start();
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'ru';
$_SESSION['lang'] = $lang;

$translations = [
    'ru' => [
        'site_title' => 'Интернет-магазин ВладХим',
        'catalog' => 'Каталог',
        'login' => 'Вход',
        'logout' => 'Выход',
        'welcome_message' => 'Добро пожаловать в B2B-магазин бытовой химии!'
    ],
    'en' => [
        'site_title' => 'Online Store',
        'catalog' => 'Catalog',
        'login' => 'Login',
        'logout' => 'Logout',
        'welcome_message' => 'Welcome to the B2B Household Chemicals Store!'
    ]
];

function t($key) {
    global $translations, $lang;
    return $translations[$lang][$key] ?? $key;
}
