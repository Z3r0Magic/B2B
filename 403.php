<?php
require_once 'includes/lang.php';
require_once 'includes/auth.php';

// Устанавливаем HTTP-статус 403 Forbidden
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= t('error_403') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-403 {
            margin-top: 100px;
            text-align: center;
        }
        .error-code {
            font-size: 120px;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <?php include 'partials/navbar.php'; ?>
    
    <div class="container container-403">
        <div class="error-code">403</div>
        <h1 class="mt-3"><?= t('access_denied') ?></h1>
        <p class="lead"><?= t('access_denied_description') ?></p>
        <a href="/" class="btn btn-primary mt-3"><?= t('back_to_home') ?></a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>