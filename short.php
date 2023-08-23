<?php
require("./config.php");
require("./function.php");
$bluestar->get_function(mysqli_connect($host, $user, $password, $dbname),1);
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 获取短码参数
if (isset($_GET['shortcode'])) {
    $shortcode = $_GET['shortcode'];

// 准备查询语句
$sql = "SELECT url FROM blue_urls WHERE shortcode = ?";

// 准备并绑定参数
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $shortcode);

// 执行查询
mysqli_stmt_execute($stmt);

// 获取结果
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // 找到匹配的短码，进行重定向
    $row = mysqli_fetch_assoc($result);
    $longUrl = $row['url'];
    header("Location: " . $longUrl);
    exit;
} else {
    // 未找到匹配的短码
    echo "无效的短码";
}
} else {
    // 缺少短码参数
    echo "缺少短码参数";
}

// 关闭数据库连接
$conn->close();
?>
