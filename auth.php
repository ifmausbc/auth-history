<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>駅検索システム｜ログイン</title>
</head>

<body>
<?php
// セッションの宣言
session_start();
header('Content-Type:text/html; charset=UTF-8');

require('../login.php');

if (isset($_POST["login_id"]) && $_POST["login_id"] != "") {
        $login_id = $_POST["login_id"];
}
else {
        die("ログインIDが未入力です。");
}

if (isset($_POST["password"]) && $_POST["password"] != "") {
        $ppassword = $_POST["password"];
}
else {
        die("パスワードが未入力です。");
}



$sql_in="SELECT * FROM accounts WHERE login_id = :login_id"; 
#idはauto_increment(自動番号付け)のため指定しなくてよい。
#favは初期値を指定しなければならない。今回の表示に関係しないがテーブルに存在している属性は指定しなければならない。


try{

        $stmh=$pdo->prepare($sql_in);

        
        $stmh->bindvalue(":login_id","$login_id",PDO::PARAM_STR);


        $stmh->execute();

        $result=$stmh->fetch(PDO::FETCH_ASSOC);

        

} catch(PDOException $Exception){
        die("エラー:".$Exception->getMessage());

}
// ログインフォームで入力されたパスワードをハッシュ値同士でDBに格納されているパスワードと比較
if(password_verify($ppassword, $result['ppassword'])) {
    // セッション変数にuserIDを引き渡して
    $_SESSION['userID'] = $result['userID']; //ログイン成功時
    // マイページに遷移
    header('Location: ../my.php');
} else {
    echo "ユーザ名またはパスワードが違います．"; //ログイン失敗時
    echo "<a href=\"./auth.html\">ログイン画面に戻る</a>";
}
?>


</body>
</html>
