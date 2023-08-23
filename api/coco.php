<?php
header('Content-Type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("content-type: text/json;charset:utf-8");
require("../config.php");
require("../function.php");
	// 检查是否使用了代理服务器
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
$conn = mysqli_connect($host, $user, $password, $dbname);
$config = $bluestar->get_config($conn);

function sendMail($to,$name,$title,$content,$array,$smtp_url){

    //引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
    require_once($smtp_url."class.phpmailer.php"); 
    require_once($smtp_url."class.smtp.php");
    //实例化PHPMailer核心类
    $mail = new PHPMailer();

    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 1;

    //使用smtp鉴权方式发送邮件
    $mail->isSMTP();

    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth=true;

    //链接qq域名邮箱的服务器地址
    $mail->Host = $array["smtp_host"];

    //设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = 'ssl';

    //设置ssl连接smtp服务器的远程服务器端口号，
    $mail->Port = intval($array["smtp_port"]);
    //设置发件人的主机域 可有可无
    $mail->Hostname = 'localhost';

    //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
    $mail->CharSet = 'UTF-8';

    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = $name;
    //这里主要是发件人设置，很重要
    //smtp登录的账号，也就是你要发件的邮箱
    $mail->Username =$array["smtp_email"];
    //smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
    $mail->Password = $array["smtp_password"];
    //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From = $array["smtp_email"];
    //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->isHTML(true); 
    //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
    $mail->addAddress($to,'收件人');
    //这个收件地址可有可无
    //添加多个收件人 则多次调用方法即可
    // $mail->addAddress('xxx@163.com','lsgo在线通知');
    //添加该邮件的主题
    $mail->Subject = $title;

    //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body = $content;

    //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
    // $mail->addAttachment('./d.jpg','mm.jpg');
    //同样该方法可以多次调用 上传多个附件
    // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');

    $status = $mail->send();


}

function runSQL($bluestar, $conn, $sql)
{
    $x = $bluestar->sql($conn, $sql);
    if ($x["code"] === 200) {
        return $x["data"];
    } else {
        die(json_encode(array("code" => 500, "data" => "数据库操作错误：" . $x["msg"])));
    }
};

if (mysqli_connect_errno($conn)) {
    die(json_encode(array("code" => 500, "data" => "数据库连接失败：" . mysqli_connect_error($conn))));
}

$sqltest = runSQL($bluestar, $conn, "SELECT 1");
if (!$sqltest) {
    die(json_encode(array("code" => 500, "data" => "数据库连接失败")));
}


$online = mysqli_query($conn, "SELECT * FROM `blue_function` WHERE `id`=5");
while ($row = mysqli_fetch_assoc($online)) {
    if ($row["status"] == 0) {
         die('{"code":403,"data":"API\\u670d\\u52a1\\u5df2\\u88ab\\u6682\\u65f6\\u5173\\u95ed"}');
    }
}

if (isset($_REQUEST["a"]) && $_SERVER['REQUEST_METHOD'] === "POST") {
    $return = null;
    $code = 200;
    switch ($_REQUEST["a"]) {
        case "login":
            $to = preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);
            $result = mysqli_query($conn, "SELECT * FROM blue_user WHERE email='$to'");
            if ($result->num_rows == 0) {
                $code = 404;
                $return = "邮箱或密码错误";
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row["password"] == hash("sha256", $_REQUEST["password"])) {
                        if ($row["ip"] == $ip) {
                            $return =array("id"=>$row["id"],"pw"=>$row["password"]);
                        } else {
                            $ip = $ip;
                            //sendMail($to, $config["websitename"] . "平台登录告警", "您正在登录" . $config["websitename"] . "APP平台的账号<br>", '由于您登录的IP：<strong style="color:red">' . $ip . '</strong>与注册时的IP：<strong style="color:red">' . $row["ip"] . "不符，所以发送此邮件进行提醒，若您发现并非您本人登录，请务必修改您的登录密码，否则本平台不承担相应责任，谢谢配合！", $config,"../app/phpmailer/");
                            $return =array("id"=>$row["id"],"pw"=>$row["password"]);
                        }
                    } else {
                        if ($row["password"] == md5($_REQUEST["password"] . "幻梦")) {
                            mysqli_query($conn, "UPDATE `blue_user` SET `password`='" . hash("sha256", $_REQUEST["password"]) . "' WHERE `id`='" . $row["id"] . "'");
                            $userkey = hash("sha256", $row["password"] . $row["token"]);
                            if ($row["ip"] == $ip) {
                                $return =array("id"=>$row["id"],"pw"=>$row["password"]);
                            } else {
                                $ip = $ip;
                                //sendMail($to, $config["websitename"] . "平台登录告警", "您正在登录" . $config["websitename"] . "APP平台的账号<br>", '由于您登录的IP：<strong style="color:red">' . $ip . '</strong>与注册时的IP：<strong style="color:red">' . $row["ip"] . "不符，所以发送此邮件进行提醒，若您发现并非您本人登录，请务必修改您的登录密码，否则本平台不承担相应责任，谢谢配合！", $config);
                                $return =array("id"=>$row["id"],"pw"=>$row["password"]);
                            }
                        } else {
                            $return = "邮箱或密码错误";
                            $code = 400;
                        }
                    }
                }
            }
            break;
        case "code":
            if ($_REQUEST["mode"] == "send") {
                $email = preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);

                $stmt_select = mysqli_prepare($conn, "SELECT * FROM blue_code WHERE email = ? AND code = '0'");
                mysqli_stmt_bind_param($stmt_select, "s", $email);
                mysqli_stmt_execute($stmt_select);
                
                $result_select = mysqli_stmt_get_result($stmt_select);
                
                if (mysqli_num_rows($result_select) == 0) {
                    $text = rand(100000, 999999);
                    $time = time();
                    $to = preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);
                
                    $stmt_insert = mysqli_prepare($conn, "INSERT INTO `blue_code` (`email`, `text`, `time`, `code`) VALUES (?, ?, ?, '0')");
                    mysqli_stmt_bind_param($stmt_insert, "sss", $to, $text, $time);
                    mysqli_stmt_execute($stmt_insert);
                    
                    sendMail($to, "BlueStarNet邮箱验证系统", "您正在注册/登录", "您的验证码是:" . $text . "请勿告诉他人哦", $config,"../app/phpmailer/");
                    
                    $return = "发送成功";
                } else {
                    while ($row = mysqli_fetch_array($result_select)) {
                        if ($row["time"] + 60 < time()) {
                            $text = rand(100000, 999999);
                            $time = time();
                            $to = preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);
                
                            $stmt_delete = mysqli_prepare($conn, "DELETE FROM `blue_code` WHERE email = ?");
                            mysqli_stmt_bind_param($stmt_delete, "s", $to);
                            mysqli_stmt_execute($stmt_delete);
                
                            $stmt_insert = mysqli_prepare($conn, "INSERT INTO `blue_code` (`email`, `text`, `time`, `code`) VALUES (?, ?, ?, '0')");
                            mysqli_stmt_bind_param($stmt_insert, "sss", $to, $text, $time);
                            mysqli_stmt_execute($stmt_insert);
                            
                            sendMail($to, "BlueStarNet邮箱验证系统", "您正在注册/登录", "您的验证码是:" . $text . "请勿告诉他人哦", $config,"../app/phpmailer/");
                            
                            $return = "发送成功";
                        } else {
                            $code = 500;
                            $return = "请稍后重试";
                        }
                    }
                }
            } else {
                $email = preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);

                $stmt = mysqli_prepare($conn, "SELECT * FROM blue_code WHERE email = ? AND code = '0'");
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) == 0) {
                    $return = "未找到当前验证邮箱";
                    $code = 401;
                } else {
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row["time"] + 60 * 5 < time()) {
                            $return = "当前验证码已超时";
                            $code = 402;
                        } else {
                            if (md5($row["text"]) == $_REQUEST["text"]) {
                                $return = "验证成功";
                                $stmt_delete = mysqli_prepare($conn, "DELETE FROM blue_code WHERE email = ?");
                                mysqli_stmt_bind_param($stmt_delete, "s", $email);
                                mysqli_stmt_execute($stmt_delete);
                                mysqli_stmt_close($stmt_delete);
                            } else {
                                $return = "验证码错误";
                                $code = 404;
                            }
                        }
                    }
                }
            }
            break;
        case "signup":
            $email = preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);

            $stmt = mysqli_prepare($conn, "SELECT * FROM blue_code WHERE email = ? AND code = '0'");
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            if ($result->num_rows == 0) {
                $return = "验证码错误";
                $code = 401;
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if ($row["time"] + 60 * 5 < time()) {
                        $return = "验证码错误";
                        $code = 402;
                    } else {
                        if ($row["text"] == $_REQUEST["text"]) {
                            $stmt = mysqli_prepare($conn, "SELECT * FROM blue_user WHERE email = ?");
                            mysqli_stmt_bind_param($stmt, "s", $to);
                            mysqli_stmt_execute($stmt);
                            
                            $user = mysqli_stmt_get_result($stmt);
                            if ($user->num_rows == 0) { //不存在该账户
                                $password = hash("sha256", $_REQUEST["password"]);
                                $token = hash("sha256", $to . $password . time());
                                $ip = $ip;
                                $time = time();
                                $stmt = mysqli_prepare($conn, "DELETE FROM `blue_code` WHERE email=?");
                                mysqli_stmt_bind_param($stmt, "s", $to);
                                mysqli_stmt_execute($stmt);

                                $stmt2 = mysqli_prepare($conn, "INSERT INTO `blue_user`(`email`, `password`, `token`, `ip`, `time`, `risk`, `money`, `status`, `name`, `img` ) VALUES (?, ?, ?, ?, ?, '0', '0.00', '', ?, ?)");
                                mysqli_stmt_bind_param($stmt2, "ssssssss", $to, $password, $token, $ip, $time, $status, $name, $img);
                                mysqli_stmt_execute($stmt2);
                                $bluestar->add_log($conn, "用户注册成功", $to, $ip, $time, "200"); //插入日志

                                $stmt = mysqli_prepare($conn, "SELECT * FROM blue_user WHERE email = ?");
                                mysqli_stmt_bind_param($stmt, "s", $to);
                                mysqli_stmt_execute($stmt);
                                $user = mysqli_stmt_get_result($stmt);
                                while ($userrow = mysqli_fetch_array($user)) {
                                    $return =array("id"=>$userrow["id"],"pw"=>$userrow["password"]);
                                }
                                setcookie("user_email", $to, time() + 60 * 60 * 24 * 7);
                                setcookie("user_password", hash("sha256", $_REQUEST["password"]), time() + 60 * 60 * 24 * 7);
                            } else {
                                $return = "账户已存在";
                                $code = 502;
                            }
                        } else {
                            $return = "验证码错误";
                            $code = 404;
                        }
                    }
                }
            }
            break;
        case "get_config":
            if (in_array($_REQUEST["b"], ["about", "qqgroup", "websitename", "websiteurl"])) {
                $t = $bluestar->get_config($conn);
                $return  = $t[$_REQUEST["b"]];
            } else {
                $code = 400;
                $return = "获取失败";
            }
            break;
        case "get_product_card":
            $return = $bluestar->get_product_card($conn);
            break;
        case "get_product":
            $return =  runSQL($bluestar, $conn, "SELECT * FROM `blue_product`");
            break;
        case "get_nav":
            $return =  runSQL($bluestar, $conn, "SELECT * FROM `blue_nav`");
            break;
        case "get_friend_url":
            $return = runSQL($bluestar, $conn, "SELECT * FROM `blue_friendurl`");
            break;
        case "get_new_things":
            $return = runSQL($bluestar, $conn, "SELECT * FROM `blue_new_things`");
            break;
        case "get_product_from":
            $return = $bluestar->get_product_from($conn);
            break;
        case "get_team_members":
            $return = $bluestar->get_team_members($conn);
            break;
        case "get_user_data":
            $code = 400;
            $user = array();
            $select = mysqli_query($conn, "SELECT * FROM `blue_user`");
            while ($row = mysqli_fetch_assoc($select)) {
                $user[$row["id"]] =$row;
            }
            if ($user[$_REQUEST["id"]]["password"]  == $_REQUEST["pw"]) {
                $code = 200;
                $return = $user[$_REQUEST["id"]];
                break;
            }
            break;
        case "change_user_name":
            $code = 400;
            $user = array();
            $select = mysqli_query($conn, "SELECT * FROM `blue_user`");
            while ($row = mysqli_fetch_assoc($select)) {
                $user[$row["id"]] =$row;
            }
            if ($user[$_REQUEST["id"]]["password"]  == $_REQUEST["pw"]) {
                $code = 200;
                $name = $_REQUEST["name"];
                $id = $_REQUEST["id"];
                $query = "UPDATE `blue_user` SET `name` = ? WHERE `id` = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "si", $name, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                break;
            }
            break;
          case "get_users":
            $code = 200;
            $user = array();
            $select = mysqli_query($conn, "SELECT * FROM `blue_user`");
            while ($row = mysqli_fetch_assoc($select)) {
                $user[$row["id"]] = array("id"=>$row["id"],"name"=>$row["name"],"img"=>$row["img"],"email"=>$row["email"],"money"=>$row["money"]);
            }
            
            if(isset($_REQUEST["id"])){
                $return = $user[$_REQUEST["id"]];
            }else{
                $return = $user;
            }
            break;
        case "change_user_img":
                $code = 400;
                $user = array();
                $select = mysqli_query($conn, "SELECT * FROM `blue_user`");
                while ($row = mysqli_fetch_assoc($select)) {
                    $user[$row["id"]] =$row;
                }
                if ($user[$_REQUEST["id"]]["password"]  == $_REQUEST["pw"]) {
                    $code = 200;
                    $img = $_REQUEST["img"];
                    $id = $_REQUEST["id"];
                    $query = "UPDATE `blue_user` SET `img` = ? WHERE `id` = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "si", $img, $id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    break;
                }
            break;
            
    }
    $bluestar->add_log($conn, "调用CoCoAPI（" . $_REQUEST["a"] . "）", "APP_user", $ip, time(), "200");
    die(json_encode(array("code" => $code, "data" => $return)));
} else {
    die(json_encode(array("code" => 200)));
}
