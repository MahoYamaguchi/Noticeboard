<?php
    // DB接続設定
    $dsn='データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
     // データベース内にテーブル作成
     $sql = "CREATE TABLE IF NOT EXISTS m51"
    ." ("
    // 連続した数値を自動でカラムに格納する
    // インデックスを自動的に作成する PRIMARY KEY や UNIQUを設定
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment text,"
    . "pass text,"
    . "date DATETIME"
    .");";
    $stmt = $pdo->query($sql);
    
    // テーブルの表示
    // $sql ='SHOW TABLES';
    // $result = $pdo -> query($sql);
    // foreach ($result as $row){
    //     echo $row[0];
    //     echo '<br>';
    // }
    // echo "<hr>";
    
    // テーブルの構成詳細の表示
    // $sql ='SHOW CREATE TABLE m51';
    // $result = $pdo -> query($sql);
    // foreach ($result as $row){
    //     echo $row[1];
    // }
    // echo "<hr>";
    
    // データ入力　新規投稿
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) && empty($_POST["number_edit_check"])) {
        $sql = $pdo -> prepare("INSERT INTO m51 (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
        $sql -> bindParam(":name", $name, PDO::PARAM_STR);
        $sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
        $sql -> bindParam(":pass", $pass, PDO::PARAM_STR);
        $sql -> bindParam(":date", $date, PDO::PARAM_STR);
        $name =$_POST["name"];
        $comment =$_POST["comment"] ; 
        $pass=$_POST["pass"];
        $date=date("Y/m/d/ H:i:s");
        $sql -> execute();
    
    // データレコードの削除
    }else if(!empty($_POST["number_delete"])){
        // パスワードの指定
        $id = $_POST["number_delete"];
        $sql = 'SELECT * FROM m51 WHERE id=:id'; //:必要！！
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        }
        $password_delete= $row[3];
        // echo $password_delete. "<br>";
         
         // パスワードが一致する時
        if ($_POST["password_delete"] ==  $password_delete){
            $id = $_POST["number_delete"];
            $sql = 'delete from m51 WHERE id =:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }else{
            echo "パスワードが一致していません！<br>";
        } 
        
 
    // 編集番号を表示
    // if ($_POST["password_edit"]==$password_edit){
    }else if (!empty($_POST["number_edit"])){
        // パスワードを指定
        $id=$_POST["number_edit"];
        $sql = 'SELECT * FROM m51 WHERE id=:id'; //MySQLで代入を表す「:=」
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        $stmt->execute(); 
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        }
        $password_edit=$row[3];
        // echo $password_edit;
        // echo $row[3];
        
        // パスワードが一致する時
        if ($_POST["password_edit"]==$password_edit){
            $id=$_POST["number_edit"];
            $sql = "SELECT * FROM m51 WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();       
            // fetchAllメソッドで配列として全てのデータを取得する
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //入力フォームと一致するように
                $number_edit=$row['id'];
                $name_edit=$row['name'];
                $comment_edit=$row['comment'];
                $password_edit=$row["pass"];
             }
             
        }else{
            echo "パスワードが一致していません！<br>";
        }
    
    }
    
     // 編集実行機能
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) && !empty($_POST["number_edit_check"])) {
        $id = $_POST["number_edit_check"];
        $name= $_POST["name"];
        $comment = $_POST["comment"];
        $pass = $_POST["pass"];
        $date = date("Y/m/d H:i:s");
        $sql = 'UPDATE m51 SET name=:name, comment=:comment, pass=:pass, date=:date WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
        

    
    

?>


<!Doctype html>
<html lang="ja">
    <head>
        <meta charaset="UTF-8">
        <title>5-1</title>
    </head>
    <body>
        <!--新規投稿フォーム-->
        <form action="" method="POST">
            <input type="text" name="name" placeholder="名前" 
             value=<?php if (!empty($name_edit)) {echo $name_edit;} ?>>
            <input type="text" name="comment" placeholder="コメント" 
             value=<?php if (!empty($comment_edit)) {echo $comment_edit;} ?>>
            <input type="password" name="pass" placeholder="パスワード" 
             value=<?php if (!empty($password_edit)) {echo $password_edit;} ?>>
            <input type="hidden" name="number_edit_check" 
             value=<?php if (!empty($number_edit)) {echo $number_edit;}?>>
            <input type="submit" name="submit" value="送信">
        </form>
        <br>
        <!--削除フォーム-->
        <form action="" method="POST">
            <input type="number" name="number_delete" placeholder="削除対象番号">
            <input type="password" name="password_delete" placeholder="パスワード">
            <input type="submit" name="submit" value="削除">
        </form>
        <br>
        <!--編集フォーム-->
        <form action="" method="POST">
            <input type="number" name="number_edit" placeholder="編集対象番号">
            <input type="password" name="password_edit" placeholder="パスワード">
            <input type="submit" name="submit" value="編集">
        </form>
        
<?php
        // 表示
        $sql = 'SELECT * FROM m51';
        $stmt = $pdo->query($sql);
        // fetchAllメソッドで配列として全てのデータを取得する
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo "<br>";
            echo $row["id"].',';
            echo $row["name"].',';
            echo $row["comment"].",";
            echo $row["date"]."<br>";
            echo "<hr>";
            }
?>