<?php
require("../config.php");
$conn = mysqli_connect($host, $user, $password, $dbname);
require("../function.php");
$config = $bluestar->get_config($conn);
if(isset($_REQUEST["key"])){
  if($_REQUEST["key"]==$config["admin"]){
    setcookie("admin_key", md5($config["admin"]), time()+60*60*24*7);
    die("200");
  }
}
if(isset($_COOKIE["admin_key"])){
    if($_COOKIE["admin_key"]==md5($config["admin"])){
      require("./admin/main.php");
    }else{
        $admin_url=$config["admin_url"];
        die(<<<_
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
          <meta charset="UTF-8">
          <title>后台系统</title>
          <script src="../file/js/jQuery.min.js"></script>
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
                        url: "/$admin_url/index.php", // 接收 POST 请求的 URL 地址
                        data: { // POST 请求的数据
                            key: $("#password").val(),
                        },
                        success: function(data) {
                            if(data=="200"){//登录成功
                                alert("登录成功");
                            }else{
                                alert("登录失败");
                            } // 成功时的回调函数，data 为返回的数据
                        },
                        error: function(error) {
                            console.error(error); // 失败时的回调函数，error 为错误信息
                        }
                    });
        
                }
            </script>
          <div id="login">
            <h1>面板登录</h1>
              <label for="password">面板密码：</label>
              <input type="password" name="password" id="password" required>
              <button id="button" onclick="install()">登入</button>
        
          </div>
        </body>
        </html>
        _);
    }
}else{
  $admin_url=$config["admin_url"];
  die(<<<_
  <!DOCTYPE html>
  <html lang="zh-CN">
  <head>
    <meta charset="UTF-8">
    <title>后台系统</title>
    <script src="../file/js/jQuery.min.js"></script>
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
                  url: "/$admin_url/index.php", // 接收 POST 请求的 URL 地址
                  data: { // POST 请求的数据
                      key: $("#password").val(),
                  },
                  success: function(data) {
                      if(data=="200"){//登录成功
                          alert("登录成功");
                      }else{
                          alert("登录失败");
                      } // 成功时的回调函数，data 为返回的数据
                  },
                  error: function(error) {
                      console.error(error); // 失败时的回调函数，error 为错误信息
                  }
              });
  
          }
      </script>
    <div id="login">
      <h1>面板登录</h1>
        <label for="password">面板密码：</label>
        <input type="password" name="password" id="password" required>
        <button id="button" onclick="install()">登入</button>
  
    </div>
  </body>
  </html>
  _);
}
?>