<?php
    error_reporting (E_ALL & ~E_NOTICE);

    $number=0;
    $name=$_POST ["name"];
    $comment=$_POST ["comment"];
    $date=date("Y/m/d H:i:s");
    $pass=$_POST ["pass"];
        
    $filename="mission_3-5.txt";
        
    if (file_exists ($filename)) {
        $lines=file ($filename);
        $lastline=$lines [count($lines)-1];
        $string=explode ("<>",$lastline);
        $lastnumber=intval ($string[0]);
        $number=$lastnumber+1;
    } else {
        $number=1;
    }
        
    $data=$number."<>".$name."<>".$comment."<>".$date."<>".$pass."<>";
        
    if ((!empty ($name && $comment && $pass)) && (empty ($_POST ["hiddenedit"]))) {
        $filename="mission_3-5.txt";
        $fp=fopen ($filename,"a");
        fwrite ($fp, $data.PHP_EOL);
        fclose ($fp); 
    }
        
        
    //削除機能    
    if (!empty ($_POST ["delete"]) && ($_POST ["delpass"])) {
        $delete = $_POST["delete"];
        $delpass = $_POST ["delpass"];

        $delCon = file($filename);
        $fp = fopen($filename,"w");
        foreach ($delCon as $line) {
            $deldata = explode("<>",$line);
            if (($delete != $deldata[0]) && ($delpass != $deldata[4])) {
                fwrite ($fp, $line);
            }  
        }
        fclose($fp);
    }
    
        
    //編集機能
    if (!empty ($_POST ["edit"]) && ($_POST ["editpass"])) {
        $edit = $_POST ["edit"];
        $editpass = $_POST ["editpass"];
            
        $editCon = file ($filename);
        foreach ($editCon as $line) {
            $editdata = explode ("<>",$line);
            if (($edit == $editdata[0]) && ($editpass == $editdata[4])) {
                $editnumber = $editdata[0];
                $editname = $editdata[1];
                $editcomment = $editdata[2];
            }
        }
    }
    
    if (isset ($_POST["name"])&&($_POST["comment"])&&($_POST["hiddenedit"])){
        $hiddenedit = $_POST ["hiddenedit"];
        $editCon = file ($filename);
        $fp = fopen ($filename,"w");
        foreach ($editCon as $line) {
            $editdata = explode ("<>",$line);
            if ($hiddenedit == $editdata[0]) {
                fwrite ($fp, $editdata[0]."<>".$name."<>".$comment."<>".$date."<>".$pass."<>".PHP_EOL);
            } else {
                fwrite ($fp, $line);
            }
        }
    fclose ($fp);    
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
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
<br>
<?php
    $lines=file ($filename, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        $newdata=explode ("<>",$line);
        echo $newdata [0];
        echo $newdata [1];
        echo $newdata [2];
        echo $newdata [3];
        echo "<br>";
    }    
?>
</body>
</html>