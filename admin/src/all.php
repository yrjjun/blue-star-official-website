<?php
require("../../config.php");
$conn = mysqli_connect($host, $user, $password, $dbname);
require("../../function.php");
$user_info = $bluestar->detection($conn);
$isadmin = strpos($user_info["status"], "admin") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问admin面板", "", $_SERVER['REMOTE_ADDR'], time(), "200");
    header('Location: ../');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网站总览</title>
    <style>
        iframe {
            width: 100%;
            border: none;
            outline: none;
        }

        .list {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            margin: 20px 10px;
        }

        .list_row {
            display: flex;
            align-items: center;
            flex-direction: row;
            margin: 0px 10px 25px;
            background: #5383F1;
            padding: 15px 30px;
            color: #fff;
            border-radius: 10px;
        }

        .list_row_img {
            background: #fff4;
            border-radius: 100px;
            padding: 10px 12px;
        }

        .list_row_content {
            margin-left: 25px;
        }

        .list_row_content_title {
            font-size: 20px;
            font-weight: bold;
        }

        h3 {
            text-align: center;
        }

        h3 select {
            font-size: 16px;
        }
    </style>
</head>

<body>
    <h3>
        <select>
            <option>7天</option>
            <option>24小时</option>
        </select>
        浏览量 折线统计图
    </h3>
    <iframe src="./statistics.php?a=0&b=7&c=300&d=m-d" height="300"></iframe>
    <h3>数据总览</h3>
    <?php

    $info = $bluestar->get_website_info($conn);


    $array = array();
    for ($i = 0; $i < 24; $i++) {
        $num = 0;
        $time = time() - ($i * 3600);
        $time1 = time() - (($i + 1) * 3600);
        $select = mysqli_query($conn, "SELECT * FROM `blue_log` WHERE `time`>'$time1' AND `time`<='$time' AND `text`='访问'");
        while ($row = mysqli_fetch_assoc($select)) {
            $num += 1;
        }
        array_push($array, $num);
    }
    $sum = floor(array_reduce($array, function ($a, $b) {
        return $a + $b;
    }) / count($array));
    ?>
    <?php

    function getTime1()
    {
        $date = new DateTime();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        return $timestamp;
    }
    function getTime()
    {
        return array(getTime1(), 86400);
    }
    $array_ = array();
    $t = getTime();
    $time = $t[0];
    $time1 = $t[0] - $t[1];
    $timestamp = $time;
    $select = mysqli_query($conn, "SELECT * FROM `blue_log` WHERE `time`<'$time' AND `time`>'$time1' AND `text`='访问'");
    while ($row = mysqli_fetch_assoc($select)) {
        if (array_key_exists($row["ip"], $array_)) {
            $array_[$row["ip"]] += 1;
        } else {
            $array_[$row["ip"]] = 1;
        }
    }
    ?>
    <div class="list">
        <div class="list_row">
            <div class="list_row_img"><img src="../../file/image/user.png"></div>
            <div class="list_row_content">
                <div class="list_row_content_title">当前用户数</div>
                <div class="list_row_content_content"><?php echo $info["user"] ?>个</div>
            </div>
        </div>
        <div class="list_row" style="background:#ff8f68">
            <div class="list_row_img"><img src="../../file/image/user.png"></div>
            <div class="list_row_content">
                <div class="list_row_content_title">今日平均每小时访问量</div>
                <div class="list_row_content_content"><?php echo $sum ?>次 / h</div>
            </div>
        </div>
        <div class="list_row" style="background:#6453f1">
            <div class="list_row_img"><img src="../../file/image/user.png"></div>
            <div class="list_row_content">
                <div class="list_row_content_title">今日共有</div>
                <div class="list_row_content_content"><?php echo count(array_keys($array_)) ?>个IP访问了网站</div>
            </div>
        </div>
    </div>
    <script>
        ! function() {
            document.querySelector("select").addEventListener("change", function() {
                document.querySelector("iframe").src = [
                    "./statistics.php?a=0&b=7&c=300&d=m-d",
                    "./statistics.php?a=1&b=24&c=300&d=H时"
                ][this.selectedIndex]
            })
        }()
    </script>
</body>

</html>