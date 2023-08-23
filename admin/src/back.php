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

if(isset($_REQUEST["mode"])){
    if($_REQUEST["mode"]=="sql"){
        // 查询数据库中的所有表
        $tables = array();
        $result = mysqli_query($conn, "SHOW TABLES");
        while ($row = mysqli_fetch_array($result)) {
            $tables[] = $row[0];
        }

        // 导出数据到SQL文件
        $sql = '';
        foreach ($tables as $table) {
            $result = mysqli_query($conn, "SELECT * FROM $table");
            $sql .= "DROP TABLE IF EXISTS $table;\n";
            $row2 = mysqli_fetch_row(mysqli_query($conn, "SHOW CREATE TABLE $table"));
            $sql .= $row2[1].";\n";

            // 获取主键列名
            $primaryKeys = array();
            $columns = mysqli_query($conn, "SHOW COLUMNS FROM $table");
            while ($column = mysqli_fetch_assoc($columns)) {
                if ($column['Key'] == 'PRI') {
                    $primaryKeys[] = $column['Field'];
                }
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $sql .= "INSERT INTO $table (";
                foreach ($row as $column => $value) {
                    $sql .= "`$column`,";
                }
                $sql = rtrim($sql, ',');
                $sql .= ") VALUES (";
                foreach ($row as $column => $value) {
                    // 检查是否为主键列
                    if (in_array($column, $primaryKeys)) {
                        $sql .= "'".mysqli_real_escape_string($conn, $value)."',";
                    } else {
                        $sql .= "'".mysqli_real_escape_string($conn, $value)."',";
                    }
                }
                $sql = rtrim($sql, ',');
                $sql .= ");\n";
            }
        }

        // 将SQL内容保存到文件
        $file = 'db.sql';
        if (file_put_contents($file, $sql) !== false) {
            // 备份完成后下载文件
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=db.sql");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");
            readfile($file);

            // 删除备份文件
            unlink($file);
        } else {
            echo "导出数据失败.";
        }

        // 关闭数据库连接
        mysqli_close($conn);
    }else{

    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>备份页面</title>
    <link rel="stylesheet" href="../../file/css/mdui.min.css">
    <link rel="stylesheet" href="../../file/css/admin.css">
    <script src="../../file/js/mdui.min.js"></script>
</head>
<body>
    <div class="mdui-container">
        <h1>备份页面</h1>
        <button class="mdui-btn mdui-color-theme-accent mdui-ripple" onclick="backupDatabase()">备份数据库</button>
        <button class="mdui-btn mdui-color-theme-accent mdui-ripple" onclick="backupFiles()">备份文件</button>
    </div>
    <script>
        function backupDatabase() {
            window.location.href = "./back.php?mode=sql";
        }

        function backupFiles() {
            window.location.href = "./back.php?mode=file";
        }
    </script>
</body>
</html>

