<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" name="viewport"
    content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>
    管理界面
  </title>
  <!-- 新 Bootstrap 核心 CSS 文件 -->
  <link href="../file/css/bootstrap.min.css" rel="stylesheet">

  <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
  <script src="../file/js/jquery_2.1.1_jquery.min.js"></script>

  <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
  <script src="../file/js/bootstrap.min.js"></script>
  <script src="../file/js/jquery_2.1.4_jquery.min.js"></script>


  <script>

    $(document).ready(function () {


    });

  </script>
  <style>
    .nav {
      position: fixed;
      top: 0px;
      z-index: 1;
      color: black;
      background-color: white;
      width: 100%;
      height: 60px;
      padding: 5px;
      box-shadow: 4px 4px 15px grey;
      display: flex;
      align-items: center;
    }

    .nav a {
      color: black;
      margin-right: 12.5vh;
      text-align: center;
    }

    .nav big {
      margin-right: 15vh;
      margin-left: 7.5vh;
    }

    html,
    body {
      width: 100%;
      height: 100%;
    }

    body {
      background-color: #97bfff;
    }

    a {
      text-decoration: none;
    }

    * {
      margin: 0px;
    }

    .png {
      width: 100%;
      height: 600px;
      /*text-align:center;*/
    }

    .png button {
      background-color: #0052D9;
      color: white;
      width: 100px;
      height: 40px;
      border-width: 0;
    }

    .buu {
      background-color: #0052D9;
      color: white;
      width: 70px;
      height: 40px;
      border-width: 0;
    }

    .png span {
      position: absolute;
      top: 61px;
      width: 100%;
      height: 600px;
      /*background-image: url("hero2-bg-pc1_160.jpg");
    background-size:cover;*/
      background-repeat: no-repeat;
      position: flex;
      z-index: -1;
      background-color: #97bfff;
    }

    .blocka {
      border-radius: 10px;
      width: 100%;
      height: 150px;
      display: inline-block;
      background-color: white;
      margin: 10px;
      text-align: center;
      box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.6), -5px -5px 10px rgba(255, 255, 255, 0.5);
      background: linear-gradient(180deg, #FFFFFF 0%, #D6DBDC 100%);
      border: 2px solid #D6DBDC;
      /*box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.15);*/
      transition: all 0.3s ease-in-out;
    }

    .da {
      display: flex;
    }

    @media screen and (max-width: 930px) {
      .blocka {
        border-radius: 10px;
        width: 100%;
        height: 150px;
        display: block;
        background-color: white;
        margin: 10px;
        text-align: center;
      }

      .da {
        display: block;
      }
    }

  </style>
</head>

<body>
  <div class="nav">
    <br><b>
      <big>
        管理界面
      </big>
      <a href="./index.php?page=user">用户管理</a>
      <a href="./index.php?page=nav">导航栏管理</a>
      <a href="./index.php?page=product">产品管理</a>
      <a href="./index.php?page=from">产品分组管理</a>
    </b>
  </div>
  <div style="text-align:center;background-color:grey;position:fixed;bottom:0px;width:100%;z-index:1;">
    <p>&copy;2023-现在 blue star-net版权所有！</p>
  </div>
    <?php
      if(isset($_REQUEST["page"])){
        
      }

    ?>
</body>

</html>