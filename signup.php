<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>駅検索システム｜サインアップ</title>
</head>

<body>
<?php
header('Content-Type:text/html; charset=UTF-8');

require('../login.php');

 if (isset($_POST["username"]) && $_POST["username"] != "") {
        $username = $_POST["username"];
}
else {
        die("ユーザ名が未入力です。");
}

if (isset($_POST["login_id"]) && $_POST["login_id"] != "") {
        $login_id = $_POST["login_id"];
}
else {
        die("ログインIDが未入力です。");
}


if (isset($_POST["password"]) && $_POST["password"] != "") {
        $password = $_POST["password"];
        // パスワードハッシュ（暗号化）
        $ppassword = password_hash($password, PASSWORD_DEFAULT);
}
else {
        die("パスワードが未入力です。");
}



$sql_in="INSERT INTO accounts(username,login_id,ppassword) values (:username,:login_id,:ppassword)"; 
#idはauto_increment(自動番号付け)のため指定しなくてよい。
#favは初期値を指定しなければならない。今回の表示に関係しないがテーブルに存在している属性は指定しなければならない。


try{
        $stmh=$pdo->prepare($sql_in);
        $stmh->bindvalue(":username","$username",PDO::PARAM_STR);
        $stmh->bindvalue(":login_id","$login_id",PDO::PARAM_STR);
        $stmh->bindvalue(":ppassword","$ppassword",PDO::PARAM_STR);
        $stmh->execute();
        $count=$stmh->rowCount();
        print "データを{$count}件追加しました。<br><br>";
        echo "<a href=\"../my.php\">マイページへ</a> <br><br>";
        echo "<a href=\"../index.php\">サイトホームへ</a> <br><br>";
} catch(PDOException $Exception){
        die("エラー:".$Exception->getMessage());
}


// 一般の訪問者に全ユーザのID一覧を見せる訳にはいかないので，コメントアウト
/*
try{
        $sql = "SELECT userID, username, login_id, last_login
        FROM accounts";
        
        $stmh=$pdo->prepare($sql);
        $stmh->execute();

} catch(PDOException $Exception){
        die("DB検索エラー:".$Exception->getMessage());

}
*/
?>
<!--
<table border='1' cellpadding='2' cellspacing='0'>
<thead>
<tr bgcolor="#00CCCC"><th>userID</th><th>ユーザ名</th><th>ログインID</th><th>最終ログイン日時</th></tr>
</thead>
<tbody>
-->

<?php
/*
$result=$stmh->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row){
        print "<tr><td>"; 
        print htmlspecialchars($row["userID"],ENT_QUOTES);
        print "</td><td>";
        print htmlspecialchars($row["username"],ENT_QUOTES);
        print "</td><td>";
        print htmlspecialchars($row["login_id"],ENT_QUOTES);
        print "</td><td>";
        print htmlspecialchars($row["last_login"],ENT_QUOTES);
        print "</td><tr>\n";
        

}
*/


?>
</body>
</html>
