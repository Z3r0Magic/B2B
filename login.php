<?php
require 'includes/lang.php';
session_start();
$error='';
$users = json_decode(file_get_contents('users.json'),true);
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $u = $_POST['login']; $p = $_POST['password'];
  if (isset($users[$u]) && password_verify($p,$users[$u]['hash'])) {
    $_SESSION['user']=['login'=>$u,'role'=>$users[$u]['role']];
    header('Location: index.php'); exit;
  }
  $error='Неверные данные';
}
?>
<!DOCTYPE html><html lang="<?=$lang?>"><head><meta charset="UTF-8"><title><?=t('login')?></title>
<link href="..." rel="stylesheet"></head><body><!-- форма--></body></html>