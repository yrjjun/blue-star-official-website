<?php
header('Content-Type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("content-type: text/json;charset:utf-8");

require("../config.php");

//获取当前文件的绝对路径
$current_file_path = dirname(__FILE__);
$absolute_file_path = realpath($current_file_path);
//echo $absolute_file_path;

//定义程序文件路径
define("_APP_INSTALL", $absolute_file_path."/app/install/");
define("_APP_LOGIN", $absolute_file_path."/app/login/");
define("_APP_SIGNUP", $absolute_file_path."/app/signup/");
define("_APP_SMTP", $absolute_file_path."/app/phpmailer/");

require("../function.php");

$conn = mysqli_connect($host, $user, $password, $dbname);
$bluestar->get_function($conn,3);
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
if (mysqli_connect_errno()){//数据库链接失败
    $return = array('info'=>$conn->connect_error, 'code'=>100);
    echo json_encode($return);
}else{//数据库链接成功
    $to = $_REQUEST["to"];
    	// 检查是否使用了代理服务器
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
    $time = time();
    if($_REQUEST["mode"]=="send"){
        if (!(isset($_REQUEST["key"]) && isset($_REQUEST["to"]) && isset($_REQUEST["appid"]))) {
            $bluestar->add_log($conn, "调用发送邮件api:缺少请求参数", $row["user_id"], $ip, $time, "403");//插入日志
            $return = array('info' => "请检查请求参数，存在缺少", 'code' => 403);
            die(json_encode($return));
        }
        
        $appid = preg_replace('/[^A-Za-z0-9]/', '', $_REQUEST["appid"]);
        $hash = $_REQUEST["key"];
        $result = mysqli_query($conn, "SELECT * FROM blue_service WHERE appid=?");
        $stmt = mysqli_prepare($conn, "SELECT * FROM blue_service WHERE appid=?");
        mysqli_stmt_bind_param($stmt, "s", $appid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 0) {//不存在当前服务
            $bluestar->add_log($conn, "调用发送邮件api：不存在当前appid", $row["user_id"], $ip, $time, "404");//插入日志
            $return = array('info' => "请检查appid", 'code' => 404);
            echo json_encode($return);
        } else {
            $smtp_code_result = mysqli_query($conn, "SELECT * FROM blue_smtp_code WHERE email=?");
            $stmt_smtp_code = mysqli_prepare($conn, "SELECT * FROM blue_smtp_code WHERE email=?");
            mysqli_stmt_bind_param($stmt_smtp_code, "s", $to);
            mysqli_stmt_execute($stmt_smtp_code);
            $smtp_code_result = mysqli_stmt_get_result($stmt_smtp_code);
            if (mysqli_num_rows($smtp_code_result) != 0) {
                while ($row = mysqli_fetch_array($result)) {
                    if (hash("sha256", $to . $row["token"]) == $hash) {//允许发送

                        while ($smtp_code_row = mysqli_fetch_array($smtp_code_result)) {
                            $time=intval(time());
                            $row_time=intval($smtp_code_row["time"]);
                            if(($row_time+60)<$time){
                                $text = rand(100000, 999999);
                                $stmt_update_code = mysqli_prepare($conn, "UPDATE `blue_smtp_code` SET `code`=?, `time`=? WHERE `email`=?");
                                mysqli_stmt_bind_param($stmt_update_code, "sss", $text, time(), $to);
                                mysqli_stmt_execute($stmt_update_code);
                
                                // 调用sendMail函数等其他操作...
                                sendMail($to,$config["websitename"]."邮件验证码","请查收您的验证码","您的验证码是:".$text."请勿告诉他人哦", $config, "../app/phpmailer/");
                
                                $now_times = $row["times"] - 1;
                                $stmt_update_times = mysqli_prepare($conn, "UPDATE blue_service SET `times`=? WHERE `appid`=?");
                                mysqli_stmt_bind_param($stmt_update_times, "is", $now_times, $appid);
                                mysqli_stmt_execute($stmt_update_times);
                                $text_hash = base64_encode($text);
                                $bluestar->add_log($conn, "调用邮件验证码api：to-$to;", $row["user_id"], $ip, $time, "200");//插入日志
                                $return = array('code' => 200);
                                echo json_encode($return);
                            }else{
                                $bluestar->add_log($conn, "发送频率太快！", $row["user_id"], $ip, $time, "405");//插入日志
                                $return = array('info' => "发送频率太快！", 'code' => 405);
                                echo json_encode($return);
                            }
                        }
                    } else {
                        $bluestar->add_log($conn, "调用邮件验证码api：key验证不通过", $row["user_id"], $ip, $time, "401");//插入日志
                        $return = array('info' => "请检查key", 'code' => 401);
                        echo json_encode($return);
                    }
                }
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    if (hash("sha256", $to . $row["token"]) == $hash) {//允许发送
                        $text = rand(100000, 999999);
                        $stmt_insert_code = mysqli_prepare($conn, "INSERT INTO `blue_smtp_code` (`email`, `code`, `time`) VALUES (?, ?, ?)");
                        mysqli_stmt_bind_param($stmt_insert_code, "sss", $to, $text, $time);
                        mysqli_stmt_execute($stmt_insert_code);
        
                        // 调用sendMail函数等其他操作...
                        sendMail($to,$config["websitename"]."邮件验证码","请查收您的验证码","您的验证码是:".$text."请勿告诉他人哦", $config, "../app/phpmailer/");
        
                        $now_times = $row["times"] - 1;
                        $stmt_update_times = mysqli_prepare($conn, "UPDATE blue_service SET `times`=? WHERE `appid`=?");
                        mysqli_stmt_bind_param($stmt_update_times, "is", $now_times, $appid);
                        mysqli_stmt_execute($stmt_update_times);
        
                        $text_hash = base64_encode($text);
                        $bluestar->add_log($conn, "调用邮件验证码api：to-$to;", $row["user_id"], $ip, $time, "200");//插入日志
                        $return = array('code' => 200);
                        echo json_encode($return);
                    } else {
                        $bluestar->add_log($conn, "调用邮件验证码api：key验证不通过", $row["user_id"], $ip, $time, "401");//插入日志
                        $return = array('info' => "请检查key", 'code' => 401);
                        echo json_encode($return);
                    }
                }
            }
        }
    }else{
        $result = mysqli_query($conn,"SELECT * FROM blue_smtp_code WHERE email='$to'");
        if($result->num_rows ==0){
            $return = array('info'=>"未找到当前邮箱.".$to, 'code'=>401);
            die(json_encode($return));
        }else{
            while($row = mysqli_fetch_array($result)){
                if ($row["time"]+60*5<time()){
                    $return = array('info'=>"当前验证码已超时", 'code'=>402);
                    die(json_encode($return));
                }else{
                    if($row["code"]==intval($_REQUEST["code"])){
                        $return = array('info'=>"验证成功", 'code'=>200);
                        mysqli_query($conn, "DELETE FROM blue_code WHERE email='$to'");
                        die(json_encode($return));
                    }else{
                        $return = array('info'=>"验证码错误".$to, 'code'=>404);
                        die(json_encode($return));
                    }
                }
            }
        }
    }
}


?>
