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
?>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>离开面板</h2>
    <p>离开后需重新进入</p>
    <button class="mdui-btn mdui-ripple mdui-btn-raised mdui-color-theme-accent">安全离开</button>
</div>
<script>
    !function () {
        mdui.$("button").on("click", function () {
            fetch("../?type=func&target=exit", {
                method: "POST"
            }).then(() => {
                window.top.location.reload();
            });
        });
    }();
</script>