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
    <script  src="./../js/edit.js"></script>
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
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM history WHERE sid = ?");
                    $stmt->bindValue(1, $id);
                    $stmt->execute();
                    $data = $stmt->fetchAll();

                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM history WHERE sid = ?");
                    $stmt->bindValue(1, $id);
                    $stmt->execute();
                    $count =  $stmt->fetchColumn();

                    $latest_month = $data[$count-1]['in_month'];
                    $latest_month = intval( $latest_month );

                    $record_num = 0;
                    $position = $count-$record_num;

                    if( $latest_month===1 || $latest_month===2 ){
                        $value = 12;
                    }else{
                        $value = 0;
                    }

                    for($i = $latest_month+$value; $i>2; $i--){
                        $input_month=$i-$value;
                        echo '<b>'.$input_month.'月</b>';

                        echo '<table class="state"> <tr> <th>日付</th> <th>入室時刻</th> <th>退室時刻</th> <th>滞在時間</th>　<th>編集</th> </tr>';
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM history WHERE sid = ? AND in_month = ? ");
                        $stmt->bindValue(1, $id);
                        $stmt->bindValue(2, $input_month);
                        $stmt->execute();
                        $record_num  = $stmt->fetchColumn();

                        if($i-$value===$latest_month){
                            $position = $count-$record_num;
                        }else{
                            $position -= $record_num;
                        }

                        for ($j=$position; $j<$position+$record_num; $j++) {
                            echo '<tr>';
                            echo '<td>'.$data[$j]['in_day'].'</td>';
                            echo '<td>'.$data[$j]['in_hour'].':'. $data[$j]['in_minute'].'</td>';
                            echo '<td>'.$data[$j]['out_hour'].':'.$data[$j]['out_minute'].'</td>';
                            if( $data[$j]['in_day'] === $data[$j]['out_day']){
                                $staying_time = intval($data[$j]['in_time']) - intval($data[$j]['out_time']);
                            }else{
                                $staying_time = (24 - intval($data[$j]['in_time'])) + intval($data[$j]['out_time']);
                            }
                            echo '<td>'.$staying_time.'</td>';
                            echo '<td><input value="  編集  " class="btn-right-radius"type="button" onClick="disp()"></td>';
                            echo '</tr>';
                        }
                       echo '</table>';                    }
                    ?>
            </div>
        </div>

    <button><a href="../index.php">GO HOME</a></button>
</body>

</html>