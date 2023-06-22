<?php
header('Content-Type:text/html; charset=UTF-8');
require('login.php');
session_start();
// 現在時刻を取得し，nowに格納
$now = date('Y-m-d H:i:s');

$kyushuexfare = 0;
$addfare = 0;

$boarding = $_GET["boarding"];

$getoff = $_GET["getoff"];

$sql1 = "SELECT tokyo_dist, Kyushu_dist, company_name
    FROM Stations, RailwayCompany, Line, CompanyLine
    WHERE Stations.LineID = Line.LineID AND
    RailwayCompany.CompanyID = CompanyLine.CompanyID AND
    Line.LineID = CompanyLine.LineID
    AND station_name = '" . $boarding . "'";

$sql2 = "SELECT tokyo_dist, Kyushu_dist, company_name
FROM Stations, RailwayCompany, Line, CompanyLine
WHERE Stations.LineID = Line.LineID AND
RailwayCompany.CompanyID = CompanyLine.CompanyID AND
Line.LineID = CompanyLine.LineID
AND station_name = '" . $getoff . "'";

$sql3 = "SELECT tokyo_dist FROM Stations WHERE station_name = '博多' ";


try {
    $cmd1 = $pdo->prepare($sql1);
    $cmd1->execute();
    foreach ($cmd1->fetchAll() as $row) {
        $dist1 = $row['tokyo_dist'];
        $kandist1 = $row['tokyo_dist'];
        $railwaycompanyb = $row['company_name'];
        $kyushub = $row['Kyushu_dist'];
    }
} catch (PDOException $Exception) {
    die("DB search error!:" . $Exception->getMessage());
}

try {
    $cmd2 = $pdo->prepare($sql2);
    $cmd2->execute();
    foreach ($cmd2->fetchAll() as $row) {
        $dist2 = $row['tokyo_dist'];
        $kandist2 = $row['tokyo_dist'];
        $railwaycompanyg = $row['company_name'];
        $kyushug = $row['Kyushu_dist'];
    }
} catch (PDOException $Exception) {
    die("DB search error!:" . $Exception->getMessage());
}

try {
    $cmd3 = $pdo->prepare($sql3);
    $cmd3->execute();
    foreach ($cmd3->fetchAll() as $row) {
        $hakatakan = $row['tokyo_dist'];
    }
} catch (PDOException $Exception) {
    die("DB search error!:" . $Exception->getMessage());
}

$dist = abs($dist1 - $dist2);
$dist = ceil($dist);
$kandist = abs($kandist1 - $kandist2);
$kandist = ceil($dist);
if ($dist >= 101) {
    $limit = ceil($dist / 200 + 1);
} else {
    $limit = 1;
}


$sql_calcfare = "SELECT fare
    FROM fare_ecw
    WHERE fare_ecw.mmin <= $dist AND fare_ecw.mmax >= $dist";
$sql_calcexfare = "SELECT fare
    FROM express
    WHERE express.mmin <= $kandist AND express.mmax >= $kandist";


try {
    $fare_calc = $pdo->prepare($sql_calcfare);
    $fare_calc->execute();
    foreach ($fare_calc->fetchAll() as $row) {
        $fare = $row['fare'];
    }
} catch (PDOException $Exception) {
    die("DB search error!:" . $Exception->getMessage());
}
try {
    $exfare_calc = $pdo->prepare($sql_calcexfare);
    $exfare_calc->execute();
    foreach ($exfare_calc->fetchAll() as $row) {
        $exfare = $row['fare'];
    }
} catch (PDOException $Exception) {
    die("DB search error!:" . $Exception->getMessage());
}

// 乗車駅がJR九州管内
if ($railwaycompanyb == "JR九州") {
    $kyushu_calcfare = "SELECT fare
        FROM kyushu_add
        WHERE kyushu_add.mmin <= $kyushub AND kyushu_add.mmax >= $kyushub";
    $kyuexb = $kandist1 - $hakatakan;
    $kyushu_calcexfare = "SELECT fare
        FROM kyushu_exp
        WHERE kyushu_exp.mmin <= $kyushub AND kyushu_exp.mmax >= $kyushub";
    try {
        $kyushuadd_calc = $pdo->prepare($kyushu_calcfare);
        $kyushuadd_calc->execute();
        foreach ($kyushuadd_calc->fetchAll() as $row) {
            $addfare = $row['fare'];
        }
    } catch (PDOException $Exception) {
        die("DB search error!:" . $Exception->getMessage());
    }
    try {
        $kyushuexp_calc = $pdo->prepare($kyushu_calcexfare);
        $kyushuexp_calc->execute();
        foreach ($kyushuexp_calc->fetchAll() as $row) {
            $kyushuexfare = $row['fare'];
        }
    } catch (PDOException $Exception) {
        die("DB search error!:" . $Exception->getMessage());
    }
    $honshu = $kandist - $kyuexb;
    $sql_calcexfare = "SELECT fare
        FROM express
        WHERE express.mmin <= $honshu AND express.mmax >= $honshu";
    try {
        $exfare_calc = $pdo->prepare($sql_calcexfare);
        $exfare_calc->execute();
        foreach ($exfare_calc->fetchAll() as $row) {
            $exfare = $row['fare'];
        }
    } catch (PDOException $Exception) {
        die("DB search error!:" . $Exception->getMessage());
    }
    $exfare = $exfare + $kyushuexfare - 530;
    $fare = $fare + $addfare;
}
// 降車駅がJR九州管内
if ($railwaycompanyg == "JR九州") {
    $kyushu_calcfare = "SELECT fare
        FROM kyushu_add
        WHERE kyushu_add.mmin <= $kyushug AND kyushu_add.mmax >= $kyushug";
    $kyuexg = $kandist2 - $hakatakan;
    $kyushu_calcexfare = "SELECT fare
        FROM kyushu_exp
        WHERE kyushu_exp.mmin <= $kyushug AND kyushu_exp.mmax >= $kyushug";
    try {
        $kyushuadd_calc = $pdo->prepare($kyushu_calcfare);
        $kyushuadd_calc->execute();
        foreach ($kyushuadd_calc->fetchAll() as $row) {
            $addfare = $row['fare'];
        }
    } catch (PDOException $Exception) {
        die("DB search error!:" . $Exception->getMessage());
    }
    try {
        $kyushuexp_calc = $pdo->prepare($kyushu_calcexfare);
        $kyushuexp_calc->execute();
        foreach ($kyushuexp_calc->fetchAll() as $row) {
            $kyushuexfare = $row['fare'];
        }
    } catch (PDOException $Exception) {
        die("DB search error!:" . $Exception->getMessage());
    }
    $honshu = $kandist - $kyuexg;
    $sql_calcexfare = "SELECT fare
        FROM express
        WHERE express.mmin <= $honshu AND express.mmax >= $honshu";
    try {
        $exfare_calc = $pdo->prepare($sql_calcexfare);
        $exfare_calc->execute();
        foreach ($exfare_calc->fetchAll() as $row) {
            $exfare = $row['fare'];
        }
    } catch (PDOException $Exception) {
        die("DB search error!:" . $Exception->getMessage());
    }
    $exfare = $exfare + $kyushuexfare - 530;
    $fare = $fare + $addfare;
}

// 九州新幹線
if (($railwaycompanyb == "JR九州" && $railwaycompanyg == "JR九州") ||
    ($boarding == "博多" && $railwaycompanyg == "JR九州" ||
        ($railwaycompanyb == "JR九州" && $getoff == "博多"))
) {
    $sql_calcfare = "SELECT fare
    FROM fare_kyushu
    WHERE fare_kyushu.mmin <= $dist AND fare_kyushu.mmax >= $dist";
    $sql_calcexfare = "SELECT fare
    FROM kyushu_exp
    WHERE kyushu_exp.mmin <= $dist AND kyushu_exp.mmax >= $dist";
    try {
        $fare_calc = $pdo->prepare($sql_calcfare);
        $fare_calc->execute();
        foreach ($fare_calc->fetchAll() as $row) {
            $fare = $row['fare'];
        }
    } catch (PDOException $Exception) {
        die("DB search error!:" . $Exception->getMessage());
    }
    try {
        $exfare_calc = $pdo->prepare($sql_calcexfare);
        $exfare_calc->execute();
        foreach ($exfare_calc->fetchAll() as $row) {
            $exfare = $row['fare'];
        }
    } catch (PDOException $Exception) {
        die("DB search error!:" . $Exception->getMessage());
    }
}



?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <link href="./system/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>運賃計算システム</title>
</head>

<body>
    <h1>運賃計算システム</h1>
    <hr>

  
    <p><?php echo $boarding; ?>駅

        から<?php echo $getoff; ?>駅
        <?php 
        // 特定都区市内における特例処理
        /* if($dist >= 201 && $tokug != "") {
                echo " ";
                echo $tokug;
                echo " ";
            }　*/ ?>
        までの旅費を計算します．</p>

    <?php if (!(($dist1 == "" || $dist2 == "") || ($boarding == $getoff))) {
        if (isset($_GET["gakuwari"])) {
            $gakuwari = $_GET["gakuwari"];
        } else {
            $gakuwari = "0";
        }
        if (isset($_GET["fukuwari"])) {
            $fukuwari = $_GET["fukuwari"];
        } else {
            $fukuwari = "0";
        }

        if ($gakuwari == "1" && $fukuwari == "1" && $dist >= 601) {
            $gakuwarifare = $fare * 0.8;
            $finalfare = $gakuwarifare * 0.9;
            $finalfare = (floor($finalfare / 10) * 10);
            $wari = $fare - $finalfare;
        } else if ($gakuwari == "1" && $dist >= 101) {
            $gakuwarifare = $fare * 0.8;
            $finalfare = (floor($gakuwarifare / 10) * 10);
            $wari = $fare - $finalfare;
        } else if ($fukuwari == "1" && $dist >= 601) {
            $finalfare = $fare * 0.9;
            $finalfare = (floor($finalfare / 10) * 10);
            $wari = $fare - $finalfare;
        } else {
            $finalfare = $fare;
            $wari = 0;
        }

        $sum = $finalfare + $exfare;

        // ログイン済みの場合は，検索履歴をDBに格納．
        if(isset($_SESSION['userID'])) {
            $UserID = $_SESSION['userID'];
            $sql_his = "INSERT INTO histories(UserID,boarding,getoff,gakuwari,fukuwari,search_time)
            VALUES (:UserID,:boarding,:getoff,$gakuwari,$fukuwari,:search_time)";
            try{
                $stmh=$pdo->prepare($sql_his);
                $stmh->bindvalue(":UserID","$UserID",PDO::PARAM_STR);
                $stmh->bindvalue(":boarding","$boarding",PDO::PARAM_STR);
                $stmh->bindvalue(":getoff","$getoff",PDO::PARAM_STR);
                $stmh->bindvalue(":search_time", "$now", PDO::PARAM_STR);
                $stmh->execute();
                $count=$stmh->rowCount();
                print "運賃検索履歴を保存しました．<br><br>";
        } catch(PDOException $Exception){
                die("エラー:".$Exception->getMessage());
        }
        }

    ?>
        <h4>料金（片道分）</h4>
        <div class="vertical-middle">
    <table class="table table-bordered">
            <tr>
                <td>乗車券（定価）</td>
                <td style="text-align: right"><?php echo number_format($fare); ?> 円</td>
            </tr>
            <tr>
                <td>新幹線券</td>
                <td style="text-align: right"><?php echo number_format($exfare); ?> 円</td>
            </tr>
            <tr>
                <td>割引計（学割・復割）</td>
                <td style="text-align: right">－<?php echo number_format($wari); ?> 円</td>
            </tr>
            <tr>
                <td>合計</td>
                <td style="text-align: right"><b><?php echo number_format($sum); ?> 円</b></td>
            </tr>
        </table>
        </div>
        <p>※表示の料金等の情報はあくまでも目安としてご利用ください．</p>
        <h4>詳細情報</h4>
        <div class="vertical-middle">
    <table class="table table-bordered">
            <tr>
                <td>計算キロ</td>
                <td><?php echo $dist; ?>km</td>
            </tr>
            <tr>
                <td>学割の可否</td>
                <td style="text-align:center"><?php if ($dist >= 101) {
                        print "○";
                    } else {
                        print "×";
                    } ?></td>
            </tr>
            <tr>
                <td>往復割の可否</td>
                <td style="text-align:center"><?php if ($dist >= 601) {
                        print "○";
                    } else {
                        print "×";
                    } ?></td>
            </tr>
            　　　　　　　　<tr>
                <td>途中下車の可否</td>
                <td style="text-align:center"><?php if ($dist >= 101) {
                        print "○";
                    } else {
                        print "×";
                    } ?></td>
            </tr>
            <tr>
                <td>乗車券有効期間</td>
                <td><?php print $limit; ?>日</td>
            </tr>
        </table>
        </div>
        <h4>備考</h4>
        <?php if ($gakuwari == "1" && $dist < 101) {
            printf("「学割適用」を選択されましたが，規定の条件（101km以上）を満たさなかったため，学割対象外となります．");
            echo "<br>";
        }
        if ($fukuwari == "1" && $dist < 601) {
            printf("「往復乗車券購入」を選択されましたが，規定の条件（601km以上）を満たさなかったため，往復割対象外となります．");
        }
        ?>

        </form>

        </table>
    <?php } else {
        printf("この経路は計算できません．別の乗車駅・降車駅を指定してください．");
    } ?>
<br/>
<a href="index.php">駅検索画面に戻る</a>
</body>

</html>
