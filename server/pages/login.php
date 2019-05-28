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
    <br>
    <div class="db-table">
      <div class="container">
        <form action="edit.php" method="post" autocomplete="off">
          <table border="1">
            <tr>
              <td>　学籍番号　</td>
              <td><input type="text" name="name" placeholder="xxxxxxxx" maxlength="8"></td>
              <td colspan="2" align="center">
              <a class="loginbutton" type="submit" href="edit.php">login</a>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</body>
</html>