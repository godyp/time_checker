<?php
session_start();
try {
    $pdo = new PDO('sqlite:../../../db/data.db');
} catch (PDOException $e) {
    exit('Connection failed:' . $e->getMessage());
}

$id = $_SESSION['id'];
$id = intval($id);
$stmt = $pdo->prepare("SELECT name FROM members WHERE sid = ?");
$stmt->bindValue(1, $id);
$stmt->execute();
$name = $stmt->fetch();

//timestamp infomation
$timestamp = $_SESSION['timestamp'];
$timestamp = str_replace('_', ' ', $timestamp);

//$timestamp = sprintf('%04s-02%s-02%s%',$year,$month,$day);
if (isset($_POST['in_datapost'])) {
    //更新時間情報
    list($new_in_hour, $new_in_min) = explode(':', $_POST['intime']);
    $new_in_hour = ltrim($new_in_hour, '0');
    $new_in_min = ltrim($new_in_min, '0');

    //自動記録時間
    list($pre_in_hour, $pre_in_min) = explode(':', $_POST['pre_intime']);
    $pre_in_hour = ltrim($pre_in_hour, '0');
    $pre_in_min = ltrim($pre_in_min, '0');

    $stmt = $pdo->prepare("UPDATE history SET in_hour = ?, in_minute = ? WHERE sid = ? AND timestamp = ?");
    $stmt->bindValue(1, $new_in_hour);
    $stmt->bindValue(2, $new_in_min);
    $stmt->bindValue(3, $id);
    $stmt->bindValue(4, $timestamp);
    // スプレッドシートに送信
    $sql = "UPDATE history SET in_hour = '" . $new_in_hour . "', in_minute = '" . $new_in_min . "' WHERE sid = " . strval($id) . " AND timestamp = '" . $timestamp . "'";
    $command = "python ../../reader/send.py \"" . $sql . "\"";
    exec($command, $output);
} elseif (isset($_POST['out_datapost'])) {
    //更新時間情報
    list($new_out_hour, $new_out_min) = explode(':', $_POST['outtime']);
    $new_out_hour = ltrim($new_out_hour, '0');
    $new_out_min = ltrim($new_out_min, '0');

    //自動記録時間
    list($pre_out_hour, $pre_out_min) = explode(':', $_POST['pre_outtime']);
    $pre_out_hour = ltrim($pre_out_hour, '0');
    $pre_out_min = ltrim($pre_out_min, '0');
    $stmt = $pdo->prepare("UPDATE history SET out_hour = ?, out_minute = ? WHERE sid = ? AND timestamp = ?");
    $stmt->bindValue(1, $new_out_hour);
    $stmt->bindValue(2, $new_out_min);
    $stmt->bindValue(3, $id);
    $stmt->bindValue(4, $timestamp);
    // スプレッドシートに送信
    $sql = "UPDATE history SET out_hour = '" . $new_out_hour . "', out_minute = '" . $new_out_min . "' WHERE sid = " . strval($id) . " AND timestamp = '" . $timestamp . "'";
    $command = "python ../../reader/send.py \"" . $sql . "\"";
    exec($command, $output);
}
$stmt->execute();
$pdo = NULL;

if ($stmt == TRUE) {
    header('Location:update.php');
} else {
    header('Location:error.php');
}
