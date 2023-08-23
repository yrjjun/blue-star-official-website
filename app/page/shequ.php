<?php
$bluestar->get_function($conn,12);
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
				<?php
					if(isset($_GET["p"])){
						$p=$_GET["p"];
					}else{
						die('<script>window.location.href = "./?path=page&page=shequ&p=1";</script>');
					}
					foreach ($bluestar->get_shequ($conn,$p) as $shequ) {
						$id = $shequ["url"];
						$user_id = $shequ["user_id"];
						$title = $shequ["title"];
						$text = $shequ["text"];
						
						$time = $shequ["time"];
						$ip = $shequ["ip"];
						$goods = $shequ["goods"];
						$views = $shequ["views"];
						$likes = $shequ["likes"];
						$ishot = $shequ["ishot"];
						$ison = $shequ["ison"];
						$isbest = $shequ["isbest"];
						$user_info_email = $bluestar->get_user_info($conn)[$user_id];
						echo <<<_
							<div class="shequ_div">
								<h3>$title</h3>
								<p>来自-$user_info_email</p>
								<p class="shequ_text">$text</p>
								<div>
									<p>浏览数：$views</p>
									<p>点赞数：$goods</p>
									<p>喜欢：$likes</p>
									<a>详情</a>
								</div>

							</div>
						_;
					}
					for ($i = 1; $i < $bluestar->get_shequ_pages($conn)+1; $i++) {
						echo '<a href="./?path=page&page=shequ&p='.$i.'">第'.$i.'页</a>';
					}
				?>
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