<?php
// 変数の初期化
$db = null;
$sql = null;
$res = null;
$row = null;

$db = new SQLite3("../../db/data.db");
// データの取得
$sql = "SELECT name,sid FROM members ORDER BY sid ASC";
$res = $db->query($sql);

if (isset($_POST["sub"])) {
    $sql = "SELECT count(*) FROM message";
    $cnt = $db->query($sql)->fetchArray();
    $rep = $_POST["report"];
    if ($rep != "") {
        $sql = "INSERT INTO message VALUES(" . strval($cnt[0]) . ",\"" . strval($rep) . "\", 0)";
        $db->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Time Checker</title>
    <link rel="stylesheet" href="./css/index_style.css">
</head>

<body>
    <div class="main">
        <header>
            <div class="container">
                <h1 class="header-left">HOME</h1>
                <h2 class="header-left">~田中・林研　勤怠管理システム~</h2>
                <a class="btn" href="./pages/login.php">ログイン</a>
            </div>
        </header>
        <div class="container">
            <div class="db-table">
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
        <div>
            <div class="container">
                <div class="message">
                    <form class="area" method="post">
                        <p>[Message]</p>
                        <textarea class="text" name="report" rows="4" cols="40" placeholder="エラー報告／欲しい機能"></textarea><br>
                        <input class="message-btn" type="submit" value="送信" name="sub"><input class="message-btn" type="reset" value="リセット">
                    </form>
                    <div class="">
                        <div class="task">
                            <?php
                            // データの取得
                            $sql = "SELECT message,status FROM message";
                            $res = $db->query($sql);
                            echo '<ul class="info">';
                            echo "未解決";
                            while ($row = $res->fetchArray()) {
                                if ($row[1] == 0) {
                                    echo '<li>' . $row[0] . '</li>';
                                }
                            }
                            echo '</ul>';
                            echo '<ul class="info">';
                            echo "解決済み";
                            while ($row = $res->fetchArray()) {
                                if ($row[1] == 1) {
                                    echo '<li>' . $row[0] . '</li>';
                                }
                            }
                            echo ' </ul>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>