<?php
session_start();
try{
    $pdo = new PDO('sqlite:../../../db/data.db');
    //$pdo->exec('CREATE TABLE IF NOT EXISTS member(id INTEGER PRIMARY KEY, name TEXT, status INT)');
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }catch(PDOException $e){
        exit('Connection failed:'.$e->getMessage());
    }

$id = $_SESSION['id'];
$id = intval($id);
$stmt = $pdo->prepare("SELECT name FROM members WHERE sid = ?");
$stmt->bindValue(1, $id);
$stmt->execute();
$name = $stmt->fetch();

$set_date = $_SESSION['date'];
list($year,$month,$day) = explode('-', $set_date );
$year = ltrim($year, '0');
$month = ltrim($month, '0');
$day = ltrim($day, '0');

function ShowOneDayHistory(){
    global $pdo;
    global $id;
    global $year, $month, $day;

    echo '<h4>'.$year.'年'.$month.'月'.$day.'日の編集<h4>';
    $date = sprintf('%04s-%02s-%02s%%',$year, $month, $day);
    $stmt = $pdo->prepare("SELECT * FROM history WHERE sid = ? AND timestamp LIKE ? ORDER BY timestamp ASC");
    $stmt->bindValue(1, $id);
    $stmt->bindValue(2, $date);
    $stmt->execute();
    $date_history = $stmt->fetchAll();
    $history_cnt = count($date_history);

    if($history_cnt==0){
        echo '<h1>選択した日のデータはありません</h1>';
        return -1;
    }

    echo '<table class="state"> <tr> <th>入室時刻</th> <th>退室時刻</th> <th>滞在時間</th> <th></th> </tr>';
    for($i=0;$i<$history_cnt;$i++){
        $in_time = str_pad($date_history[$i]['in_hour'], 2, 0, STR_PAD_LEFT).":". str_pad($date_history[$i]['in_minute'], 2, 0, STR_PAD_LEFT);
        $out_time = str_pad($date_history[$i]['out_hour'], 2, 0, STR_PAD_LEFT).":". str_pad($date_history[$i]['out_minute'], 2, 0, STR_PAD_LEFT);
        $timestamp = $date_history[$i]['timestamp'];
        echo '<tr>';//行始まり
        echo '<td>'.$in_time .'</td>';//1列目
        echo '<td>'.$out_time.'</td>';//2列目

        $in_hour= intval($date_history[$i]['in_hour']);
        $out_hour= intval($date_history[$i]['out_hour']);
        $in_min= intval($date_history[$i]['in_minute']);
        $out_min= intval($date_history[$i]['out_minute']);

        if($in_hour < $out_hour){
            if($in_min <= $out_min){
                $staying_time = $out_hour - $in_hour;
                $staying_min = $out_min -$in_min;
                if($staying_min>30)$staying_time+=1;
            }else{
                $staying_time = $out_hour - $in_hour - 1;
                $staying_min = 60 - $in_min + $out_min;
                if($staying_min>30)$staying_time+=1;
            }

        }elseif($in_hour > $out_hour){
            if($in_min <= $out_min){
                $staying_time = 24 - $in_hour + $out_hour;
                $staying_min = $out_min -$in_min;
                if($staying_min>30)$staying_time+=1;
            }else{
                $staying_time = 24 -  $in_hour + $out_hour - 1;
                $staying_min = 60 - $in_min + $out_min;
                if($staying_min>30)$staying_time+=1;
            }

        }else{
            if($in_min <= $out_min){
                $staying_time = 0;
                $staying_min = $out_min - $in_min;
                if($staying_min>30)$staying_time+=1;
            }else{
                $staying_time = 23;
                $staying_min = 60 - $in_min + $out_min;
                if($staying_min>30)$staying_time+=1;
            }
        }

        echo '<td>'.$staying_time.'<selet id="sel1"></select>'.'</td>';//3列目
        $timestamp = str_replace(' ', '_', $timestamp);

        echo '<td>';
        echo '<form method="POST" action="">';
        echo '<input type="hidden" name="timestamp" value='.$timestamp.'>';
        echo '<input class="btn-gradient-radius" type="submit" name="select" value="編集">';
        echo '</form>';
        echo '</td>';//4列目

        echo '</tr>';//行終わり

    }
    echo '</table>';
    return intval($history_cnt);
}

if(isset($_POST['select'])){
    $_SESSION['timestamp'] = $_POST['timestamp'];
    //$date = $_SESSION['date'];
    header('Location:update.php');
  }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Time Checker ~EDIT~</title>
    <link rel="stylesheet" href="./../css/edit_style.css">
</head>

<body>
    <div class="main">
        <header>
            <div class="container">
                <h2 class="header-left">
                    <?php echo $name[0] ?>さんの勤怠履歴
                </h2>
                <div><a href="./../index.php"  class="home"></a></div>
                <div class="btn header-right"><a href="./history.php">戻る</a></div>
            </div>
        </header>
    </div>

    <div class="db-table">
        <div class="container">
            <?php
            $exist = ShowOneDayHistory($year,$month,$day);
            $pdo = NULL;
            ?>
        </div>
    </div>
</body>

</html>
