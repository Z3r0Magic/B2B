<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$productId = (int)$_GET['product_id'];

$stmt = $pdo->prepare("
  DELETE FROM cart 
  WHERE user_id = ? AND product_id = ?
");
$stmt->execute([$_SESSION['user']['id'], $productId]);

header('Location: cart.php');