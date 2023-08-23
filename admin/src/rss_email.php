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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>发布邮件订阅</title>
    <link rel="stylesheet" href="../../file/css/mdui.min.css">
    <script src="../../file/js/mdui.min.js"></script>
    <script src="../../file/js/jq.js"></script>

</head>
<body>
<div class="mdui-p-a-5 mdui-typo">
    <h2>发布邮件订阅</h2>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">邮件广告标题</label>
        <input class="mdui-textfield-input" id="email_title" />
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">邮件广告发件人昵称</label>
        <input class="mdui-textfield-input" id="email_nickname"/>
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">邮件广告内容</label>
        <textarea class="mdui-textfield-input" id="email_text"></textarea>
    </div>
    <button class="mdui-btn mdui-ripple mdui-btn-raised mdui-color-theme-accent mdui-m-t-4" id="save">发送</button>
</div>
<script>
    $("#save").click(function() {
        $.post("../?type=func&target=send_rss_email", {title: $("#email_title").val(), text: $("#email_text").val(), nickname: $("#email_nickname").val()}, function(response){
            // 在这里编写处理响应的代码
            console.log(response);
        });
    });

</script>
</body>
</html>