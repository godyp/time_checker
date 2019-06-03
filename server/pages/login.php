<?php
session_start();
try {
  $pdo = new PDO('sqlite:./../db/data.db');
  $pdo->exec('CREATE TABLE IF NOT EXISTS member(id INTEGER PRIMARY KEY, name TEXT, status INT)');
  //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
  exit('Connection failed:' . $e->getMessage());
}

$matches;
if (isset($_POST['datapost'])) {
  $_SESSION['id'] = $_POST['id'];
  $id = $_SESSION['id'];
  if (preg_match('/[0-9]{8}/u', strval($id), $matches)) {
    $id = intval($id);
    echo $id;
    //$stmt = $pdo->prepare("SELECT * FROM member WHERE id = ?");
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE sid = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $count =  $stmt->fetchColumn();
    $data = $stmt->fetch();
    if ($count == 0) {
      header('Location:error.php');
    } else {
      header('Location:history.php');
    }
  } else {
    $matches = -1;
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
        <h2 class="header-left">~田中・林研　勤怠管理システム~</h2>
        <div class="btn header-right"><a href="../index.php">戻る</a></div>
      </div>
    </header>
    <div class="container">
      <form action="" method="post" autocomplete="off">
        <table>
          <tr>
            <td>学籍番号</td>
            <td><input class="input-sid" type="text" name="id" maxlength="8" autofocus></td>
          </tr>
        </table>
        <input class="btn login" type="submit" name="datapost" value="ログイン">
        <?php
        global $matches;
        if ($matches) {
          echo '<p class="error1">※8桁の学籍番号を入力してください</p>';
        }
        ?>
      </form>
    </div>
  </div>
</body>

</html>