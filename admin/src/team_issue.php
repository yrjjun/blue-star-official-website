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
    <title>团队任务</title>
</head>

<body>
    <link rel="stylesheet" href="../../file/css/mdui.min.css">
    <script src="../../file/js/mdui.min.js"></script>
    <div class="mdui-p-a-5">
        <h2>您当前的任务</h2>
        <div>
            <?php
            foreach ($bluestar->get_issue($conn) as $things) {
                $name = $things["name"];
                $text = $things["text"];
                if ($things["members"] == $user_info["email"]) {
                    echo <<<_
                    <div>
                        <h3>任务名：$name</h3>
                        <p >任务内容：$text</p>
                    </div>
                _;
                }
            }
            ?>
        </div>
    </div>
</body>

</html>