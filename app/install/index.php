<?php
if(isset($status)){

}else{
    die("不受允许的访问");
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>安装系统</title>
  <script src="./file/js/jQuery.min.js"></script>
  <script src="./file/js/pickduck.js"></script>
  <link href="./file/css/pickduck.css" rel="stylesheet">
  <style>
    #login {
      width: 400px;
      margin: 100px auto;
      background-color: #fff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* 添加阴影 */
    }

    input{
      display: block;
      width: 100%;
      margin-bottom: 20px;
      padding: 10px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* 添加轻微阴影 */
    }

    #button{
      display: block;
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 5px;
      background-color: #4CAF50;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
    }

    #button:hover {
      background-color: #3E8E41;
    }
  </style>
</head>
<body>
    <script>
        function install(){
            $.post({
                url: "./api.php", // 接收 POST 请求的 URL 地址
                data: { // POST 请求的数据
                    key: $("#password").val(),
                    mode: "install",
                    smtp_host: $("#smtp_host").val(),
                    smtp_email: $("#smtp_email").val(),
                    smtp_port: $("#port").val(),
                    smtp_password: $("#smtp_password").val(),
                    websitename: $("#websitename").val(),
                    shequ_url: $("#shequ_url").val(),
                    db_host: $("#db_host").val(),
                    db_user: $("#db_user").val(),
                    db_password: $("#db_password").val(),
                    db_name: $("#db_name").val(),
                    admin_url:$("#admin_url").val(),
                    websiteurl:$('#websiteurl').val(),
                },
                success: function(data) {
                    if(data=="200"){//登录成功
                        alert("安装成功");
                    }else{
                        alert("安装失败");
                    } // 成功时的回调函数，data 为返回的数据
                },
                error: function(error) {
                    console.error(error); // 失败时的回调函数，error 为错误信息
                }
            });

        }
    </script>
  <div id="login">
    <h1>面板安装</h1>
    <?php 
    // 获取协议
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    // 获取主机名
    $host = $_SERVER['HTTP_HOST'];
    // 获取当前请求 URI
    $uri = $_SERVER['REQUEST_URI'];

?>
      <label for="websiteurl">网站URL：</label>
      <input  name="websiteurl" id="websiteurl" value="<?php echo $protocol."://".$host;?>" required>
    <label for="db_host">数据库地址：</label>
      <input value="localhost" name="db_host" id="db_host" required>
      <label for="db_user">数据库用户名：</label>
      <input  name="db_user" id="db_user" required>
      <label for="db_password">数据库密码：</label>
      <input type="password" name="db_password" id="db_password" required>
      <label for="db_name">数据库名：</label>
      <input  name="db_name" id="db_name" required>
      <label for="password">面板密码：</label>
      <input type="password" name="password" id="password" required>
      <label for="smtp_host">SMTP HOST：</label>
      <input  name="smtp_host" id="smtp_host" required>
      <label for="smtp_email">SMTP EMAIL：</label>
      <input type="email" name="smtp_email" id="smtp_email" required>
      <label for="port">SMTP PORT：</label>
      <input  name="port" id="port" required>
      <label for="smtp_password">SMTP PASSWORD：</label>
      <input type="password" name="smtp_password" id="smtp_password" required>
      <label for="websitename">团队名：</label>
      <input  name="websitename" id="websitename" required>
      <label for="shequ_url">社区网址：</label>
      <input  name="shequ_url" id="shequ_url" required>
      <button id="button" onclick="install()">安装</button>

  </div>
  <div style="display:none;" id='modal'>
<!--背景虚化-->
<div class="alert-modal">
  <!--静态框主体-->
  <div class="modal" id='mainmodal'>
    <!--内容-->
    <div class="text-modal" style="overflow: auto;text-overflow:ellipsis;">
      <p id="title">标题</p>
      <span id="text" style="white-space: pre-line;">主体内容</span>
     
        <input type="text" class="input-text" id="textplay"  placeholder="请输入内容..." style="display:none;padding: 0px 0px 0px 10px;">
    </div>
    <!--底部按钮-->
    <div class="button-modal">
      <div class="left-button">
        <a id="a-left"><button class="modal-button" id="text-left" onclick="pimodal();">了解更多</button></a>
      </div>
      <div class="right-button">
        <button class="modal-button modal-button--cancel"  onclick="pimodal();" id="text-right2">取消</button>
        <a id="a-right" ><button class="modal-button modal-button-confirm" onclick="pimodal();" id="text-right">积极按钮</button></a>
        <a onclick="" id="text-right-form-s" style="display:none"><button class="modal-button modal-button-confirm"  onclick="pimodal();" >提交</button></a>
      </div>
    </div>
  </div>
 </div>
</div>
</body>
</html>

