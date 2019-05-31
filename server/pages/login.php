<?php
session_start();
try{
  $pdo = new PDO('sqlite:./../db/data.db');
  $pdo->exec('CREATE TABLE IF NOT EXISTS member(id INTEGER PRIMARY KEY, name TEXT, status INT)');
  //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  }catch(PDOException $e){
      exit('Connection failed:'.$e->getMessage());
    }

if(isset($_POST['datapost'])){
  $_SESSION['id'] = $_POST['id'];
  $id = $_SESSION['id'];
  $id = intval($id);
  echo $id;
  //$stmt = $pdo->prepare("SELECT * FROM member WHERE id = ?");
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE sid = ?");
  $stmt->bindValue(1, $id);
  $stmt->execute();
  $count =  $stmt->fetchColumn();
  $data = $stmt->fetch();
  if($count == 0){
    header('Location:error.php');
  }else{
    header('Location:history.php');
  }
}
$pdo = NULL; //DB接続解除
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charse="UTF-8">
    <title>Time Checker</title>
    <link rel="stylesheet" href="./../css/login_style.css">
</head>

<body>
  <div class="main">
    <header>
      <div class="container">
        <h1 class="header-left">ログイン</h1>
      </div>
    </header>
  <form action="" method="post" autocomplete="off">
    <table border="1">
      <tr>
        <td>学籍番号</td>
        <td><input type="text" name="id" maxlength="8"></td>
        <td colspan="2" align="center">
          <input type="submit" name= "datapost" value="ログイン">
        </td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>