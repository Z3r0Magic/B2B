<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

restrictAccess('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Проверка уникальности артикула
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM products WHERE article = ?");
        $stmtCheck->execute([$_POST['article']]);
        $articleExists = (bool)$stmtCheck->fetchColumn();

        if ($articleExists) {
            throw new Exception("Артикул '{$_POST['article']}' уже существует!");
        }

        // Создание папки uploads, если её нет
        $uploadDir = realpath(__DIR__ . '/../../') . '/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Загрузка изображения
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            throw new Exception("Ошибка загрузки файла!");
        }

        // Вставка данных
        $stmt = $pdo->prepare("
            INSERT INTO products (
                title, 
                article, 
                wholesale_price, 
                retail_price, 
                quantity_per_box, 
                description, 
                category_id, 
                image_path
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['title'],
            $_POST['article'],
            $_POST['wholesale_price'],
            $_POST['retail_price'],
            $_POST['quantity_per_box'],
            $_POST['description'],
            $_POST['category_id'],
            $fileName
        ]);

        header('Location: add_product.php?success=1');
    } catch (Exception $e) {
        // Удаляем загруженный файл, если ошибка после загрузки
        if (isset($targetPath) && file_exists($targetPath)) {
            unlink($targetPath);
        }
        header('Location: add_product.php?error=' . urlencode($e->getMessage()));
    }
    exit;
}

header('Location: add_product.php?error=Неизвестная ошибка');