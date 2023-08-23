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
				$bluestar->get_function($conn,2);
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
			<div class="app_header_photodroplistbutton">
				<div class="app_header_photodroplistbutton_line"></div>
				<div class="app_header_photodroplistbutton_line"></div>
				<div class="app_header_photodroplistbutton_line"></div>
			</div>
			<div class="app_header_photodroplist">
				<?php
				foreach ($bluestar->get_nav($conn) as $nav) {
					$url = $nav["url"];
					$name = $nav["name"];
					echo <<<_
					<a class="app_header_photodroplist_c" href="$url">$name</a>
					_;
				}
				?>
				<a class="app_header_photodroplist_line"></a>
			<?php
			if ($user_info == false) {
				echo <<<_
				<a class="app_header_photodroplist_c app_header_login">登录/注册</a>
				_;
			} else {
				$user_email = $user_info["email"];
				echo <<<_
				<a class="app_header_photodroplist_c" href="./?path=page&page=me">$user_email</a>
				_;
			}
			?>
			</div>
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
		<div class="app_content">
			<div class="app_content_announcement">
				<div class="app_content_announcement_title">上云精选</div>
				<div class="app_content_announcement_contents">618特惠主机<br>超大存储空间，超多流量只要0元</div>
				<div class="app_content_announcement_buttons">
					<a href="http://vhost.bluestarnet.top/">立即选购</a>
				</div>
			</div>
			<div class="app_content_product">
				<?php
				foreach ($bluestar->get_product_card($conn) as $card) {
					$about = $card[1];
					$name = $card[0];
					echo <<<_
					<div class="app_content_product_card">
						<div class="app_content_product_card_title">$name</div>
						<div class="app_content_product_card_content">$about</div>
					</div>
					_;
				}
				?>
			</div>


			<div class="app_content_subfield">
				<div class="app_content_subfield_title">关于我们</div>
				<div class="app_content_subfield_content"><?php echo $config["about"] ?></div>
			</div>
			<div class="app_content_subfield">
				<div class="app_content_subfield_title">发展日程</div>
				<div class="app_content_subfield_content">
					<div class="app_content_dynamic">
						<div class="app_content_dynamic_left">
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">2023-05-18</div>
								<div class="app_content_dynamic_content_content">官网大更新</div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">2023-05-31</div>
								<div class="app_content_dynamic_content_content">上线产品购买页面</div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">2023-06-11</div>
								<div class="app_content_dynamic_content_content">与轩宇、幻梦合并，改名为 StarDreamNet</div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">2023-06-15</div>
								<div class="app_content_dynamic_content_content">上线支付功能，可以充值购买产品啦</div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
						</div>
						<div class="app_content_dynamic_center">
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
							<div class="app_content_dynamic_center_dot"></div>
							<div class="app_content_dynamic_center_air"></div>
						</div>
						<div class="app_content_dynamic_right">
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">2023-03-03</div>
								<div class="app_content_dynamic_content_content">BlueStarNet 正式成立</div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">2023-05-27</div>
								<div class="app_content_dynamic_content_content">修复高危漏洞</div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">2023-06-01</div>
								<div class="app_content_dynamic_content_content">上线邮件代发API</div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">2023-06-13</div>
								<div class="app_content_dynamic_content_content">上线邮箱验证码API</div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title"></div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
							<div class="app_content_dynamic_content">
								<div class="app_content_dynamic_content_title">未来可期</div>
								<div class="app_content_dynamic_content_content"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="app_content_subfield">
				<div class="app_content_subfield_title">核心人员</div>
				<div class="app_content_subfield_content">
					<?php
					foreach ($team_members as $members) {
						$about = $members["about"];
						$name = $members["name"];
						$img_url = $members["img_url"];
						echo <<<_
								<div class="app_content_subfield_content_card">
									<div class="app_content_subfield_content_card_img"><img src="$img_url"></div>
									<div class="app_content_subfield_content_card_title">$name</div>
									<div class="app_content_subfield_content_card_content">$about</div>
								</div>
								_;
					}
					?>
				</div>
			</div>
		</div>
		<div class="friend_links">
			<span>友情链接：</span>
			<?php
			$friend = array();
			$select = mysqli_query($conn, "SELECT * FROM `blue_friendurl`");
			while ($row = mysqli_fetch_assoc($select)) {
				$friend[$row["name"]] = array("name" => $row["name"], "url" => $row["url"], "status" => $row["status"]);
			}
			foreach ($friend  as $members) {
				$name = $members["name"];
				$url = $members["url"];
				$status = $members["status"];
				if ($status == 1) {
					echo <<<_
				<a href="$url" target="_blank">$name</a>
				_;
				}
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
					?> StarDreamNet版权所有！</p>
		</footer>
	</div>
	<?php
	require("./app/page/login.html");
	?>
	<div class="app_msg"></div>
	<script src="./file/js/script.js"></script>

</body>

</html>