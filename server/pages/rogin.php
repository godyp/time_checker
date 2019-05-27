<?php
session_start();
if(isset($_POST['datapost'])){
  $_SESSION['id'] = $_POST['id'];
  header('Location:edit.php');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Time Checker</title>
</head>
<body>
  <h1>ログイン</h1>
  <form action="" method="post" autocomplete="off">
    <p>学籍番号</p>
    <input type="text" name="id" maxlength="8">
    <input type="submit" name="datapost">
  </form>
    <!-- <button><a href="./edit.php">ログイン</a></button> -->
</body>
</html>