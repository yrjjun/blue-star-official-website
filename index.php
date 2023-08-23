<?php
$status="yes";//
//获取当前文件的绝对路径
$current_file_path = dirname(__FILE__);
$absolute_file_path = realpath($current_file_path);
//echo $absolute_file_path;

//定义程序文件路径
define("_APP_INSTALL", $absolute_file_path."/app/install/");
define("_APP_LOGIN", $absolute_file_path."/app/login/");
define("_APP_SIGNUP", $absolute_file_path."/app/signup/");
define("_APP_SMTP", $absolute_file_path."/app/phpmailer/");

require("./function.php");
	function sendMail($to,$name,$title,$content,$array){

		//引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
		require_once(_APP_SMTP."class.phpmailer.php"); 
		require_once(_APP_SMTP."class.smtp.php");
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
	
		//简单的判断与提示信息
		if($status) {
			return true;
		}else{
			return false;
		}
	}
	function detection($conn){
		if(isset($_COOKIE["user_email"])){
			$email = preg_replace("/[^A-Za-z0-9.@]/", "", $_COOKIE["user_email"]);
			$select = mysqli_query($conn, "SELECT * FROM `blue_user` WHERE email='$email'");
			while($row = mysqli_fetch_assoc($select)) {
				$user = array("id"=>$row["id"],"password"=>$row["password"],"token"=>$row["token"],"ip"=>$row["ip"],'name'=>$orw["name"],'img'=>$orw["img"]);
			}
			return $user;
		}else{
			return false;
		}
	}
//判断PHP版本
$bluestar->php_version("7.0");
//判断是否安装
$bluestar->install(_APP_INSTALL);
//引入配置文件
require("./config.php");
$conn = mysqli_connect($host, $user, $password, $dbname);
$config = $bluestar->get_config($conn);

$product_card = $bluestar->get_product_card($conn);
$product_info = $bluestar->get_product($conn);
$service = $bluestar->get_service($conn);

$user_info = $bluestar->detection($conn);
$team_members = $bluestar->get_team_members($conn);
$bluestar->ip_access($conn);
$new_things = $bluestar->get_new_things($conn);
	// 检查是否使用了代理服务器
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
if($user_info != false){

    $bluestar->add_log($conn,"访问",$user_info["id"],$ip,time(),"200");
	$user_id=$user_info["id"];
}else{
    $bluestar->add_log($conn,"访问","free",$ip,time(),"200");
}

//路由程序
require($bluestar->path());

?>