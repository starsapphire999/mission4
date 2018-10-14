<!DOCTYPE html>
<head>
<meta http-equiv = "Content-type" content = "text/html" charset = "utf-8">
</head>
<body>

<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

/*"$_POST送信（パスワード以外）─────────────────────────*/
$comment =$_POST['comment'];
$name =$_POST['name'];
$hidden =$_POST['hidden'];
$delete =$_POST['delete'];
$edit =$_POST['edit'];

//"パスワードPOST送信─────────────────────────
$addpass =$_POST['addpass'];
$deletepass =$_POST['deletepass'];
$editpass2 =$_POST['editpass2'];

//"編集時の表示─────────────────────────
$editnum ="";
$editname ="";
$editcomment ="";
$editpass ="";




//"編集━━━━━━━━━━━━━━━━━━━━━━━━━
if(!empty($edit) && !empty($editpass2)){
	$sql='SELECT * FROM m4table'; //file()と似ている
	$result=$pdo->query($sql);

	foreach($result as $row){ //各行繰り返しさせる
	 if($row['id']==$edit){ //入力番号と一致
	   if($row['pass']==$editpass2){
	     $editnum=$row['id'];
	     $editname=$row['name'];
	     $editcomment=$row['comment'];
	     $editpass=$row['pass'];
	   }else{ 
	     echo "パスワードが違います"; //番号一致、パスワード違い
	     }
	 }else{"投稿番号がありません";}
	}
}
 if($hidden!=null){
	//update　表名　列名＝値　where 更新する行を特定する条件
	$sql='update m4table set name=:name, comment=:comment, pass=:pass where id=:id';
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':id',$hidden,PDO::PARAM_INT);
	$stmt->bindParam(':name',$name,PDO::PARAM_STR);
	$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
	$stmt->bindParam(':pass',$addpass,PDO::PARAM_STR);
	$stmt->execute();
 }
//"新規投稿━━━━━━━━━━━━━━━━━━━━━━━━━
 else{
 if(!empty($comment) && !empty($name) && !empty($addpass)){
	$addsql=$pdo->prepare("INSERT INTO m4table(name,comment,pass) VALUES(:name,:comment,:pass)");
	//idはデータベースの方で連番設定（別のPHPファイル？テキスト？で作成する）
	$addsql->bindParam(':name',$name,PDO::PARAM_STR);
	$addsql->bindParam(':comment',$comment,PDO::PARAM_STR);
	$addsql->bindParam(':pass',$addpass,PDO::PARAM_STR);
	$addsql->execute();
 }
}

//"削除━━━━━━━━━━━━━━━━━━━━━━━━━
if($delete!=null&&$deletepass!=null){
 $sql='SELECT * FROM m4table'; //file()のような
 $result=$pdo->query($sql);

 foreach($result as $row){
	if($row['id']==$delete){ //番号一致
	  if($row['pass']==$deletepass){
	    // DELETE文を変数に格納
         $sql = "DELETE FROM m4table WHERE id = :id AND pass = :pass";
         $stmt = $pdo->prepare($sql);
         $stmt -> bindParam(':id', $delete, PDO::PARAM_INT);
         $stmt -> bindParam(':pass',$deletepass,PDO::PARAM_STR);
         // 削除するレコードのIDが入った変数をexecuteにセットしてSQLを実行
         $flag = $stmt->execute(); //実行しつつ、それが成功したか（true,falseがflagに入る）
	  }else{
	     echo "パスワードが違います";  //パスワード不一致
	   }
	  }else{}
	}
 }
?>

<center>

<form method="post" action="mission_4.php">
投稿フォーム<br>
<input type="text" name="name" placeholder="お名前" value="<?php echo $editname; ?>" ><br>
<input type="text" name="comment" placeholder="コメント" value="<?php echo $editcomment; ?>" ><br>
<input type="hidden" name="hidden" value="<?php echo $editnum; ?>" >
<input type="text" name="addpass" placeholder="パスワード" value="<?php echo $editpass; ?>" ><br>
<input type = "submit" value ="送信" name="add1">
</form>




<form method="post" action="mission_4.php">
削除フォーム<br>
<input type="text" name="delete" placeholder="投稿番号" value=""><br>
<input type="text" name="deletepass" placeholder="パスワード" value=""><br>
<input type = "submit" value ="送信" name="delete1">
</form>


<form method="post" action="mission_4.php">
編集フォーム<br>
<input type="text" name="edit" placeholder="投稿番号" value=""><br>
<input type="text" name="editpass2" placeholder="パスワード" value=""><br>
<input type = "submit" value ="送信" name="edit1">
</form>

<hr>

投稿一覧<br>

<?php
//表示
$showsql='SELECT * FROM m4table order by id';  //idで並べ替え、降順や昇順指定可能
$result=$pdo->query($showsql);
foreach($result as $row){
//$row=テーブルのカラム名
echo $row['id'].',';
echo $row['name'].',';
echo $row['comment'].',';
echo $row['date'].',';
echo '<br>';
}
?>
</center>
</body>
</html>
