<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php echo $config["websitename"] ?></title>
	<link rel="stylesheet" href="./file/css/style.css">
	<script src="./file/js/jq.js"></script>
	<script>
		$(document).ready(function() {
			bu = '';
			$("button").click(function() {
				$('.cpfl_button').css('background-color', '#B3B3B6');
				$(this).css('background-color', '#0052D9');
				bu = $(this).text();
			});
		});
	</script>
	<style>
		.cpfl_button:hover {
			background-color: #0052D9;
		}

		.cpfl_button {
			background-color: #B3B3B6;
			display: inline-block;
			width: 100%;
			margin: 0px 10px 10px 0px;
			height: 50px;
			padding: 0px 10px;
			border-width: 0;
			color: white;
			font-size: 15px;
		}

		.fl {
			display: flex;
		}

		body {
			text-align: center;
		}
	</style>
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

		<div class="product_from">
		<?php
		foreach ($bluestar->get_product_from($conn) as $from) {
			echo <<<_
      <a href="./?path=page&page=sell&from=$from">
      <button class='cpfl_button'>$from</button>
      </a>
     _;
		}
		?>
		</div>

		<div class="product">
			<?php
			if (isset($_REQUEST["from"])) {
				$select = mysqli_query($conn, "SELECT * FROM `blue_product` WHERE `from`='" . preg_replace('/[^a-zA-Z]/', '', $_REQUEST["from"]) . "'");
				while ($row = mysqli_fetch_assoc($select)) {
					$product_id = $row["id"];
					$product_name = $row["name"];
					$product_about = $row["about"];
					echo '
				  <div ><h4>' . $product_name . '</h4><p>' . $product_about . '</p><a href="./?path=page&page=product_main&id=' . $product_id . '">详情</a></div>
				';
				}
			} else {
				$select = mysqli_query($conn, "SELECT * FROM `blue_product`");
				while ($row = mysqli_fetch_assoc($select)) {
					$product_name = $row["name"];
					$product_about = $row["about"];
					$product_id = $row["id"];
					echo '
            	<div><h4>' . $product_name . '</h4><p>' . $product_about . '</p><a href="./?path=page&page=product_main&id=' . $product_id . '">详情</a></div>
          	';
				}
			}

			?>
		</div>
		<footer class="app_footer">
			<p>&copy; <?php
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