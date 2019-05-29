<!-- 実機では
29   $command = "python ../../reader/sample/getidm.py ";
44   $command = "python ../../reader/sample/addmem.py " . $_POST['idm'] . " " . $_POST['sid'] . " " . $_POST['name'];
のsampleを消してから実行する
-->

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Time Checker</title>
    <link rel="stylesheet" href="../css/index_style.css">
    <link rel="stylesheet" href="../css/signup.css">
</head>

<body>
    <div class="main">
        <header>
            <div class="container">
                <h1 class="header-left">新規登録</h1>
                <h2 class="header-left">~田中・林研　勤怠管理システム~</h2>
                <a class="header-right" href="../index.php">戻る</a>
            </div>
        </header>

        <div class="container">
            <form action="" method="post">
                <input class="start" id="startsignup" type="submit" name="start" value="新規登録を開始する(Touch FeliCa)">
                <?php
                $idm;
                if (isset($_POST['start'])) {
                    $command = "python ../../reader/getidm.py ";
                    exec($command, $output);
                    echo '<p>FeliCa IDm = ' . $output[0] . '</p>';
                    $idm = $output[0];
                    echo '<div class="sign-up">
                            <p><input type="hidden" name="idm" value="' . $idm . '"></p>
                            <p>学籍番号：<input type="text" name="sid" size="40"></p>
                            <p>　名前　：<input type="text" name="name" size="40"></p>
                            <input class="register" type="submit" name="register" value="登録する">
                        </div>';
                }
                if (isset($_POST['register'])) {
                    $command = "python ../../reader/addmem.py " . $_POST['idm'] . " " . $_POST['sid'] . " " . $_POST['name'];
                    exec($command, $output);
                    echo '<div class="sign-up">
                            <p>以下の情報で登録しました</p>
                            <p>FeliCa IDm : ' . $output[0] . '</p>
                            <p>学籍番号 : ' . $output[1] . '</p>
                            <p>名　　前 : ' . $output[2] . '</p>
                    </div>';
                }
                ?>
            </form>
        </div>
    </div>
</body>

</html>