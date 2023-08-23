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
$isadmin = strpos($user_info["status"], "smtp") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问smtp功能", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
?>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>SMTP信息</h2>
    <p>SMTP邮件发送配置</p>
    <div class="mdui-textfield mdui-textfield-floating-label mdui-textfield-floating-label">
        <label class="mdui-textfield-label">邮箱</label>
        <input class="mdui-textfield-input" />
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label mdui-textfield-floating-label">
        <label class="mdui-textfield-label">链接地址</label>
        <input class="mdui-textfield-input" />
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label mdui-textfield-floating-label">
        <label class="mdui-textfield-label">授权码</label>
        <input class="mdui-textfield-input" />
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label mdui-textfield-floating-label">
        <label class="mdui-textfield-label">端口</label>
        <input class="mdui-textfield-input" />
    </div>
    <button class="mdui-btn mdui-ripple mdui-btn-raised mdui-color-theme-accent mdui-m-t-4">保存更改</button>
</div>
<script>
    !function () {
        fetch("../?type=func&target=get_config", {
            method: "POST"
        }).then(v => v.json()).then(v => {
            mdui.$("input")[0].value = v.data.smtp_email;
            mdui.$("input")[1].value = v.data.smtp_host;
            mdui.$("input")[2].value = v.data.smtp_password;
            mdui.$("input")[3].value = v.data.smtp_port;
            mdui.updateTextFields()
            mdui.$("button").on("click", function () {
                mdui.$.ajax({
                    url: "../?type=func&target=set_smtp",
                    method: "POST",
                    data: {
                        email: mdui.$("input")[0].value,
                        host: mdui.$("input")[1].value,
                        password: mdui.$("input")[2].value,
                        port: mdui.$("input")[3].value,
                    },
                    success: function () {
                        window.has_change = null;
                        window.top.mdui.snackbar("保存成功", { position: "left-bottom" })
                    }
                })
            });
            mdui.$("input").on("input", function () {
                window.has_change = () => true;
            });
        });
    }();
</script>