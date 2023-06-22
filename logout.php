<?php
session_start();
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <link href="./style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ログアウト</title>
</head>
<body>
    <p>ログアウトしました．</p>
    <p><a href="../index.php">サイトホームに戻る</a></p>
    <p><a href="./auth.html">ログインページに戻る</a></p>
</body>
</html>
