<?php
$bluestar->get_function($conn, 7);
$to = preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);
$result = mysqli_query($conn, "SELECT * FROM blue_user WHERE email='$to'");
if ($result->num_rows == 0) {
    $return = array('info' => "不存在该用户", 'code' => 404);
    echo json_encode($return);
} else {
    while ($row = mysqli_fetch_array($result)) {
        if ($row["password"] == hash("sha256", $_REQUEST["password"])) {
            setcookie("user_email", $to, time() + 60 * 60 * 24 * 7);
            setcookie("user_password", hash("sha256", $_REQUEST["password"]), time() + 60 * 60 * 24 * 7);
            if ($row["ip"] == $_SERVER['REMOTE_ADDR']) {
                $return = array('code' => 200);
                echo json_encode($return);
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
                sendMail($to, $config["websitename"] . "平台登录告警", "您正在登录" . $config["websitename"] . "平台的账号<br>", '由于您登录的IP：<strong style="color:red">' . $ip . '</strong>与注册时的IP：<strong style="color:red">' . $row["ip"] . "不符，所以发送此邮件进行提醒，若您发现并非您本人登录，请务必修改您的登录密码，否则本平台不承担相应责任，谢谢配合！", $config);
                $return = array('code' => 200);
                echo json_encode($return);
            }
        } else {
            if ($row["password"] == md5($_REQUEST["password"] . "幻梦")) {
                mysqli_query($conn, "UPDATE `blue_user` SET `password`='" . hash("sha256", $_REQUEST["password"]) . "' WHERE `id`='" . $row["id"] . "'");
                setcookie("user_email", $to, time() + 60 * 60 * 24 * 7);
                setcookie("user_password", hash("sha256", $_REQUEST["password"]), time() + 60 * 60 * 24 * 7);
                if ($row["ip"] == $_SERVER['REMOTE_ADDR']) {
                    $return = array('code' => 200);
                    echo json_encode($return);
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                    sendMail($to, $config["websitename"] . "平台登录告警", "您正在登录" . $config["websitename"] . "平台的账号<br>", '由于您登录的IP：<strong style="color:red">' . $ip . '</strong>与注册时的IP：<strong style="color:red">' . $row["ip"] . "不符，所以发送此邮件进行提醒，若您发现并非您本人登录，请务必修改您的登录密码，否则本平台不承担相应责任，谢谢配合！", $config);
                    $return = array('code' => 200);
                    echo json_encode($return);
                }
            } else {
                $return = array('info' => "密码错误", 'code' => 400);
                echo json_encode($return);
            }
        }
    }
}
