<?php
require("../config.php");
$conn = mysqli_connect($host, $user, $password, $dbname);
require("../function.php");
$config = $bluestar->get_config($conn);
$admin_url = $config["admin_url"];
$user_info = $bluestar->detection($conn);
$login_html = <<<_
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title>管理面板</title>
    <script src="../file/js/jQuery.min.js"></script>
    <style>
        #login {
            width: 400px;
            margin: 100px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        input {
            display: block;
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        #button {
            display: block;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        #button:hover {
            background-color: #3E8E41;
        }
    </style>
</head>

<body>
    <script>
        function install() {
            $.post({
                url: "/$admin_url/index.php",
                data: {
                    login: $("#password").val(),
                },
                success: function(data) {
                    if (typeof data === "string") {
                        data = JSON.parse(data);
                    };
                    if (data.code == 200) {
                        location.reload();
                    } else {
                        alert("Wrong password.");
                    }
                }
            });
        }
    </script>
    <div id="login">
        <h1>智能门锁 pro max 1TB</h1>
        <label for="password">请输入开门咒语：</label>
        <input type="password" name="password" id="password" required>
        <button id="button" onclick="install()">开门！</button>
    </div>
</body>

</html>
_;
	// 检查是否使用了代理服务器
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
$isadmin = strpos($user_info["status"], "admin") !== false;

if (isset($_REQUEST["type"]) and isset($_REQUEST["target"])) {
    if (!$isadmin) {
        $bluestar->add_log($conn, "无权限访问adminAPI", "", $ip, time(), "401");
        die(json_encode(array("code" => 401, "info" => "unauthorized")));
    }
}
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问admin面板", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
if (isset($_REQUEST["type"]) and isset($_REQUEST["target"])) {
    if ($_REQUEST["type"] == 'func') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            function runSQL($bluestar, $conn, $sql)
            {
                $x = $bluestar->sql($conn, $sql);
                if ($x["code"] === 200) {
                    return $x["data"];
                } else {
                    die(json_encode(array("code" => 500, "msg" => $x["msg"])));
                }
            };
            $return = null;
            switch ($_REQUEST["target"]) {
                case "time":
                    $return = time();
                    break;
                case "php_version":
                    $return = phpversion();
                    break;
                    /* GET */
                case "get_all_user":
                    $return = runSQL($bluestar, $conn, "SELECT * FROM `blue_user`");
                    break;
                case "get_all_email_code":
                    $return =  runSQL($bluestar, $conn, "SELECT * FROM `blue_code`");
                    break;
                case "get_config":
                    $return = $bluestar->get_config($conn);
                    break;
                case "get_product_card":
                    $return = $bluestar->get_product_card($conn);
                    break;
                case "get_product":
                    $return =  runSQL($bluestar, $conn, "SELECT * FROM `blue_product`");
                    break;
                case "get_nav":
                    $return =  runSQL($bluestar, $conn, "SELECT * FROM `blue_nav`");
                    break;
                case "get_friend_url":
                    $return = runSQL($bluestar, $conn, "SELECT * FROM `blue_friendurl`");
                    break;
                case "get_product_from":
                    $return = $bluestar->get_product_from($conn);
                    break;
                case "get_team_members":
                    $return = $bluestar->get_team_members($conn);
                    break;
                    /* CONFIG */
                case "set_config_about":
                    $p = $_POST["value"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p' WHERE name='about'");
                    $return = "success";
                    break;
                case "set_config_websitename":
                    $p = $_POST["value"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p' WHERE name='websitename'");
                    $return = "success";
                    break;
                case "set_config_websiteurl":
                    $p = $_POST["value"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p' WHERE name='websiteurl'");
                    $return = "success";
                    break;
                case "set_config_qqg":
                    $p = $_POST["value"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p' WHERE name='qqgroup'");
                    $return = "success";
                    break;
                case "set_smtp":
                    $p1 = $_POST["email"];
                    $p2 = $_POST["host"];
                    $p3 = $_POST["password"];
                    $p4 = $_POST["port"];
                    $select1 = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p1' WHERE name='smtp_email'");
                    $select2 = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p2' WHERE name='smtp_host'");
                    $select3 = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p3' WHERE name='smtp_password'");
                    $select4 = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p4' WHERE name='smtp_port'");
                    $return = "success";
                    break;
                case "set_easypay":
                    $p1 = $_POST["easypay_url"];
                    $p2 = $_POST["easypay_pid"];
                    $p3 = $_POST["easypay_key"];
                    $select1 = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p1' WHERE name='easypay_url'");
                    $select2 = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p2' WHERE name='easypay_pid'");
                    $select3 = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p3' WHERE name='easypay_key'");
                    $return = "success";
                    break;
                    /* NAV */
                case "add_nav":
                    $n = $_POST["name"];
                    $u = $_POST["url"];
                    $select = runSQL($bluestar, $conn, "INSERT INTO blue_nav (`name`, `url`) VALUES ('$n', '$u')");
                    $return = "success";
                    break;
                case "delete_nav":
                    $i = $_POST["id"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_nav WHERE `id`='$i'");
                    $return = "success";
                    break;
                case "change_nav":
                    $i = $_POST["id"];
                    $n = $_POST["name"];
                    $u = $_POST["url"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_nav SET `name`='$n', `url`='$u' WHERE `id`='$i'");
                    $return = "success";
                    break;
                    /* FRIEND_URL */
                case "add_friend_url":
                    $n = $_POST["name"];
                    $u = $_POST["url"];
                    $a  = $_POST["about"];
                    $im = $_POST["img"];
                    $s = $_POST["show"];
                    $select = runSQL($bluestar, $conn, "INSERT INTO blue_friendurl (`name`, `url`, `about`, `image`, `status`) VALUES ('" . $n . "', '" . $u . "', '" . $a . "', '" . $im . "', '" . $s . "')");
                    $return = "success";
                    break;
                case "delete_friend_url":
                    $i = $_POST["id"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_friendurl WHERE `id`='$i'");
                    $return = "success";
                    break;
                case "change_friend_url":
                    $i = $_POST["id"];
                    $n = $_POST["name"];
                    $u = $_POST["url"];
                    $a  = $_POST["about"];
                    $im = $_POST["img"];
                    $s = $_POST["show"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_friendurl SET `name`='" . $n . "', `url`='" . $u . "', `about`='" . $a . "', `image`='" . $im . "', `status`='" . $s . "' WHERE `id`='" . $i . "'");
                    $return = "success";
                    break;
                    /* product */
                case "add_product":
                    $from = $_POST["from"];
                    $name = $_POST["name"];
                    $buy = $_POST["buy"];
                    $admin = $_POST["admin"];
                    $money = $_POST["money"];
                    $money_mode = $_POST["money_mode"];
                    $about = $_POST["about"];
                    $select = runSQL($bluestar, $conn, "INSERT INTO blue_product (`from`, `name`, `buy`, `admin`, `money`, `money_mode`, `about`) VALUES ('" . $from . "', '" . $name . "', '" . $buy . "', '" . $admin . "', '" . $money . "', '" . $money_mode . "', '" . $about . "')");
                    break;
                case "add_pf":
                    $n = $_POST["name"];
                    $select = runSQL($bluestar, $conn, "INSERT INTO blue_product_from VALUES ('$n')");
                    break;
                case "delete_product":
                    $i = $_POST["id"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_product WHERE `name`='$i'");
                    break;
                case "delete_pf":
                    $n = $_POST["name"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_product_from WHERE `name`='$n'");
                    break;
                case "change_product":
                    $i = $_POST["id"];
                    $from = $_POST["from"];
                    $name = $_POST["name"];
                    $buy = $_POST["buy"];
                    $admin = $_POST["admin"];
                    $money = $_POST["money"];
                    $money_mode = $_POST["money_mode"];
                    $about = $_POST["about"];
                    $host = $_POST["host"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_product SET `from`='" . $from . "', `name`='" . $name . "', `buy`='" . $buy . "', `admin`='" . $admin . "', `money`='" . $money . "', `money_mode`='" . $money_mode . "', `about`='" . $about . "', `host`='".$host."' WHERE `id`='" . $i . "'");
                    break;
                    /* EMAIL_CODE */
                case "delete_email_code":
                    $i = $_POST["email"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_code WHERE `email`='$i'");
                    $return = "success";
                    break;
                    /* SMTP_CODE */
                case "get_smtp_email_code":
                    $select = runSQL($bluestar, $conn, "SELECT * FROM `blue_smtp_code`");
                    $return =  $select;
                    break;
                case "delete_smtp_email_code":
                    $i = $_POST["email"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_smtp_code WHERE `email`='$i'");
                    $return = "success";
                    break;
                    /* USER */
                case "delete_user":
                    $i = $_POST["id"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_user WHERE `id`='$i'");
                    $return = "success";
                    break;
                case "change_user":
                    $i = $_POST["id"];
                    $e = $_POST["email"];
                    $r = $_POST["risk"];
                    $m = $_POST["money"];
                    $name = $_POST["name"];
                    $img = $_POST["img"];
                    
                    $user_info = $bluestar->detection($conn);
                    $isstatus = strpos($user_info["status"], "status") !== false;
                    if(!$isstatus){
                        $select = runSQL($bluestar, $conn, "UPDATE blue_user SET `email`='$e', `risk`=$r, `money`=$m, `name`='$name', `img`='$img' WHERE `id`='$i'");
                    }else{
                        $status = $_POST["status"];
                        $select = runSQL($bluestar, $conn, "UPDATE blue_user SET `email`='$e', `risk`=$r, `money`=$m, `status`='$status', `name`='$name', `img`='$img' WHERE `id`='$i'");
                    }
                    
                    $return = "success";
                    break;
                    /* host */
                case "get_host":
                    $select = runSQL($bluestar, $conn, "SELECT * FROM `blue_vhost_vps`");
                    $return =  $select;
                    break;
                case "change_host":
                    $i = $_POST["id"];
                    $name = $_POST["name"];
                    $host_url = $_POST["host_url"];
                    $user_name = $_POST["user_name"];
                    $user_key = $_POST["user_key"];
                    $buy = $_POST["buy"];
                    $host_ip = $_POST["host_ip"];
                    $money = $_POST["money"];
                    $about = $_POST["about"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_vhost_vps SET `name`='" . $name . "', `host_url`='" . $host_url . "', `user_name`='" . $user_name . "', `user_key`='" . $user_key . "', `buy`='" . $buy . "', `host_ip`='" . $host_ip . "', `money`='" . $money . "', `about`='" . $about . "' WHERE `id`='" . $i . "'");
                    break;
                case "add_host":
                    $i = $_POST["id"];
                    $name = $_POST["name"];
                    $host_url = $_POST["host_url"];
                    $user_name = $_POST["user_name"];
                    $user_key = $_POST["user_key"];
                    $buy = $_POST["buy"];
                    $host_ip = $_POST["host_ip"];
                    $money = $_POST["money"];
                    $about = $_POST["about"];
                    $select = runSQL($bluestar, $conn, "INSERT INTO blue_vhost_vps (`name`, `host_url`, `user_name`, `user_key`, `buy`, `host_ip`, `money`, `about`) VALUES ('$name', '$host_url', '$user_name', '$user_key', '$buy', '$host_ip', '$money', '$about')");
                    break;
                case "delete_host":
                    $i = $_POST["id"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_vhost_vps WHERE `id`='$i'");
                    $return = "success";
                    break;
                    /* service */
                case "get_service":
                    $select = runSQL($bluestar, $conn, "SELECT * FROM `blue_service`");
                    $return =  $select;
                    break;
                case "change_service":
                    $i = $_POST["id"];
                    $user_id = $_POST["user_id"];
                    $product_id = $_POST["product_id"];
                    $appid = $_POST["appid"];
                    $token = $_POST["token"];
                    $long = $_POST["long"];
                    $admin = $_POST["admin"];
                    $name = $_POST["name"];
                    $times = $_POST["times"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_service SET `user_id`=" . $user_id . ", `product_id`='" . $product_id . "', `appid`='" . $appid . "', `token`='" . $token . "', `long`=" . $long . ", `admin`='" . $admin . "', `name`='" . $name . "', `times`=" . $times . " WHERE `id`='" . $i . "'");
                    break;
                case "add_service":
                    $user_id = $_POST["user_id"];
                    $product_id = $_POST["product_id"];
                    $appid = $_POST["appid"];
                    $token = $_POST["token"];
                    $long = $_POST["long"];
                    $admin = $_POST["admin"];
                    $name = $_POST["name"];
                    $times = $_POST["times"];
                    $time = time();
                    $select = runSQL(
                        $bluestar,
                        $conn,
                        "INSERT INTO blue_service (
                            `user_id`, `product_id`, `time`, `appid`, `token`, `long`, `admin`, `name`, `times`
                        ) VALUES (
                            $user_id, '$product_id', $time, '$appid', '$token', $long, '$admin', '$name', $times
                        )"
                    );
                    break;
                case "delete_service":
                    $i = $_POST["id"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_service WHERE `id`='$i'");
                    $return = "success";
                    break;
                    /* pay */
                case "get_pay":
                    $select = runSQL($bluestar, $conn, "SELECT * FROM `blue_pay`");
                    $return =  $select;
                    break;
                case "delete_pay":
                    $i = $_POST["id"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_pay WHERE `id`='$i'");
                    $return = "success";
                    break;
                    /* smtp_code */
                case "get_smtp_code":
                    $select = runSQL($bluestar, $conn, "SELECT * FROM `blue_smtp_code`");
                    $return =  $select;
                    break;
                case "delete_smtp_code":
                    $i = $_POST["id"];
                    $select = runSQL($bluestar, $conn, "DELETE FROM blue_smtp_code WHERE `id`='$i'");
                    $return = "success";
                    break;
                    /* OTHER */
                case "get_logs":
                    $select = runSQL($bluestar, $conn, "SELECT * FROM `blue_log`");
                    $return =  $select;
                    break;
                case "clear_logs":
                    $select = runSQL($bluestar, $conn, "DELETE FROM `blue_log`");
                    $return = "success";
                    break;
                case "get_post":
                    $return = array("post" => $_POST, "get" => $_GET, "r" => $_REQUEST);
                    break;
                case "send_rss_email":
                    if ($bluestar->send_rss_email($_REQUEST["nickname"], $_REQUEST["title"], $_REQUEST["text"], $config, "../app/phpmailer/", $bluestar->get_user($conn))) {
                        die(json_encode(array("code" => 200)));
                    } else {
                        die(json_encode(array("code" => 500)));
                    }
                    break;
                case "change_password":
                    $p = $_POST["old"];
                    $n = $_POST["new"];
                    $c = $bluestar->get_config($conn);
                    if ($c["admin"] === $p) {
                        $select = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$n' WHERE name='admin'");
                        $return = "success";
                    } else {
                        die(json_encode(array("code" => 401, "info" => "Wrong password.")));
                    }
                    break;
                case "exit":
                    setcookie("admin_token", "0");
                    break;
                case "set_config_domain":
                    $p = $_POST["value"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p' WHERE name='domain'");
                    $return = "success";
                    break;
                case "set_config_key_url":
                    $p = $_POST["value"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p' WHERE name='bing_key_url'");
                    $return = "success";
                    break;
                case "set_config_key":
                    $p = $_POST["value"];
                    $select = runSQL($bluestar, $conn, "UPDATE blue_config SET value='$p' WHERE name='bing_key'");
                    $return = "success";
                    break;
            }
            $log = json_encode($return);
            if (strlen($log) > 2000) {
                $log = "数据过长无法加载";
            }
            $bluestar->add_log($conn, "调用AdminAPI（" . $_REQUEST["target"] . "），返回结果：" . $log, "admin", $ip, time(), "200");
            die(json_encode(array("code" => 200, "data" => $return)));
        } else {
            $bluestar->add_log($conn, "使用非POST请求方式访问AdminAPI", "", $ip, time(), "405");
            die(json_encode(array("code" => 405)));
        }
    }
}

$bluestar->add_log($conn, "进入管理员面板", "admin", $ip, time(), "200");

echo "<!DOCTYPE html>";

?>

<html>

<head>
    <title>管理面板</title>
    <link rel="stylesheet" href="../file/css/mdui.min.css">
</head>

<body class="mdui-theme-primary-indigo mdui-theme-accent-blue mdui-appbar-with-toolbar mdui-drawer-body-left" style="overflow:hidden">
    <div class="mdui-appbar mdui-appbar-fixed">
        <div class="mdui-toolbar mdui-color-theme ">
            <button class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '#drawer'}">
                <i class="mdui-icon material-icons">menu</i>
            </button>
            <span class="mdui-typo-title">管理面板</span>
            <div class="mdui-toolbar-spacer"></div>
            <button class="mdui-btn mdui-btn-icon">
                <i class="mdui-icon material-icons">refresh</i>
            </button>
            <button class="mdui-btn mdui-btn-icon">
                <i class="mdui-icon material-icons">more_vert</i>
            </button>
        </div>
    </div>
    <div class="mdui-drawer" id="drawer">
        <ul class="mdui-list">
            <li class="mdui-subheader">基础信息</li>
            <a class="mdui-list-item mdui-ripple" href="#cfg">
                <i class="mdui-list-item-icon mdui-icon material-icons">web</i>
                <div class="mdui-list-item-content">官网信息</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#smtp">
                <i class="mdui-list-item-icon mdui-icon material-icons">email</i>
                <div class="mdui-list-item-content">SMTP信息</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#easypay">
                <i class="mdui-list-item-icon mdui-icon material-icons">payment</i>
                <div class="mdui-list-item-content">Easypay信息</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#function">
                <i class="mdui-list-item-icon mdui-icon material-icons">done_all</i>
                <div class="mdui-list-item-content">功能开关</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#all">
                <i class="mdui-list-item-icon mdui-icon material-icons">dashboard</i>
                <div class="mdui-list-item-content">仪表盘</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#bing">
                <i class="mdui-list-item-icon mdui-icon material-icons">dashboard</i>
                <div class="mdui-list-item-content">必应提交网址</div>
            </a>
            <li class="mdui-subheader">团队协同</li>
            <a class="mdui-list-item mdui-ripple" href="#team_issue">
                <i class="mdui-list-item-icon mdui-icon material-icons">format_align_center</i>
                <div class="mdui-list-item-content">当前任务</div>
            </a>
            <li class="mdui-subheader">官网管理</li>
            <a class="mdui-list-item mdui-ripple" href="#nav">
                <i class="mdui-list-item-icon mdui-icon material-icons">navigation</i>
                <div class="mdui-list-item-content">导航栏管理</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#friend">
                <i class="mdui-list-item-icon mdui-icon material-icons">insert_link</i>
                <div class="mdui-list-item-content">友链管理</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#product_card">
                <i class="mdui-list-item-icon mdui-icon material-icons" style="transform:rotate(180deg)">credit_card</i>
                <div class="mdui-list-item-content">产品卡片管理</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#developer">
                <i class="mdui-list-item-icon mdui-icon material-icons">group</i>
                <div class="mdui-list-item-content">团队开发人员管理</div>
            </a>
            <li class="mdui-subheader">用户</li>
            <a class="mdui-list-item mdui-ripple" href="#user">
                <i class="mdui-list-item-icon mdui-icon material-icons">person</i>
                <div class="mdui-list-item-content">用户管理</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#code">
                <i class="mdui-list-item-icon mdui-icon material-icons">email</i>
                <div class="mdui-list-item-content">注册验证码日志</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#smtp_code">
                <i class="mdui-list-item-icon mdui-icon material-icons">email</i>
                <div class="mdui-list-item-content">验证码API日志</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#service">
                <i class="mdui-list-item-icon mdui-icon material-icons">shopping_cart</i>
                <div class="mdui-list-item-content">API服务管理</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#payment">
                <i class="mdui-list-item-icon mdui-icon material-icons">attach_money</i>
                <div class="mdui-list-item-content">支付信息</div>
            </a>
            <li class="mdui-subheader">产品</li>
            <a class="mdui-list-item mdui-ripple" href="#new_things">
                <i class="mdui-list-item-icon mdui-icon material-icons">fiber_new</i>
                <div class="mdui-list-item-content">新品管理</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#product">
                <i class="mdui-list-item-icon mdui-icon material-icons">format_list_bulleted</i>
                <div class="mdui-list-item-content">产品管理</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#product_from">
                <i class="mdui-list-item-icon mdui-icon material-icons">class</i>
                <div class="mdui-list-item-content">产品分类管理</div>
            </a>
            <li class="mdui-subheader">论坛</li>
            <a class="mdui-list-item mdui-ripple" href="#shequ">
                <i class="mdui-list-item-icon mdui-icon material-icons">note</i>
                <div class="mdui-list-item-content">帖子管理</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#shequ_talk">
                <i class="mdui-list-item-icon mdui-icon material-icons">reply</i>
                <div class="mdui-list-item-content">评论管理</div>
            </a>
            <li class="mdui-subheader">虚拟主机</li>
            <a class="mdui-list-item mdui-ripple" href="#host">
                <i class="mdui-list-item-icon mdui-icon material-icons">computer</i>
                <div class="mdui-list-item-content">虚拟主机母机管理</div>
            </a>
            <li class="mdui-subheader">高级</li>
            <a class="mdui-list-item mdui-ripple" href="#rss_email">
                <i class="mdui-list-item-icon mdui-icon material-icons">subject</i>
                <div class="mdui-list-item-content">发布邮件订阅</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#logs">
                <i class="mdui-list-item-icon mdui-icon material-icons">reorder</i>
                <div class="mdui-list-item-content">后台日志</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#back">
                <i class="mdui-list-item-icon mdui-icon material-icons">reorder</i>
                <div class="mdui-list-item-content">数据库备份</div>
            </a>
            <a class="mdui-list-item mdui-ripple" href="#sql">
                <i class="mdui-list-item-icon mdui-icon material-icons">reorder</i>
                <div class="mdui-list-item-content">MySQL调试器</div>
            </a>
        </ul>
    </div>
    <div id="app"></div>
    <script>
        ! function() {
            function double() {
                var now = new Date().getHours();
                if (now <= 5) {
                    return "凌晨"
                } else if (now > 5 && now <= 9) {
                    return "上午"
                } else if (now > 9 && now <= 11) {
                    return "上午"
                } else if (now > 11 && now <= 12) {
                    return "中午"
                } else if (now > 12 && now <= 17) {
                    return "下午"
                } else if (now > 17 && now <= 18) {
                    return "傍晚"
                } else if (now > 18 && now <= 22) {
                    return "晚上"
                } else if (now > 22) {
                    return "深夜"
                }
            };
            var a = document.createElement("iframe");
            a.style.cssText = "margin:0;border:none;width:100%;";
            setInterval(() => {
                a.style.height = (window.innerHeight - document.querySelector(".mdui-toolbar").offsetHeight) + "px";
            }, 16);
            a.addEventListener("load", function(event) {
                if (!location.hash || location.hash === "#") {
                    document.title = "管理面板 - 首页";
                    a.contentDocument.write(`<h2>${double()}好！欢迎来到管理面板！</h2>`);
                } else {
                    a.contentDocument.body.classList.add("mdui-theme-accent-blue");
                }
            });
            document.querySelector("#app").append(a);
            var temp = 0;
            document.querySelectorAll("a").forEach(e => {
                e.onclick = function() {
                    if (a.contentWindow.has_change) {
                        mdui.confirm("你貌似还有未保存的更改！真的要离开吗？", null, function() {
                            a.contentWindow.has_change = null;
                            e.click();
                        })
                        return false;
                    }
                };
            });
            setInterval(() => {
                if (temp !== location.hash) {
                    temp = location.hash;
                    document.querySelectorAll(".mdui-list-item").forEach(e => {
                        if (e.getAttribute("href") == location.hash) {
                            document.title = "管理面板 - " + e.querySelector(".mdui-list-item-content").innerText;
                            e.classList.add("mdui-list-item-active")
                        } else {
                            e.classList.remove("mdui-list-item-active")
                        }
                    });
                    a.src = "./src/" + location.hash.slice(1) + ".php";
                };
            });
            window.writeHtml = function(html, callback) {
                var x = mdui.$(html);
                x.appendTo(document.body);
                callback({
                    w: window,
                    jq: x
                });
            };
        }();
    </script>
    <script src="../file/js/mdui.min.js"></script>
</body>

</html>