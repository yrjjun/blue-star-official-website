<?php

// 处理表单提交
if (isset($_POST['url'])) {
	
	$code = $_POST['code'];
	$id=md5($_COOKIE["client_id"]);
	$result = mysqli_query($conn,"SELECT * FROM blue_verify_code WHERE client_id='".$id."'");
	if($result->num_rows ==0){//不存在当前服务
		echo 1;
	}else{
		while($row = mysqli_fetch_array($result)){
			if($_POST["code"]==$row["code"]){
				$url = $_POST['url'];
				$result = mysqli_query($conn,"SELECT * FROM blue_urls WHERE url='$url'");
				if($result->num_rows ==0){//不存在当前服务
					// 生成短码
					$shortCode = generateShortCode();
			
					// 插入数据库
					$sql = "INSERT INTO blue_urls (url, shortcode) VALUES (?, ?)";
					// 准备并绑定参数
					$stmt = mysqli_prepare($conn, $sql);
					mysqli_stmt_bind_param($stmt, "ss", $url, $shortCode);
			
					// 执行查询
					if (mysqli_stmt_execute($stmt)) {
						$shortUrl = $config["websiteurl"] . $shortCode;
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error;
					}
				}else{
					while($row = mysqli_fetch_array($result)){
						$shortUrl = $config["websiteurl"] . $row["shortcode"];
					}
				}
			}else{
				echo "<script>alert('图形验证码错误');</script>";
			}
		}

	}


}

// 生成短码函数
function generateShortCode() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $shortCode = '';
    $length = 6;

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = rand(0, strlen($alphabet) - 1);
        $shortCode .= $alphabet[$randomIndex];
    }

    return $shortCode;
}
	if(isset($_REQUEST["mode"])){
		if($_REQUEST["mode"]=="code"){
			
			// 生成或获取客户端标识符
			function getClientIdentifier() {
    			if (isset($_COOKIE['client_id'])) {
        			// 如果Cookie中已存在客户端标识符，则使用它
        			$clientId = $_COOKIE['client_id'];
    			} else {
        			// 生成一个新的客户端标识符
            			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        					$ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        					$clientIP = trim($ipList[0]);
    					}
    					// 判断是否存在HTTP_CLIENT_IP头部并且不为空
    					elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        					$clientIP = $_SERVER['HTTP_CLIENT_IP'];
    					}
    					// 否则直接使用REMOTE_ADDR
    					else {
        					$clientIP = $_SERVER['REMOTE_ADDR'];
    					}
        			$clientId = md5($clientIP.time()); // 替换为您的生成唯一标识符的方法
        			setcookie('client_id', $clientId, time()+3600*24*30); // 设置Cookie的过期时间为30天
    			}
    			return $clientId;
			}

			// 通过客户端标识符进行插入或更新操作
			function insertOrUpdateVerifyCode($clientId, $verifyCode,$conn) {
    			//global $conn;

    			$hashedClientId = md5($clientId);

    			// 准备查询语句，检查验证码是否已存在
    			$sqlExist = "SELECT COUNT(*) FROM blue_verify_code WHERE client_id = ?";
    			$stmtExist = mysqli_prepare($conn, $sqlExist);
    			mysqli_stmt_bind_param($stmtExist, 's', $hashedClientId);
    			mysqli_stmt_execute($stmtExist);
    			mysqli_stmt_bind_result($stmtExist, $count);
    			mysqli_stmt_fetch($stmtExist);
    			mysqli_stmt_close($stmtExist);

    			if ($count > 0) {
        			// 需要执行更新操作
        			$sqlUpdate = "UPDATE blue_verify_code SET code = ?, create_time = NOW() WHERE client_id = ?";
        			$stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
        			mysqli_stmt_bind_param($stmtUpdate, 'ss', $verifyCode, $hashedClientId);
        			$resultUpdate = mysqli_stmt_execute($stmtUpdate);
        			mysqli_stmt_close($stmtUpdate);

        			if ($resultUpdate) {
            			echo "验证码已更新成功！";
        			} else {
            			echo "更新验证码时发生错误，请重试！";
        			}
    			} else {
        			// 执行插入操作
        			$sqlInsert = "INSERT INTO blue_verify_code (code, client_id, create_time) VALUES (?, ?, '".time()."')";
        			$stmtInsert = mysqli_prepare($conn, $sqlInsert);
        			mysqli_stmt_bind_param($stmtInsert, 'ss', $verifyCode, $hashedClientId);
        			$resultInsert = mysqli_stmt_execute($stmtInsert);
        			mysqli_stmt_close($stmtInsert);

        			if ($resultInsert) {
            			echo "验证码已插入成功！";
        			} else {
            			die();
        			}
    			}
			}

// 生成验证码图像
function generateVerifyImage($conn) {
    // 创建验证码图像
    $img = imagecreatetruecolor(120, 40);

    // 分配颜色
    $bgColor = imagecolorallocate($img, 255, 255, 255);

    // 填充背景色
    imagefill($img, 0, 0, $bgColor);

    // 绘制验证码文本
    $verifyCode = '';
    $fontSize = 15;
    $font = './file/arial.ttf';
    for ($i = 0; $i < 5; $i++) {
        $digit = rand(0, 9);
        $verifyCode .= $digit;
        $angle = rand(-20, 20);
        $x = 10 + $i * 25;
        $y = rand(25, 35);
        $textColor = imagecolorallocate($img, rand(0, 100), rand(0, 100), rand(0, 100));
        imagettftext($img, $fontSize, $angle, $x, $y, $textColor, $font, $digit);
    }

    // 绘制随机直线
    for ($i = 0; $i < 5; $i++) {
        $lineColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
        imageline($img, rand(0, 120), rand(0, 40), rand(0, 120), rand(0, 40), $lineColor);
    }

    // 绘制随机干扰斜线
    for ($i = 0; $i < 3; $i++) {
        $lineColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
        $points = array(
            rand(0, 120), rand(0, 40),
            rand(0, 120), rand(0, 40),
            rand(0, 120), rand(0, 40),
            rand(0, 120), rand(0, 40)
        );
        imagepolygon($img, $points, 4, $lineColor);
    }



    // 输出图像
    header('Content-type: image/png');

    imagepng($img);
    imagedestroy($img);
    // 在验证码图像上绘制的验证码文本存储到数据库中
    $clientId = getClientIdentifier();
    insertOrUpdateVerifyCode($clientId, $verifyCode,$conn);

}

// 生成验证码图像
generateVerifyImage($conn);
exit;
		}else{

		}
	}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo $config["websitename"] ?></title>
	<link rel="stylesheet" href="./file/css/style.css">
	<script src="./file/js/jq.js"></script>
</head>



<body>
	<noscript>
		<style>
			#app {
				display: none;
			}

			body {
				margin: 8px
			}
		</style>
		<b>Please start JavaScript to run this website.</b>
	</noscript>
	<div id="app">
		<header class="app_header">
			<a class="app_header_title" href="?" alt="BlueStarNet">
				<text><?php echo $config["websitename"] ?></text>
			</a>
			<div class="app_header_navigate">
				<?php
				foreach ($bluestar->get_nav($conn) as $nav) {
					$url = $nav["url"];
					$name = $nav["name"];
					echo <<<_
					<a class="app_header_navigate_div" href="$url">$name</a>
					_;
				}
				?>
			</div>
			<div class="app_header_spacer"></div>
			<?php
			if ($user_info == false) {
				echo <<<_
				<a class="app_header_login">登录/注册</a>
				_;
			} else {
				$user_email = $user_info["email"];
				echo <<<_
				<a id="app_header_user" href="./?path=page&page=me">$user_email</a>
				_;
			}
			?>
		</header>
			<div>
            <center>
            <h1>网址缩短器</h1>
                <form method="post" action="./?path=page&page=short_url&mode=up">
                    <input type="text" name="url" placeholder="输入长链接" required>
					<iframe src="./?path=page&page=short_url&mode=code" width="200" height="50" frameborder="0"></iframe>
					<input type="number" name="code" placeholder="输入验证码" required>
                    <button type="submit">缩短</button>
                </form>
                <?php if (isset($shortUrl)): ?>
                <p>缩短后的网址：<a href="<?php echo $shortUrl; ?>"><?php echo $shortUrl; ?></a></p>
                <?php endif; ?>
                </center>
			</div>
		
		<footer class="app_footer">
			<p>© <?php
					$d = date("Y");
					if ($d === "2023") {
						echo $d;
					} else {
						echo "2023-" . $d;
					}
					?> StarDreamNet版权所有！！</p>
		</footer>
	</div>
	<?php
	require("./app/page/login.html");
	?>
	<div class="app_msg"></div>

	<script src="./file/js/script.js"></script>
</body>

</html>