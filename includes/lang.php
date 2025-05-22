<?php
session_start();

$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'en';
$_SESSION['lang'] = $lang;

$translations = [
    'en' => [
        'site_title' => 'Online Store',
        'welcome_message' => 'Welcome to our store!',
        'catalog' => 'Catalog',
        'login' => 'Login',
        'register' => 'Register',
        'no_account' => 'Don\'t have an account?',
        'login_failed' => 'Invalid username or password',
        'logout' => 'Logout',
        'admin_panel' => 'Admin Panel',
         'manage_users' => 'Manage Users',
        'manage_products' => 'Manage Products',
        'total_orders' => 'Total Orders',
        'total_revenue' => 'Total Revenue',
        'active_users' => 'Active Users',
        'dashboard' => 'Dashboard',
        'language' => 'Language',
        'article' => 'Article',
        'wholesale_price' => 'Wholesale Price',
        'retail_price' => 'Retail Price',
        'quantity_per_box' => 'Quantity per Box',
        'description' => 'Description',
        'category' => 'Category',
        'title' => 'Title',
        'actions' => 'Actions',
        'confirm_password' => 'Confirm Password',
        'fill_all_fields' => 'Please fill all fields.',
        'passwords_not_match' => 'Passwords do not match.',
        'password_min_length' => 'Password must be at least 6 characters.',
        'username_taken' => 'Username is already taken.',
        'already_have_account' => 'Already have an account?',
        'auth_required' => 'You need to register to add products to cart',
        'cart' => 'Cart',
        'cart_empty' => 'Your cart is empty',
        'price' => 'Price',
        'update' => 'Update',
        'remove' => 'Remove',
        'total' => 'Total',
        'checkout' => 'Checkout',
        'not_enough_stock' => 'Not enough stock',
        
        
    ],
    'ru' => [
        'site_title' => 'Интернет Магазин',
        'welcome_message' => 'Добро пожаловать в наш магазин!',
        'catalog' => 'Каталог',
        'login' => 'Вход',
        'register' => 'Регистрация',
        'no_account' => 'Нет аккаунта?',
        'login_failed' => 'Неверный логин или пароль', // Добавлено
        'logout' => 'Выход',
        'admin_panel' => 'Админ Меню',
        'manage_users' => 'Управление пользователями',
        'manage_products' => 'Управление товарами',
        'total_orders' => 'Всего заказов',
        'total_revenue' => 'Итого',
        'active_users' => 'Активные пользователи',
        'dashboard' => 'Информационная панель',
        'language' => 'Язык',
         'article' => 'Артикул',
        'wholesale_price' => 'Оптовая цена',
        'retail_price' => 'Розничная цена',
        'quantity_per_box' => 'Количество в коробке',
        'description' => 'Описание',
        'category' => 'Категория',
        'title' => 'Название',
        'actions' => 'Действия',
        'confirm_password' => 'Подтвердите пароль',
        'fill_all_fields' => 'Заполните все поля.',
        'passwords_not_match' => 'Пароли не совпадают.',
        'password_min_length' => 'Пароль должен быть не менее 6 символов.',
        'username_taken' => 'Имя пользователя занято.',
        'already_have_account' => 'Уже есть аккаунт?',
        'auth_required' => 'Для добавления товаров в корзину необходимо зарегистрироваться',
        'cart' => 'Корзина',
        'cart_empty' => 'Ваша корзина пуста',
        'price' => 'Цена',
        'update' => 'Обновить',
        'remove' => 'Удалить',
        'total' => 'Итого',
        'checkout' => 'Оформить заказ',
        'not_enough_stock' => 'Недостаточно товара',
    ]
];

function t($key) {
    global $lang, $translations;
    return $translations[$lang][$key] ?? $key;
}
?>