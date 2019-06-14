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
    $stmt = $pdo->prepare("SELECT * FROM history WHERE sid = ? AND in_year = ? AND in_month = ? AND in_day = ?");
    $stmt->bindValue(1, $id);
    $stmt->bindValue(2, $year);
    $stmt->bindValue(3, $month);
    $stmt->bindValue(4, $day);
    $stmt->execute();
    $date_history = $stmt->fetchAll();
    $history_cnt = count($date_history);


    if($history_cnt==0){
        echo '<h1>選択した日のデータはありません</h1>';
        return -1;
    }

    echo '<table class="state"> <tr> <th>入室時刻</th> <th>変更後時刻</th> <th>退室時刻</th> <th>変更後時刻</th> <th>滞在時間</th></tr>';
    for($i=0;$i<$history_cnt;$i++){
        $in_time = str_pad($date_history[$i]['in_hour'], 2, 0, STR_PAD_LEFT).":". str_pad($date_history[$i]['in_minute'], 2, 0, STR_PAD_LEFT);
        $out_time = str_pad($date_history[$i]['out_hour'], 2, 0, STR_PAD_LEFT).":". str_pad($date_history[$i]['out_minute'], 2, 0, STR_PAD_LEFT);


        echo '<tr>';//行始まり
        echo '<td>'.$in_time .'</td>';//1列目

        echo '<form method="POST" action="update.php">';
        echo '<td>'; 
        echo '<input type="time" name="intime" value='.$in_time.'>';
        echo '<input type="hidden" name="pre_intime" value='.$in_time.'>';
        //echo '<input type="time" name="intime" value="13:40">';
        echo '<input type="submit" name= "in_datapost" value="更新">';
        echo '</td>';
        echo '</form>';//2列目

        echo '<td>'.$out_time.'</td>';//３列目

        echo '<form method="POST" action="update.php">';
        echo '<td>';
        echo '<input type="time" name= "outtime" value='.$out_time.'>';
        echo '<input type="hidden" name="pre_outtime" value='.$out_time.'>';
        echo '<input type="submit" name= "out_datapost" value="更新">';
        echo '</td>';
        echo '</form>';//4行目

        $in_hour= intval($date_history[$i]['in_hour']);
        $out_hour= intval($date_history[$i]['out_hour']);
        if( $date_history[$i]['in_day'] === $date_history[$i]['out_day']){
            $staying_time = $out_hour - $in_hour + 1;
        }else{
            $staying_time = (24 - $in_hour) + $out_hour +1 ;
        }

        echo '<td>'.$staying_time.'<selet id="sel1"></select>'.'</td>';//5列目
        echo '</tr>';//行終わり
    }
    echo '</table>';
    return intval($history_cnt);
}

// if(isset($_POST['datapost'])){
//     header('Location:update.php');
// }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Time Checker ~EDIT~</title>
    <link rel="stylesheet" href="./../css/edit_style.css">
    <script  src="./../js/edit.js"></script>
</head>

<body>
    <div class="main">
        <header>
            <div class="container">
                <h2 class="header-left">
                    <?php echo $name[0] ?>さんの勤怠履歴
                </h2>
            </div>
        </header>
    </div>

    <div class="db-table">
        <div class="container">
            <?php
            //echo '<form action="update.php" method="post" autocomplete="off">';
            $exist = ShowOneDayHistory($year,$month,$day);
            //echo '</form>';
            $pdo = NULL;
            ?>
        </div>
    </div>
    <button><a href="../index.php">GO HOME</a></button>
</body>

</html>
