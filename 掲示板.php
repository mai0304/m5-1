<?php
    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    // テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS bulletinboard"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,"
    . "password char(32)"
    .");";
    $stmt = $pdo->query($sql);
    
    
    error_reporting (E_ALL & ~E_NOTICE);
    
    // データ入力
    $name=$_POST ["name"];
    $comment=$_POST ["comment"];
    $date=date("Y/m/d H:i:s");
    $pass=$_POST ["pass"];
    
    if (!empty ($_POST ["name"]) && !empty ($_POST ["comment"]) && !empty ($_POST ["pass"]) && empty ($_POST ["hiddenedit"])) {
        $sql = $pdo -> prepare("INSERT INTO bulletinboard (name, comment, date, password) VALUES (:name, :comment, :date, :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $sql -> execute();
    }
    
    
    // データレコード削除
    $delid = $_POST ["delete"];
    $delpass = $_POST ["delpass"];
    
    $sql = 'SELECT * FROM bulletinboard';
    $stmt = $pdo->query($sql);
    $loops = $stmt->fetchAll();
    foreach ($loops as $loop) {
        if (($delid==$loop['id'])&&($delpass==$loop['password'])) {
            $sql = 'delete from bulletinboard where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $delid, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    
    
    // データレコード編集
    $editid = $_POST ["edit"];
    $editpass = $_POST ["editpass"];
    $hiddenedit = $_POST ["hiddenedit"];
    
    $sql = 'SELECT * FROM bulletinboard';
    $stmt = $pdo->query($sql);
    $lines = $stmt->fetchAll();
    foreach ($lines as $line) {
        if (($editid==$line['id'])&&($editpass==$line['password'])) {
            $editnumber = $line['id'];
            $editname = $line['name'];
            $editcomment = $line['comment'];
        }
    }
    
    if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) && !empty($_POST["hiddenedit"])) {
        $sql = 'UPDATE bulletinboard SET name=:name,comment=:comment,password=:pass WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':id', $hiddenedit, PDO::PARAM_INT);
        $stmt->execute();
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <h1>好きな動物は何ですか？</h1>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php echo $editname; ?>">
        <input type="text" name="comment" placeholder="コメント" value="<?php echo $editcomment; ?>">
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit"><br>
        <br>
        <input type="number" name="delete" placeholder="削除対象番号">
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除"><br>
        <br>
        <input type="number" name="edit" placeholder="編集対象番号">
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" name="submit" value="編集"><br>
        <br>
        <input type="hidden" name="hiddenedit" value="<?php echo $editnumber; ?>">
    </form>
</body>
</html>
    
<?php
// データレコード表示
    $sql = 'SELECT * FROM bulletinboard';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
    echo "<hr>";
    }
?>
