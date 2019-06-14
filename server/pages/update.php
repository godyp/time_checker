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

    if( !(isset($_POST[in_datapost]) || isset($_POST[out_datapost])) ){
        header('Location:error.php');
    }

    $id = $_SESSION['id'];
    $id = intval($id);
    $stmt = $pdo->prepare("SELECT name FROM members WHERE sid = ?");
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $name = $stmt->fetch();

    //年月日情報
    $set_date = $_SESSION['date'];
    list($year,$month,$day) = explode('-', $set_date );
    $year = ltrim($year, '0');
    $month = ltrim($month, '0');
    $day = ltrim($day, '0');
     
    if(isset($_POST[in_datapost])){
         //更新時間情報
        list($in_hour,$in_min) = explode(':', $_POST[intime]);
        $in_hour = ltrim($in_hour, '0');
        $in_min = ltrim($in_min, '0');

        //自動記録時間
        list($pre_in_hour,$pre_in_min) = explode(':', $_POST[pre_intime]);
        $pre_in_hour = ltrim($pre_in_hour, '0');
        $pre_in_min = ltrim($pre_in_min, '0');

        $stmt = $pdo->prepare("UPDATE history SET in_hour = ?, in_minute = ? WHERE sid = ? AND in_year = ? AND in_month = ? AND in_day = ? AND in_hour = ? AND in_minute = ?");
        //$stmt = $pdo->prepare("UPDATE history SET in_hour = ?, in_minute = ? WHERE sid = ? ");
        $stmt->bindValue(1, $in_hour);
        $stmt->bindValue(2, $in_min);
        $stmt->bindValue(3, $id);
        $stmt->bindValue(4, $year);
        $stmt->bindValue(5, $month);
        $stmt->bindValue(6, $day);
        $stmt->bindValue(7, $pre_in_hour);
        $stmt->bindValue(8, $pre_in_min);
    }elseif(isset($_POST[out_datapost])){
        //更新時間情報
        list($out_hour,$out_min) = explode(':', $_POST[outtime]);
        $out_hour = ltrim($out_hour, '0');
        $out_min = ltrim($out_min, '0');

        //自動記録時間
        list($pre_out_hour,$pre_out_min) = explode(':', $_POST[pre_outtime]);
        $pre_out_hour = ltrim($pre_out_hour, '0');
        $pre_out_min = ltrim($pre_out_min, '0');
        $stmt = $pdo->prepare("UPDATE history SET out_hour = ?, out_minute = ? WHERE sid = ? AND in_year = ? AND in_month = ? AND in_day = ? AND out_hour = ? AND out_minute = ?");
         
        //$stmt = $pdo->prepare("UPDATE history SET in_hour = ?, in_minute = ? WHERE sid = ? ");
        $stmt->bindValue(1, $out_hour);
        $stmt->bindValue(2, $out_min);
        $stmt->bindValue(3, $id);
        $stmt->bindValue(4, $year);
        $stmt->bindValue(5, $month);
        $stmt->bindValue(6, $day);
        $stmt->bindValue(7, $pre_out_hour);
        $stmt->bindValue(8, $pre_out_min);
    }
   
    $stmt->execute();
    $pdo = NULL;

    if($stmt==TRUE){
        header('Location:edit.php');
    }else{
        header('Location:error.php');
    }
?>
