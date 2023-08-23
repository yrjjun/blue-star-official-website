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
$config = $bluestar->get_config($conn);
$user_info = $bluestar->detection($conn);
$isadmin = strpos($user_info["status"], "admin") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问admin面板", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
$isadmin = strpos($user_info["status"], "password") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问password功能", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
?>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>更改咒语</h2>
    <p>更改后您需要重新进入</p>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">原咒语</label>
        <input class="mdui-textfield-input" type="password" />
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">新咒语</label>
        <input class="mdui-textfield-input" type="password" />
    </div>
    <button class="mdui-btn mdui-ripple mdui-btn-raised mdui-color-theme-accent mdui-m-t-4">更改</button>
    <script>
        ! function() {
            mdui.$("button").on("click", function() {
                mdui.$.ajax({
                    url: "../?type=func&target=change_password",
                    method: "POST",
                    data: {
                        old: mdui.$("input")[0].value,
                        new: mdui.$("input")[1].value,
                    },
                    success: function(data) {
                        if (typeof data === "string") {
                            data = JSON.parse(data);
                        };
                        if (data.code == 200) {
                            window.top.location.href = "../";
                        } else {
                            alert("原咒语不正确");
                        }
                    }
                })
            })
        }()
    </script>
</div>