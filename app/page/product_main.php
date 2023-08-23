<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo $config["websitename"]; ?> - <?php echo $product['name'];  ?> 商品购买</title>
	<link rel="stylesheet" href="./file/css/style.css">
	<script src="./file/js/jq.js"></script>
	<script src="./file/js/yuan.js"></script>
	<script>
		function change(money) {
			if ((money * Number($("#money").val())) == 0) {
				$("#now_money").text("0");
				$("#now_xing").text("0");
			} else if ((money * Number($("#money").val())) <= 0.01) {
				$("#now_money").text("0.01");
				$("#now_xing").text("1");
			} else {
				$("#now_money").text(money * Number($("#money").val()));
				$("#now_xing").text(money * Number($("#money").val()) * 100);
			}
		}

		function buy(id) {
			$.get("./?path=product&page=index&id=" + id + "&number=" + $("#money").val(), function(data) {
				if (JSON.parse(data).code == 200) {
					alert("购买成功");
					location.reload();
				} else if (JSON.parse(data).code == 400) {
					alert("余额不足");
				} else if (JSON.parse(data).code == 500) {
					alert("购买失败，此次购买不会消耗金额");
				}
			});
		}
	</script>
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
		<div class="product_shopping">
			<?php
			$product = $product_info[$_REQUEST["id"]];
			?>
			<h2><?php echo $product["name"]; ?></h2>
			<p><?php echo $product["about"]; ?></p>
			<div>
				<input id="money" style="float:left" type="number" onchange="change(<?php echo $product['money'];  ?>);">
				<strong style="float:left">
					<?php
					if ($product["money_mode"] == "times") {
						echo $product["money_mode"] . "（调用次数）";
					} else {
						echo $product["money_mode"] . "（购买月份）";
					}
					?>
				</strong>
				金额：<strong style="color:red" id="now_money">0</strong>元=<strong style="color:red" id="now_xing">0</strong>星梦币
			</div>
			<button class="product_button" onclick="buy(<?php echo $_REQUEST['id']; ?>)">购买</button>
		</div>

		<style>
			.app_footer {
				position: fixed;
				bottom: 0;
				left: 0;
				width: 100%;
				height: 60px;
			}
		</style>
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