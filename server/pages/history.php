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
$stmt = $pdo->prepare("SELECT name FROM members WHERE sid = ?");
$stmt->bindValue(1, $id);
$stmt->execute();
$name = $stmt->fetch();

function ShowMonthHistory($month, $year){
    global $pdo;
    global $id;

    $stmt = $pdo->prepare("SELECT * FROM history WHERE sid = ? AND in_month = ? AND in_year = ? ORDER BY in_day ASC");
    $stmt->bindValue(1, $id);
    $stmt->bindValue(2, $month);
    $stmt->bindValue(3, $year);
    $stmt->execute();
    $history = $stmt->fetchAll();
    $history_cnt = count($history);

    if($history_cnt==0)return -1;

    echo '<h2>'.$year.'年'.$month.'月</h2>';
    echo '<form action="" method="post" autocomplete="off">';
    echo '<h2><input type="date" name= "date" value="'.date("Y-m-d").'"></td></h2>';
    echo '<h2><input type="submit" name= "datapost" value="編集"></h2>';
    echo '</form>';

    echo '<table class="state"> <tr> <th>日付</th> <th>入室時刻</th> <th>退室時刻</th> <th>滞在時間</th></tr>';
    for($i=0;$i<$history_cnt;$i++){
        echo '<tr>';
            echo '<td>'.$history[$i]['in_day'].'</td>';
            echo '<td>'.$history[$i]['in_hour'].':'. $history[$i]['in_minute'].'</td>';
            echo '<td>'.$history[$i]['out_hour'].':'.$history[$i]['out_minute'].'</td>';
            $in_hour= intval($history[$i]['in_hour']);
            $out_hour= intval($history[$i]['out_hour']);
            if( $history[$i]['in_day'] === $history[$i]['out_day']){
                $staying_time = $out_hour - $in_hour + 1;
            }else{
                $staying_time = (24 - $in_hour) + $out_hour +1 ;
            }
            echo '<td>'.$staying_time.'<selet id="sel1"></select>'.'</td>';
            echo '</tr>';
    }
    echo '</table>';
    return 1;
}

function GetLatestYear(){
    global $pdo;
    global $id;
    $stmt = $pdo->prepare("SELECT in_year, in_month  FROM history WHERE sid = ? ");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $all_history = $stmt->fetchAll();
    $array_size = count($all_history);
    $latest_year = $all_history[$array_size-1][0];
    return intval($latest_year);
}

function GetLatestMonth(){
    global $pdo;
    global $id;
    $stmt = $pdo->prepare("SELECT in_year, in_month  FROM history WHERE sid = ? ");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $all_history = $stmt->fetchAll();
    $array_size = count($all_history);
    $latest_month = $all_history[$array_size-1][1];
    return intval($latest_month);
}

function GetaStayingTime($month, $year){
    global $pdo;
    global $id;
    $stmt = $pdo->prepare("SELECT * FROM history WHERE sid = ? AND in_month = ? AND in_year = ? ORDER BY in_day ASC");
    $stmt->bindValue(1, $id);
    $stmt->bindValue(2, $month);
    $stmt->bindValue(3, $year);
    $stmt->execute();
    $history = $stmt->fetchAll();
    $history_cnt = count($history);

    if($history_cnt==0)return -1;

    $staying_history = array();
    $days = array();
    for($i=0;$i<7;$i++){
        $in_hour= intval($history[$i]['in_hour']);
        $out_hour= intval($history[$i]['out_hour']);
        if( $history[$i]['in_day'] === $history[$i]['out_day']){
            $staying_time = $out_hour - $in_hour + 1;
        }else{
            $staying_time = (24 - $in_hour) + $out_hour +1 ;
        }
        $staying_history[] = strval($staying_time);
        $days[] = $history[$i]['in_day'];
    }

    $staying_history_csv = implode(",",$staying_history);
    $days_csv = implode(",",$days);

    return [$staying_history_csv, $days_csv];
}


if(isset($_POST['datapost'])){
    $_SESSION['date'] = $_POST['date'];
    $date = $_SESSION['date'];
    header('Location:edit.php');
  }
//$pdo = NULL; //DB接続解除
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Time Checker</title>
    <link rel="stylesheet" href="./../css/history_style.css">
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
                    $latest_year = GetLatestYear();
                    $latest_month = GetLatestMonth();
                    $graphdata_csv = GetaStayingTime($latest_month, $latest_year);
                    ?>

                <canvas id="myChart"></canvas>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
                    <script>
                    var　times_csv = "<?php echo $graphdata_csv[0] ?>";
                    var times = times_csv .split(",", -1)
                    times = times.map(function( value ){
                        return parseInt(value);
                    });

                    var　days_csv = "<?php echo $graphdata_csv[1] ?>";
                    var days = days_csv.split(",", -1);　

                    // グラフ化するデータ系列のサンプル
                    const sampleData = {
                        labels: days, // ラベル(X軸)
                        data: times, // データ系列
                    };

                    const loadCharts = function () {
                        const chartDataSet = {
                            type: 'line',
                            data: {
                                labels: sampleData.labels,
                                datasets: [{
                                    label: 'hour',
                                    data: sampleData.data,
                                    lineTension: 0, // draw straightline
                                }]
                            },
                            options: {
                                title: {
                                    display: true,
                                    text: '滞在時間の推移',
                                    fontSize: 16,
                                }
                            }
                        };
                            var ctx = document.getElementById("myChart");
                            new Chart(ctx, chartDataSet);
                        };

                        loadCharts();
                    </script>
                    <h3>履歴一覧</h3>
                    <?php
                    do{
                        $exist = ShowMonthHistory($latest_month,$latest_year);
                        $latest_month-=1;
                        if($latest_month==0){
                            $latest_year-=1;
                            $latest_month=12;
                        }
                    }while($exist!=-1);
                    $pdo = NULL;
                    ?>
            </div>
        </div>

    <button><a href="../index.php">GO HOME</a></button>
</body>

</html>