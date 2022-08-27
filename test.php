<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>

<body>
    <?php
    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    if(isset($_POST["edit"])){
        $password3 =$_POST["password3"];
        $edit_num = $_POST["edit_num"];
        $sql = "SELECT * FROM M5";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        $edit_number = $_POST["edit_number"];
        foreach($results as $row){
            if($edit_number == $row['id']){
                //パスワードを取得
                $password = $row['password'];
                if($password == $password3){
                    for($h = 0; $h < count($row)-5; $h++){
                          $simEdit[$h] = $row[$h];
                    }
                }else{
                    echo "パスワードが間違っています";
                    echo "<br>";
                }
            }
        }
    }
    ?>

    <form method="post"action="">
        入力フォーム<br> 
        <input type="text" name="name" placeholder="名前"value="<?php if(!empty($simEdit[1])){print $simEdit[1];}?>"> 
         <input type="text" name="comment" placeholder="コメント"value="<?php if(!empty($simEdit[2])){print $simEdit[2];}?>"> 
         <input type="hidden" name="edit_num"value="<?php if(!empty($simEdit[0])){print $simEdit[0];}?>">
         <input type="text" name = "password1" placeholder="パスワード"value="<?php if(!empty($simEdit[4])){print $simEdit[4];}?>">
         <input type="submit" name="submit" value = "送信"><br>
         削除フォーム<br>
        <input type="number" name = "number" placeholder="削除対象番号">
        <input type="text" name = "password2" placeholder="パスワード">
        <input type="submit" name="delete" value = "削除"><br>
        編集フォーム<br>
        <input type="number" name = "edit_number" placeholder="編集対象番号">
        <input type="text" name = "password3" placeholder="パスワード">
        <input type="submit" name="edit" value = "編集">
    </form>

<?php
    if(isset($_POST["delete"])){
        $password2 = $_POST['password2'];
        $sql = "SELECT id,password FROM M5";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        $number = $_POST['number'];
        foreach($results as $row){
            if($number == $row['id']){
                $password = $row['password'];
            }
        }
        
       if($password == $password2){
        $sql = $pdo -> prepare ("DELETE FROM M5 WHERE id = :id");
        $sql -> bindParam(':id', $id);
        $id = $_POST['number'];
        $sql -> execute();
       }else{
           echo "パスワードが間違っています";
           echo "<br>";
       }
        //表示機能
            $sql = 'SELECT * FROM M5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'];
                echo $row['name'];
                echo $row['comment'];
                echo $row['date'];
            echo "<hr>";
            }       
        
    }else{
        if(!empty($_POST["name"])) {
            //編集モードの時
            if(!empty($_POST["edit_num"])){
                
                $sql = 'UPDATE M5 SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $id = $_POST["edit_num"]; //変更する投稿番
                $name = $_POST['name'];
                $comment = $_POST['comment']; //好きな名前、好きな言葉は自分で決めること
                $date = new DateTime();
                $date = $date->format('Y-m-d H:i:s');
                $password = $_POST['password1'];
                $stmt->execute();
                
                //表示機能
                $sql = 'SELECT * FROM M5';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                echo $row['id'];
                echo $row['name'];
                echo $row['comment'];
                echo $row['date'];
                echo "<hr>";
                }
                
            }
            //新規モードの時
            else{
                $sql = $pdo -> prepare("INSERT INTO M5 (name, comment,date,password) VALUES (:name, :comment,:date,:password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                
                $name = $_POST['name'];
                $comment = $_POST['comment']; //好きな名前、好きな言葉は自分で決めること
                $date = new DateTime();
                $date = $date->format('Y-m-d H:i:s');
                $password = $_POST['password1'];
                $sql -> execute();
                //表示機能
                $sql = 'SELECT * FROM M5';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                echo $row['id'];
                echo $row['name'];
                echo $row['comment'];
                echo $row['date'];
                echo "<hr>";
                }
                
            }
        }
        }
    ?>
</body>
</html>