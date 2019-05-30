<?php
// 変数の初期化
$db = null;
$sql = null;
$res = null;
$row = null;

$db = new SQLite3("./db/data.db");
// データの取得
$sql = "SELECT name,sid FROM members";
$res = $db->query($sql);
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Time Checker:admin</title>
    <link rel="stylesheet" href="./css/index_style.css">
</head>

<body>
    <div class="main">
        <header>
            <div class="container">
                <h1 class="header-left">HOME</h1>
                <h2 class="header-left">~田中・林研　勤怠管理システム~</h2>
                <a class="header-right" href="./pages/login.php">ログイン</a>
                <a class="header-right" href="./pages/signup.php">新規登録</a>
            </div>
        </header>
        <div class="right">
            <form action="" method="post">
                <ul>
                    <li class="btn"><input class="start" id="" type="submit" name="start" value="開始"></li>
                    <?php
                    if (isset($_POST['start'])) {
                        $command = "python ../../reader/read.py";
                        exec($command, $output);
                    }
                    ?>
                    <li class="btn"><input class="stop" id="" type="submit" name="stop" value="停止"></li>
                    <?php
                    if (isset($_POST['stop'])) {
                        $command = "ps alx | grep 'python read.py'";
                        exec($command, $output);
                        list($f, $uid, $pid, $ppid, $pri, $ni, $vsz, $rss, $wchan, $stat, $tty, $time, $cmd)  = split("[[:space:]]+", $output[0]);
                        $command = "kill " . $pid;
                        system($command);
                    }
                    ?>
                </ul>
            </form>
        </div>
        <div class="db-table">
            <div class="container">
                <h3>現在の状態</h3>
                <table class="state">
                    <tr>
                        <th>名前</th>
                        <th>学内</th>
                        <th>帰宅</th>
                    </tr>
                    <?php
                    while ($row = $res->fetchArray()) {
                        $sql = "SELECT sid FROM status WHERE sid=" . $row[1];
                        $res_sts = $db->query($sql);
                        $row_sts = $res_sts->fetchArray();
                        echo '<tr>';
                        echo '<td>' . $row[0] . '</td>';
                        if ($row_sts[0] != NULL) {
                            echo '<td>◯</td>';
                            echo '<td>-</td>';
                        } else {
                            echo '<td>-</td>';
                            echo '<td>◯</td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>