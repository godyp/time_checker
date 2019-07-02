<?php
    session_start();
    try{
        $pdo = new PDO('sqlite:../../../db/data.db');
    }catch(PDOException $e){
        exit('Connection failed:'.$e->getMessage());
    }

    //session id information
    $id = $_SESSION['id'];
    $id = intval($id);
    $stmt = $pdo->prepare("SELECT name FROM members WHERE sid = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $name = $stmt->fetch();

    //timestamp infomation
    $timestamp = $_SESSION["timestamp"];
    $timestamp = str_replace('_', ' ', $timestamp);

    function ShowEditHistory(){
        global $pdo;
        global $id;
        global $timestamp;

        $stmt = $pdo->prepare("SELECT * FROM history WHERE sid = ? AND timestamp LIKE ?");
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $timestamp);
        $stmt->execute();
        $date_history = $stmt->fetchAll();
        $history_cnt = count($date_history);

        if($history_cnt!=1){
            echo '<h1>データがありません、あるいは重複しています。</h1>';
            return -1;
        }

        echo '<table class="state"> <tr> <th>入室時刻</th> <th>変更後時刻</th> <th>退室時刻</th> <th>変更後時刻</th> <th>滞在時間</th></tr>';

        $in_time = str_pad($date_history[0]['in_hour'], 2, 0, STR_PAD_LEFT).":". str_pad($date_history[0]['in_minute'], 2, 0, STR_PAD_LEFT);
        $out_time = str_pad($date_history[0]['out_hour'], 2, 0, STR_PAD_LEFT).":". str_pad($date_history[0]['out_minute'], 2, 0, STR_PAD_LEFT);
        echo '<tr>';//行始まり
        echo '<td>'.$in_time .'</td>';//1列目

        echo '<form method="POST" action="updateprocess.php">';
        echo '<td>';
        echo '<label>';
        echo '<input type="time" name="intime" value='.$in_time.'>';
        echo '</label>';
        echo '<input type="hidden" name="pre_intime" value='.$in_time.'>';
        echo '<input class="btn-right-radius" type="submit" name= "in_datapost" value="更新">';
        echo '</td>';
        echo '</form>';//2列目

        echo '<td>'.$out_time.'</td>';//３列目

        echo '<form method="POST" action="updateprocess.php">';
        echo '<td>';
        echo '<label>';
        echo '<input type="time" name= "outtime" value='.$out_time.'>';
        echo '</label>';
        echo '<input type="hidden" name="pre_outtime" value='.$out_time.'>';
        echo '<input class="btn-right-radius" type="submit" name= "out_datapost" value="更新">';
        echo '</td>';
        echo '</form>';//4列目

        $in_hour= intval($date_history[0]['in_hour']);
        $out_hour= intval($date_history[0]['out_hour']);
        $in_min= intval($date_history[0]['in_minute']);
        $out_min= intval($date_history[0]['out_minute']);

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
        echo '<td>'.$staying_time.'<selet id="sel1"></select>'.'</td>';//5列目
        echo '</tr>';//行終わり
        echo '</table>';
        return intval($history_cnt);
    }
?>

<head>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Time Checker ~EDIT~</title>
    <link rel="stylesheet" href="./../css/update_style.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://gsgd.co.uk/sandbox/jquery/easing/jquery.easing.1.3.js"></script>
    <script src="./../js/jquery.bpopup-0.11.0.min.js"></script>
</head>

<body>
    <div class="main">
        <header>
            <div class="container">
                <h2 class="header-left">
                    <?php echo $name[0] ?>さんの勤怠履歴
                </h2>
                <div><a href="./../index.php"  class="home"></a></div>
                <div class="btn header-right"><a href="./edit.php">戻る</a></div>
            </div>

            </div>
        </header>
    </div>

    <div class="db-table">
        <div class="container">
        <h2>任意の時間に編集してください</h2>
            <?php
            $exist = ShowEditHistory();
            ?>
        </div>
    </div>
</body>

</html>
