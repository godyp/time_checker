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

$id = $_SESSION['id'];
$id = intval($id);
$stmt = $pdo->prepare("SELECT * FROM members WHERE sid = ?");
$stmt->bindValue(1, $id);
$stmt->execute();
$data = $stmt->fetch();
//$pdo = NULL; //DB接続解除
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Time Checker</title>
    <link rel="stylesheet" href="./../css/edit_style.css">
</head>

<body>
    <div class="main">
        <header>
            <div class="container">
                <h2 class="header-left">
                    <?php echo $data['name'] ?>さんの勤怠履歴
                </h2>
            </div>
        </header>
    </div>

    <div class="db-table">
            <div class="container">
                <h3>履歴一覧</h3>
                <table class="state">
                    <tr>
                        <th>名前</th>
                        <th>入室時刻</th>
                        <th>退室時刻</th>
                    </tr>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM history WHERE sid = ?");
                    $stmt->bindValue(1, $id);
                    $stmt->execute();
                    $data = $stmt->fetchAll();

                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM history WHERE sid = ?");
                    $stmt->bindValue(1, $id);
                    $stmt->execute();
                    $count =  $stmt->fetchColumn();

                    for ($i=0; $i<$count; $i++) {
                        echo '<tr>';
                        echo '<td>' . $data[$i]['name']. '</td>';
                        echo '<td>' . $data[$i]['in_time'] . '</td>';
                        echo '<td>' . $data[$i]['out_time'] . '</td>';
                        echo '</tr>';
                        $i += 1;
                    }
                    ?>
                </table>
            </div>
        </div>

    <button><a href="../index.php">GO HOME</a></button>
</body>

</html>