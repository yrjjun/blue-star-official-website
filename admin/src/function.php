<?php

require("../../config.php");
	// 检查是否使用了代理服务器
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
$conn = mysqli_connect($host, $user, $password, $dbname);
require("../../function.php");
$user_info = $bluestar->detection($conn);
$isadmin = strpos($user_info["status"], "admin") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问admin面板", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
?>
<script src="../../file/js/function.js"></script>
<script src="../../file/js/jq.js"></script>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>功能开关</h2>
    <p>官网功能开关</p>
    <ul class="mdui-list" style="padding:0">

        <?php
        if (isset($_GET["id"])) {
            $status = $_GET["status"];
            mysqli_query($conn, "UPDATE `blue_function` SET status='$status' WHERE id='" . $_GET["id"] . "'");
        }
        foreach ($bluestar->get_functions($conn) as $things) {
            $name = $things["name"];
            $id = $things["id"];
            $status = $things["status"];
            if ($status == 1) {
                $aaa = 0;
                $text = "checked";
            } else {
                $aaa = 1;
                $text = "";
            }
            echo <<<_
            <li class="mdui-list-item mdui-ripple">
                <div class="mdui-list-item-content">id：$id  功能名：$name</div>
                <label class="mdui-switch">
                <input type="checkbox" $text onchange="button('$id','$aaa')"/>
                <i class="mdui-switch-icon"></i>
                </label>
            </li>
        _;
        }
        ?>
    </ul>
</div>