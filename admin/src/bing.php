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
$isadmin = strpos($user_info["status"], "user") !== false;
if (!$isadmin) {
$bluestar->add_log($conn, "无权限访问user功能", "", $ip, time(), "200");
header('Location: ../');
exit;
}


if (isset($_POST['a'])) {



// 请求的URL
$url = 'https://ssl.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey='.$config["bing_key"];
$url_data = array();
$url_data[0] = $_POST["a"];
// 准备请求数据
$data = array(
    'siteUrl' => $config["domain"],
    'urlList' => $url_data
);

// 转换请求数据为JSON格式
$jsonData = json_encode($data);

// 设置请求头部信息
$headers = array(
    'Content-Type: application/json; charset=utf-8'
);

// 创建一个新的CURL资源
$ch = curl_init();

// 设置CURL选项
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// 发起HTTP请求
$response = curl_exec($ch);

// 检查错误
if ($response === false) {
    echo '请求失败: ' . curl_error($ch);
} else {
    // 获取响应状态码
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // 输出响应结果
    echo 'HTTP 状态码：' . $httpCode . PHP_EOL;
    echo '响应内容：' . $response;
}

// 关闭CURL资源
curl_close($ch);


    die("");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>HTML Form</title>
</head>

<body>
    <form action="./bing.php" method="post" name="form">
        <label for="a">链接：</label>
        <input type="text" id="a" name="a" required><br><br>
        <button type="submit">提交</button>
    </form>

</body>

</html>