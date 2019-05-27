<?php
session_start();
try{
    $pdo = new PDO('sqlite:./../db/sample01.db');
    $pdo->exec('CREATE TABLE IF NOT EXISTS member(id INTEGER PRIMARY KEY, name TEXT, status INT)');
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }catch(PDOException $e){
        exit('Connection failed:'.$e->getMessage());
    }

$id = $_SESSION['id'];
$id = intval($id);
$stmt = $pdo->prepare("SELECT * FROM member WHERE id = ?");
$stmt->bindValue(1, $id);
$stmt->execute();
$data = $stmt->fetch();
$pdo = NULL; //DB接続解除
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
                "<?php echo $data['name'] ?>さんの勤怠履歴"
                </h2>
            </div>
        </header>
    </div>

    <button><a href="../index.php">GO HOME</a></button>
</body>

</html>