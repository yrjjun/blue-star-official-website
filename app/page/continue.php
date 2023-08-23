<?php
    $product = $service[$_REQUEST["id"]];
	if($user_info != false){
		if($user_id==$product["user_id"]){

		}else{
			die("用户鉴权错误！！");
		}
	}else{
		die("请先登录");
	}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo $config["websitename"]; ?> - <?php echo $product['name'];  ?> 商品续费</title>
	<link rel="stylesheet" href="./file/css/style.css">
	<script src="./file/js/jq.js"></script>
	<script src="./file/js/yuan.js"></script>
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

            <h2>
				<?php echo $service[$_REQUEST["id"]]["name"]; ?>
            </h2>
            <p>
                <?php echo $product_info[$product['product_id']]["about"]; ?>
            </p>
            <div>
                <input id="money" style="float:left" type="number" onchange="change(<?php echo $product_info[$product['product_id']]['money']; ?>);"><strong style="float:left"><?php if($product["money_mode"]=="times"){echo $product_info[$product['product_id']]["money_mode"]."（调用次数）";}else{echo $product_info[$product['product_id']]["money_mode"]."（续费月份）";} ?></strong>
            </div>
            金额：<strong style="color:red" id="now_money"></strong>元<br>
            <button class="product_button" onclick="buy(<?php echo $_REQUEST['id'];?>,<?php echo $product['product_id']; ?>)">续费</button>
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