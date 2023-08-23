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
			td {
  				border: 1px solid black;
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
		<div class="app_content">
			<?php
			if ($user_info == false) {
			} else {
				$user_email = $user_info["email"];
				echo <<<_
				<button style="margin-top:50px" class="exit_login">退出登录</button>
				<script>
					$(".exit_login").on("click", function() {
						$.get("./?path=function&page=exit", function(data) {
							location.href = "?path=page&page=index"
						});
					})
				</script>
				<button style="margin-top:50px" class="pay">充值</button>
				<script>
					$(".pay").on("click", function() {
						window.location.href = './?path=easypay&page=index';
					})
				</script>
				_;
			}
			?>
		</div>
		<div>
			<h2>欢迎回来，<?php echo $user_info["name"];?></h2>
			<h4>我的服务</h4>
			<?php
			$user_id = $user_info["id"];
				$result = mysqli_query($conn,"SELECT * FROM blue_service WHERE user_id='$user_id'");
				echo "<table>
				<thead>
					<tr>
						<th>商品名</th>
						<th>appid</th>
						<th>token</th>
						<th>文档</th>
						<th>操作</th>
						<th>余额</th>
					</tr>
				</thead>
				<tbody>
				";
				while($row = mysqli_fetch_array($result)){
					echo '
						<tr>
							<td>'.$row["name"].'</td>
							<td>'.$row["appid"].'</td>
							<td>'.$row["token"].'</td>
							<td><a href="./?path=admin_product&page='.$row["admin"].'">文档</a></td>
							<td><a href="./?path=page&product_id='.$row["product_id"].'&page=continue&id='.$row["id"].'">续费</a></td>
							<td>'.$row["times"].'</td>
							</tr>
					';
				}
				echo "</tbody>";
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