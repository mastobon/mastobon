<?php

//データベースへの接続
  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード';
  $pdo = new PDO($dsn,$user,$password);


//テーブル作成
  $sql = "CREATE TABLE mission4"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"  //投稿番号
  . "name char(32),"                      //名前
  . "comment TEXT,"                       //コメント
  . "time DATETIME,"                      //投稿日時
  . "password char(32)"                   //パスワード
  .");";
  $stmt = $pdo->query($sql);


//新規投稿
   if(!empty($_POST["comment"]) and !empty($_POST["name"]) and !empty($_POST["password"]) and empty($_POST["editno"])){ //名前・コメント・パスワードが入力されeditnoが空だったら
      //データの入力
       $sql = $pdo -> prepare("INSERT INTO mission4 (id,name,comment,time,password) VALUES (id,:name,:comment,:time,:password)");
       $sql -> bindParam(':name', $name, PDO::PARAM_STR);
       $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
       $sql -> bindParam(':time', $time, PDO::PARAM_STR);
       $sql -> bindParam(':password', $password, PDO::PARAM_STR);
       $name = $_POST["name"];
       $comment = $_POST["comment"];
       $time = date("Y/m/d H:i");
       $password = $_POST["password"];
       $sql -> execute();
      
   }



//削除機能
  if(!empty($_POST["deleteno"]) and  !empty($_POST["delete_password"])){                //削除番号とパスワードが入力されら
     $id = $_POST["deleteno"];
     $delete_pass = $_POST["delete_password"];
     $sql = "SELECT * FROM mission4 where id =$id";
     $results = $pdo -> query($sql);
        foreach($results as $row){ 
          $password = $row['password'];
             if($delete_pass == $password){               //パスワードが正しかったら
                $sql = "delete from mission4 where id = $id";   //削除実行
                $result = $pdo->query($sql);
             }else{
                echo "パスワードが違います。";
              }
         }
   }



//編集機能
  if(!empty($_POST["edit"])){                            //編集番号が入力されたら 
     $edit = $_POST["edit"];
     $id = $_POST["edit"];
     $sql = "SELECT * FROM mission4 where id =$id";
     $results = $pdo -> query($sql);
        foreach($results as $row){ 
           $password = $row['password'];
           if($password == $_POST["edit_password"]){    //パスワードが一致したら
              $editname = $row['name'];
              $editcomment = $row['comment'];
           }else{
             echo "パスワードが違います。";
            }  
        }
  }

//編集モード
  if(!empty($_POST["editno"])){        //フラグ立て  hiddenに編集対象番号が入っていたら
     $id = $_POST["editno"];
     $new_name = $_POST["name"];
     $new_comment = $_POST["comment"];
     $new_time = date("Y/m/d H:i");  
     $sql = "update mission4 set name = '$new_name' , comment = '$new_comment' , time = '$new_time' where id = $id";
     $result = $pdo->query($sql);
  }


?>



<html>
  <head>
    <meta http-equiv = "Content-Type" content = "text/html; charset = UTF-8">
  </head>
  <body>

    <form action = "mission_4.php" method = "post">
    <input type = "text" name = "name" placeholder = "名前" value = "<?php echo $editname; ?>">
    <input type = "text" name = "comment" placeholder = "コメント" value = "<?php echo $editcomment; ?>">
    <input type = "text" name = "password" placeholder = "パスワード">
    <input type = "hidden" name = "editno" value = "<?php echo $edit; ?>">
    <input type = "submit" value = "送信"><br/>
    <input type = "text" name = "deleteno" placeholder = "削除対象番号">
    <input type = "text" name = "delete_password" placeholder = "パスワード">
    <input type = "submit" value = "削除"><br/>
    <input type = "text" name = "edit" placeholder = "編集対象番号">
    <input type = "text" name = "edit_password" placeholder = "パスワード">
    <input type = "submit" value = "編集" ><br/>
    </form>

  </body>
</html>

<?php
//データベースへの接続
  $dsn = 'データベース名';
  $user = 'ユーザー名';
  $password = 'パスワード';
  $pdo = new PDO($dsn,$user,$password);

//入力したデータの表示
  $sql = 'SELECT * FROM mission4 ORDER BY id';    //ORDER BY id  はidを昇順に並べる。
  $results = $pdo -> query($sql);
  foreach($results as $row){
   //$rowの中にはテーブルのカラム名が入る
     echo $row['id'] . ' ';
     echo $row['name'] . ' ';
     echo $row['comment'] . ' ';
     echo $row['time'] . ' ' . '<br>';
  }

?>