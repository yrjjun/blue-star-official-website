<?php
class BlueStarNetClass
{
	function php_version($num)
	{
		// 获取当前 PHP 版本号
		$current_version = phpversion();

		// 目标版本号
		$target_version = $num;

		// 比较版本号
		if (version_compare($current_version, $target_version) >= 0) {
		} else {
			die('PHP 版本号低于目标版本号');
		}
	}
	function install($path)
	{
		$file_content = file_get_contents($path . "install.lock");
		if ($file_content == "1") { //未安装
			$status = "yes";
			require(_APP_INSTALL . "index.php");
			exit();
		}
	}
	function sha256($text)
	{
		return hash("sha256", $text);
	}
	function path()
	{ //路由
		if (isset($_REQUEST["path"])) {
			return "./app/" . $_REQUEST["path"] . "/" . $_REQUEST["page"] . ".php";
		} else {
			return "./app/page/index.php";
		}
	}

	function get_config($conn)
	{
		$config = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_config`");
		while ($row = mysqli_fetch_assoc($select)) {
			$config[$row["name"]] = $row["value"];
		}
		return $config;
	}
	function get_product_card($conn)
	{
		$product_card = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_product_card`");
		while ($row = mysqli_fetch_assoc($select)) {
			$product_card[$row["id"]] = array($row["name"], $row["about"], $row["img"], $row["url"]);
		}
		return $product_card;
	}
	function get_product($conn)
	{
		$product = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_product`");
		while ($row = mysqli_fetch_assoc($select)) {
			$product[$row["id"]] = array("id" => $row["id"], "name" => $row["name"], "about" => $row["about"], "from" => $row["from"], "code" => $row["code"], "program" => $row["program"], "money" => $row["money"], "admin" => $row["admin"], "money_mode" => $row["money_mode"], "buy" => $row["buy"],"host"=>$row["host"]);
		}
		return $product;
	}
	function detection($conn)
	{
		if (isset($_COOKIE["user_email"])) {
			$email = preg_replace("/[^A-Za-z0-9.@]/", "", $_COOKIE["user_email"]);
			$select = mysqli_query($conn, "SELECT * FROM `blue_user` WHERE email='$email'");
			while ($row = mysqli_fetch_assoc($select)) {
				if ($row["password"] == $_COOKIE["user_password"]) {
					$user = $row;
				} else {
					return false;
				}
			}
			return $user;
		} else {
			return false;
		}
	}
	function get_nav($conn)
	{
		$nav = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_nav`");
		while ($row = mysqli_fetch_assoc($select)) {
			$nav[$row["name"]] = array("name" => $row["name"], "url" => $row["url"]);
		}
		return $nav;
	}
	function get_product_from($conn)
	{
		$from = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_product_from`");
		while ($row = mysqli_fetch_assoc($select)) {
			array_push($from, $row["name"]);
		}
		return $from;
	}
	function get_team_members($conn)
	{
		$members = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_team_members`");
		while ($row = mysqli_fetch_assoc($select)) {
			$members[] = array("name" => $row["name"], "about" => $row["about"], "img_url" => $row["img_url"]);
		}
		return $members;
	}
	function add_log($conn, $text, $user_id, $ip, $time, $code)
	{
		mysqli_query($conn, "INSERT INTO `blue_log`(`text`, `user`, `ip`, `time`, `code`) VALUES ('$text', '$user_id', '$ip', '$time', '$code')");
	}
	function sendMail($to, $name, $title, $content, $array)
	{

		//引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
		require_once(_APP_SMTP . "class.phpmailer.php");
		require_once(_APP_SMTP . "class.smtp.php");
		//实例化PHPMailer核心类
		$mail = new PHPMailer();

		//是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
		$mail->SMTPDebug = 1;

		//使用smtp鉴权方式发送邮件
		$mail->isSMTP();

		//smtp需要鉴权 这个必须是true
		$mail->SMTPAuth = true;

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
		$mail->Username = $array["smtp_email"];
		//smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
		$mail->Password = $array["smtp_password"];
		//设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
		$mail->From = $array["smtp_email"];
		//邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
		$mail->isHTML(true);
		//设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
		$mail->addAddress($to, '收件人');
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
		if ($status) {
			return true;
		} else {
			return false;
		}
	}
	function ip_access($conn)
	{    // 检查是否使用了代理服务器
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$client_ip = preg_replace('/[^.:A-Za-z0-9]/', '', $ip);

		$query = "SELECT COUNT(*) AS count FROM access_log WHERE ip = '$client_ip' AND timestamp > DATE_SUB(NOW(), INTERVAL 8 SECOND)";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
		$count = $row['count'];

		if ($count >= 4) {
			mysqli_query($conn, "INSERT INTO `blue_log`(`text`, `user`, `ip`, `time`, `code`) VALUES ('访问失败', '', '$client_ip', '" . time() . "', '304')");

			header('HTTP/1.1 304 Not Modified');
			exit();
		}

		$query = "INSERT INTO access_log (ip, timestamp) VALUES ('$client_ip', NOW())";
		mysqli_query($conn, $query);
	}
	function sql($conn, $sql){
		$s = strtolower(trim($sql));
		$sql_type = '';
		if (strpos($s, 'select') === 0) {
			$sql_type = 'SELECT';
			$data = array();
			$return = array();
			$select = mysqli_query($conn, $sql);
			if ($select) {
				while ($row = mysqli_fetch_assoc($select)) {
					$data[] = $row;
				}
				$return["code"] = 200;
				$return["data"] = $data;
			} else {
				$return["code"] = 500;
				$return["msg"] = mysqli_error($conn);
			}
			return $return;
		} elseif (strpos($s, 'update') === 0) {
			$sql_type = 'UPDATE';
			if (mysqli_query($conn, $sql)) {
				$return["code"] = 200;
				return $return;
			} else {
				$return["code"] = 500;
				$return["msg"] = mysqli_error($conn);
				return $return;
			}
		} elseif (strpos($s, 'insert') === 0) {
			$sql_type = 'INSERT';
			if (mysqli_query($conn, $sql)) {
				$return["code"] = 200;
				return $return;
			} else {
				$return["code"] = 500;
				$return["msg"] = mysqli_error($conn);
				return $return;
			}
		} elseif (strpos($s, 'delete') === 0) {
			$sql_type = 'DELETE';
			if (mysqli_query($conn, $sql)) {
				$return["code"] = 200;
				return $return;
			} else {
				$return["code"] = 500;
				$return["msg"] = mysqli_error($conn);
				return $return;
			}
		}
	}
	function get_service($conn){
		$service = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_service`");
		while ($row = mysqli_fetch_assoc($select)) {
			$service[$row["id"]] = array("id" => $row["id"], "name" => $row["name"], "appid" => $row["appid"], "user_id" => $row["user_id"], "times" => $row["times"], "token" => $row["token"], "times" => $row["times"], "admin" => $row["admin"], "long" => $row["long"], "product_id"=>$row["product_id"]);
		}
		return $service;
	}
	function send_rss_email($name, $title, $content, $array, $path, $user){

		//引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
		require_once($path. "class.phpmailer.php");
		require_once($path . "class.smtp.php");
		//实例化PHPMailer核心类
		$mail = new PHPMailer();

		//是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
		$mail->SMTPDebug = 1;

		//使用smtp鉴权方式发送邮件
		$mail->isSMTP();

		//smtp需要鉴权 这个必须是true
		$mail->SMTPAuth = true;

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
		$mail->Username = $array["smtp_email"];
		//smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
		$mail->Password = $array["smtp_password"];
		//设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
		$mail->From = $array["smtp_email"];
		//邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
		$mail->isHTML(true);
		//设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
		//这个收件地址可有可无
		//添加多个收件人 则多次调用方法即可
		$mail->addAddress($user[0],'');
		unset($user[0]);
		foreach ($user as $people) {
			$mail->addBCC($people);
		}
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
		if ($status) {
			return true;
		} else {
			return false;
		}
	}
	function get_user($conn){
		$user = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_user`");
		while ($row = mysqli_fetch_assoc($select)) {
			$user[] =$row["email"];
		}
		return $user;
	}
	function turn_bad_words($path, $str){
		$words = json_decode(base64_decode(str_replace(" ","+",file_get_contents($path))));
		
		$text = $str;

		// 将文本拆分成单词或单个字符
		$words_arr = preg_split('/(?<!^)(?!$)/u', $text);

		foreach ($words_arr as &$word) {
    		if (in_array($word, $words)) {
        		// 如果单词或单个字符在敏感词数组中出现，则替换为 *
        		$word = "*";
    		}
		}

		// 将所有单词或单个字符重新拼接成字符串
		$new_text = implode("", $words_arr);

		return $new_text;
	}
	function bad_words($path, $str){
		$words = json_decode(base64_decode(str_replace(" ","+",file_get_contents($path))),true);
		$text = $str;
		foreach ($words as &$word) {
			if (strpos($text, $word) !== false) {
				return true;
			} else {
			}
		}
	}


	function get_new_things($conn){
		$things = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_new_things`");
		while ($row = mysqli_fetch_assoc($select)) {
			$things[] = array("id" => $row["id"], "name" => $row["name"], "url" => $row["url"], "img" => $row["img"], "about" => $row["about"], "time" => $row["time"]);
		}
		return $things;
	}
	function get_issue($conn){
		$issue = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_issue`");
		while ($row = mysqli_fetch_assoc($select)) {
			$issue[] = array("id" => $row["id"], "name" => $row["name"], "text" => $row["text"], "members" => $row["members"]);
		}
		return $issue;
	}
	function get_shequ($conn,$p){
		$shequ = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_post` ORDER BY time DESC;");
		while ($row = mysqli_fetch_assoc($select)) {
			$shequ[] = array("id" => $row["id"], "user_id" => $row["user_id"], "title" => $row["title"], "text" => $row["text"], "time" => $row["timet"], "ip" => $row["ip"], "goods" => $row["goods"], "views" => $row["views"], "likes" => $row["likes"], "ishot" => $row["ishot"], "ison" => $row["ison"], "isbest" => $row["isbest"]);
		}
		if($p==1){
			$echo_shequ = array();
			$num=0;
			foreach ($shequ as $post) {
				if($num==20){
					return $echo_shequ;
				}
				if($post["ison"]==1){
					$echo_shequ[]=$post;
					$num+1;
				}elseif($post["isbest"]==1){
					$echo_shequ[]=$post;
					$num+1;
				}elseif($post["ishot"]==1){
					$echo_shequ[]=$post;
					$num+1;
				}else{

					$echo_shequ[]=$post;
				}
			}
		}else{
			// 调用数组的0到19项
			for ($i = (intval($p)-1)*20; $i < intval($p)*20; $i++) {
				$echo_shequ[]=$shequ[$i];
  			}
			
		}
		return $echo_shequ;
	}
	function get_user_info($conn){
		$user = array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_user`");
		while ($row = mysqli_fetch_assoc($select)) {
			$user[$row["id"]] =$row["email"];
		}
		return $user;
	}
	function get_shequ_pages($conn){
		$pages = 0;
		$select = mysqli_query($conn, "SELECT * FROM `blue_post`");
		while ($row = mysqli_fetch_assoc($select)) {
			$pages+=1;
		}
		return $pages %20;
	}
	function get_website_info($conn){
		$info = array();
		$num=0;
                $time=time()-3600;
		$select = mysqli_query($conn, "SELECT * FROM `blue_log` WHERE `time`>'$time'");
		while ($row = mysqli_fetch_assoc($select)) {
			$num=$num+1;
		}
		$info["times"]=$num;
		$num=0;
		$select = mysqli_query($conn, "SELECT * FROM `blue_user`");
		while ($row = mysqli_fetch_assoc($select)) {
			$num=$num+1;
		}
		$info["user"]=$num;
		return $info;
	}
	function get_function($conn,$id){
		$select = mysqli_query($conn, "SELECT * FROM `blue_function` WHERE `id`=$id");
		while ($row = mysqli_fetch_assoc($select)) {
			if($row["status"]==0){
				die("该功能暂时关闭");
			}
		}
	}
	function get_functions($conn){
		$functions=array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_function`");
		while ($row = mysqli_fetch_assoc($select)) {
			$functions[]=array("id"=>$row["id"],"name"=>$row["name"],"status"=>$row["status"]);
		}
		return $functions;
	}
	function get_host($conn){
		$hosts=array();
		$select = mysqli_query($conn, "SELECT * FROM `blue_vhost_vps`");
		while ($row = mysqli_fetch_assoc($select)) {
			$hosts[$row["id"]]=array("id"=>$row["id"],"name"=>$row["name"],"host_url"=>$row["host_url"],"user_name"=>$row["username"],"user_key"=>$row["user_key"],"buy"=>$row["host"],"host_ip"=>$row["host_ip"],"money"=>$row["money"],"about"=>$row["about"]);
		}
		return $hosts;
	}
}
$bluestar = new BlueStarNetClass;
