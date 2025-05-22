<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $productId = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];

  $stmt = $pdo->prepare("
    UPDATE cart 
    SET quantity = ? 
    WHERE user_id = ? AND product_id = ?
  ");
  $stmt->execute([$quantity, $_SESSION['user']['id'], $productId]);
}

header('Location: cart.php');