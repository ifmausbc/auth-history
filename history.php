<?php



header('Content-Type:text/html; charset=UTF-8');
// セッションの宣言
session_start();

require('../login.php');

if (isset($_GET["SearchID"]) && $_GET["SearchID"] != "") {



    $id = $_GET["SearchID"];

}



// 単一削除処理

if(isset($id)){

$sql_in="DELETE FROM histories where (SearchID = :id)";

try{

    $stmh=$pdo->prepare($sql_in);

    $stmh->bindvalue(":id","$id",PDO::PARAM_STR);

    $stmh->execute();

    print "[検索管理番号:{$id}]の履歴を削除しました<br><br>";

} catch(PDOException $Exception){

    die("エラー:".$Exception->getMessage());

}



}



// 複数選択削除処理

if(isset($_POST['sID'])) {

$sID = $_POST["sID"];

$sIDNum = count($sID);

$sql_fuku = "DELETE FROM histories 

WHERE SearchID IN (:ids)";

try {

    for($i = 0; $i < $sIDNum; $i++) {

    $stmh = $pdo->prepare($sql_fuku);

    $stmh->bindvalue(":ids","$sID[$i]",PDO::PARAM_STR);

    $stmh->execute();

    }

    print "選択された履歴を全て削除しました．<br /><br />";

} catch(PDOException $Exception){

    die("エラー:".$Exception->getMessage());

 }

}



?>



<!DOCTYPE html>

<html lang="ja">



<head>

    <meta charset="utf-8">

    <link href="./style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>運賃計算システム｜検索履歴</title>



</head>



<body>



    <h1>運賃計算システム｜検索履歴</h1>

    <hr>

    <?php



    
    // ユーザがログインしている場合，セッションを使ってuserIDを受け取る
    if (isset($_SESSION['userID'])) {

        $userID = $_SESSION['userID'];

        $sql_myhis = "SELECT * FROM histories WHERE UserID = :userID";

        try {

            $stmh = $pdo->prepare($sql_myhis);

            $stmh->bindvalue(":userID", "$userID", PDO::PARAM_STR);

            $stmh->execute();

        } catch (PDOException $Exception) {

            die("エラー:" . $Exception->getMessage());

        }

    } else {



        echo "<p style=\"text-align: right\">";

        echo "現在，<a href=\"./system/auth.html\">ログイン</a>していません．";

        echo "<br />";

        echo "検索履歴を閲覧するには，<a href=\"./auth.html\">ログイン</a>する必要があります．";

        echo "</p>";

        exit;



    }



    ?>



    <div class="vertical-middle">

    <form action="./history.php" method="POST">

    <table class="table table-bordered">

        <thead>

            <tr bgcolor="#00CCCC" style="text-align:center">

                <th>選択</th>

                <th>検索管理番号</th>

                <th>乗車駅</th>

                <th>降車駅</th>

                <th>学割</th>

                <th>往復割</th>

                <th>検索日時</th>

                <th>再検索</th>

                <th>履歴削除</th>

            </tr>

        </thead>

        <tbody>

        

            <?php

$result=$stmh->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row){

        print "<tr><td style=\"text-align:center\">";

        echo "<input class=\"form-check-input\" type=\"checkbox\" name=\"sID[]\" value=\"";

        echo $row["SearchID"];

        echo "\">";

        print "</td>";

        print "<td style=\"text-align:right\">"; 

        print htmlspecialchars($row["SearchID"],ENT_QUOTES);

        print "</td><td>";

        print htmlspecialchars($row["boarding"],ENT_QUOTES);

        print "</td><td>";

        print htmlspecialchars($row["getoff"],ENT_QUOTES);

        print "</td><td style=\"text-align:center\">";

        if($row["gakuwari"] == 1) {

            echo "〇";

        } else {

            echo "×";

        }

        print "</td><td style=\"text-align:center\">";

        if($row["fukuwari"] == 1) {

            echo "〇";

        } else {

            echo "×";

        }

        print "</td><td>";

        print htmlspecialchars($row["search_time"],ENT_QUOTES);

        print "</td><td>";

        print "<a href=\"../calc.php?gakuwari=";

        echo $row["gakuwari"];

        print "&fukuwari=";

        echo $row["fukuwari"];

        print "&boarding=";

        echo $row["boarding"];

        print "&getoff=";

        echo $row["getoff"];

        print "\">再検索</a>";

        print "</td><td>";

        print "<a href='./history.php?SearchID=".$row["SearchID"]."' onclick=\"return confirm('検索管理番号:".$row["SearchID"]."のデータを削除してもよろしいですか?')\">削除する</a>";

        print "</td><tr>\n";



}



            ?>

        

        </tbody>

    </table>

    <p> </br> </p>

    <p>選択した項目を <b>全て</b>

    <input class="btn btn-danger" type="submit" value="削除する"

    onclick="return confirm('選択した項目を全て削除します．本当に実行してよろしいですか？')">

    </p>

</form>

</div>

<p> <a href="../my.php">マイページに戻る</a> </p>

<p> <a href="../index.php">サイトホームに戻る</a> </p>

</body>



</html>
