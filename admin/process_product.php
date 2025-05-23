<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/lang.php';

restrictAccess(['admin', 'editor']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $productId = $_POST['product_id'] ?? null;
        $isUpdate = isset($_POST['update']);

        // Проверка уникальности артикула
        $stmtCheck = $pdo->prepare("
            SELECT COUNT(*) 
            FROM products 
            WHERE article = ? 
            " . ($isUpdate ? "AND id != ?" : "")
        );

        $params = [$_POST['article']];
        if ($isUpdate) $params[] = $productId;
        $stmtCheck->execute($params);

        if ($stmtCheck->fetchColumn() > 0) {
            throw new Exception(t('article_exists'));
        }

        // Обработка изображения
        $fileName = null;
        $uploadDir = __DIR__ . '/../uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Если есть новое изображение
        if (!empty($_FILES['image']['name'])) {
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                throw new Exception(t('upload_error'));
            }

            // Удаляем старое изображение при обновлении
            if ($isUpdate && !empty($_POST['old_image'])) {
                $oldImagePath = $uploadDir . $_POST['old_image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        } elseif ($isUpdate) {
            // Сохраняем старое изображение
            $fileName = $_POST['old_image'];
        }

        // Подготовка данных
        $data = [
            $_POST['title'],
            $_POST['article'],
            $_POST['wholesale_price'],
            $_POST['retail_price'],
            $_POST['quantity_per_box'],
            $_POST['description'],
            $_POST['category_id'],
            $fileName,
            $_POST['stock']
        ];

        // SQL-запрос
        if ($isUpdate) {
            $query = "
                UPDATE products SET
                    title = ?,
                    article = ?,
                    wholesale_price = ?,
                    retail_price = ?,
                    quantity_per_box = ?,
                    description = ?,
                    category_id = ?,
                    image_path = ?,
                    stock = ?
                WHERE id = ?
            ";
            $data[] = $productId; // Добавляем ID для WHERE
        } else {
            $query = "
                INSERT INTO products (
                    title, article, wholesale_price, retail_price,
                    quantity_per_box, description, category_id, image_path, stock
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";
        }

        // Выполнение запроса
        $stmt = $pdo->prepare($query);
        $stmt->execute($data);

        // Перенаправление
        $message = $isUpdate ? 'product_updated' : 'product_added';
        header("Location: add_product.php?success=" . urlencode(t($message)));
        exit;

    } catch (Exception $e) {
        // Удаление загруженного файла при ошибке
        if (isset($targetPath) && file_exists($targetPath)) {
            unlink($targetPath);
        }
        header("Location: add_product.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}

header("Location: add_product.php?error=" . urlencode(t('unknown_error')));