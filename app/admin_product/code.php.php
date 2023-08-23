<!DOCTYPE html>
<html>
<head>
<title>Email-CODE API文档</title>
<meta charset="utf-8">
<style>
body {
font-family: Arial, sans-serif;
margin: 0;
padding: 0;
}

	header {
		background-color: #F8F8F8;
		padding: 20px;
	}

	header h1 {
		margin: 0;
		font-size: 36px;
		color: #333;
	}

	main {
		padding: 20px;
		max-width: 900px;
		margin: 0 auto;
	}

	section {
		margin-bottom: 40px;
	}

	h2 {
		margin-top: 0;
		margin-bottom: 10px;
		font-size: 24px;
		font-weight: normal;
		color: #333;
	}

	table {
		width: 100%;
		border-collapse: collapse;
		margin-top: 10px;
	}

	th, td {
		border: 1px solid #ccc;
		padding: 10px;
		text-align: left;
		vertical-align: top;
	}

	pre {
		background-color: #F8F8F8;
		padding: 10px;
		overflow-x: auto;
	}

	code {
		font-family: Consolas, monospace;
		font-size: 14px;
		color: #333;
	}

	footer {
		background-color: #F8F8F8;
		padding: 10px;
		text-align: center;
		font-size: 14px;
		color: #333;
	}
</style>
</head>
<body>
<header>
<h1>Email-CODE API文档</h1>
</header>

<main>
	<section>
		<h2>请求地址</h2>
		<p><?php echo $config["websiteurl"]; ?>api/code.php</p>
	</section>

	<section>
		<h2>请求方式</h2>
		<p>POST</p>
	</section>

	<section>
		<h2>发送验证码·请求参数</h2>
		<table>
			<thead>
				<tr>
					<th>参数</th>
					<th>类型</th>
					<th>必填</th>
					<th>描述</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>mode</td>
					<td>string</td>
					<td>是</td>
					<td>填写send</td>
				</tr>
				<tr>
					<td>appid</td>
					<td>string</td>
					<td>是</td>
					<td>api的ID</td>
				</tr>
				<tr>
					<td>to</td>
					<td>string</td>
					<td>是</td>
					<td>验证码收件人</td>
				</tr>
				<tr>
					<td>key</td>
					<td>string</td>
					<td>是</td>
					<td>hash("sha256",{to的值}.{api的token})</td>
				</tr>
			</tbody>
		</table>
	</section>

	<section>
		<h2>返回成功示例</h2>
		<pre><code>{
			"code": 200
		}</code></pre>
	</section>
	<section>
		<h2>失败返回值对照</h2>
		<table>
			<thead>
				<tr>
					<th>状态码</th>
					<th>描述</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>403</td>
					<td>缺少关键参数</td>
				</tr>
				<tr>
					<td>404</td>
					<td>没有当前appid</td>
				</tr>
				<tr>
					<td>401</td>
					<td>key错误</td>
				</tr>
			</tbody>
		</table>
	</section>
	<section>
		<h2>验证验证码·请求参数</h2>
		<table>
			<thead>
				<tr>
					<th>参数</th>
					<th>类型</th>
					<th>必填</th>
					<th>描述</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>to</td>
					<td>string</td>
					<td>是</td>
					<td>验证码收件人</td>
				</tr>
				<tr>
					<td>code</td>
					<td>int</td>
					<td>是</td>
					<td>用户输入的验证码</td>
				</tr>
			</tbody>
		</table>
	</section>

	<section>
		<h2>返回成功示例</h2>
		<pre><code>{
			"code": 200
		}</code></pre>
	</section>

	<section>
		<h2>失败返回值对照</h2>
		<table>
			<thead>
				<tr>
					<th>状态码</th>
					<th>描述</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>401</td>
					<td>未找到当前邮箱</td>
				</tr>
				<tr>
					<td>402</td>
					<td>验证码已超时</td>
				</tr>
				<tr>
					<td>404</td>
					<td>验证码错误</td>
				</tr>
			</tbody>
		</table>
	</section>
</main>

<footer>
	<p>文档编写日期：2023-06-13</p>
</footer>
</body>
</html>