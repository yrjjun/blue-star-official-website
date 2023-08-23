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
$isadmin = strpos($user_info["status"], "sql") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问sql功能", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sql_command"])) {
    $sql_command = $_POST["sql_command"];

    // 执行SQL命令
    if (mysqli_query($conn,$sql_command) === TRUE) {
        echo "执行成功";
    } else {
        echo "执行失败: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>MySQL命令调试器</title>
    <script src="//aeu.alicdn.com/waf/interfaceacting220819.js"></script><script src="//aeu.alicdn.com/waf/antidomxss_v640.js"></script><script>
        function insertTemplate(template) {
            document.getElementById("sql_command").value = template;
        }
    </script>
</head>
<body>
    <h2>MySQL命令调试器</h2>

    <form method="post" action="./sql.php">
        <textarea id="sql_command" name="sql_command" rows="5" cols="40"></textarea><br><br>
        
        <!-- 快捷模板按钮 -->
        <button type="button" onclick="insertTemplate('SELECT * FROM table_name')">SELECT</button>
        <button type="button" onclick="insertTemplate('UPDATE table_name SET column_name = new_value WHERE condition')">UPDATE</button>
        <!-- 添加其他快捷模板按钮 -->
        
        <br><br>
        <input type="submit" value="执行命令">
    </form>
</body>
</html>
