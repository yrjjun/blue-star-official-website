<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $config["websitename"] ?> - 虚拟主机</title>
    <link rel="stylesheet" href="./file/css/style.css">
    <link rel="stylesheet" href="./file/css/host.css">
    <script src="./file/js/jq.js"></script>
    <script>
        function buy(){
            
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
        <div class="app_content">
            <div class="app_content_left">
                <p>母机</p>
            </div>
            <div class="app_content_right">
                <div class="app_content_right_top">
                    <?php
                    $select = mysqli_query($conn, "SELECT * FROM `blue_vhost_vps`");
                    while ($row = mysqli_fetch_assoc($select)) {
                        $id = $row["id"];
                        $name = $row["name"];
                        echo <<<_
                            <div class="app_content_right_top_c" data-id="$id">$name</div>
                        _;
                    }
                    ?>
                </div>
                <div class="app_content_right_bottom">
                    <div class="app_content_right_bottom_input">
                        <p>存储空间</p>
                        <div>
                            <input>
                            <unit>MB</unit>
                        </div>
                    </div>
                    <div class="app_content_right_bottom_input">
                        <p>数据库</p>
                        <div>
                            <input>
                        </div>
                    </div>
                    <div class="app_content_right_bottom_input">
                        <p>流量大小</p>
                        <div>
                            <input>
                            <unit>GB</unit>
                        </div>
                    </div>
                    <div class="app_content_right_bottom_input">
                        <p>IP绑定数</p>
                        <div>
                            <input>
                            <unit>个</unit>
                        </div>
                    </div>
                    <div class="app_content_right_bottom_input">
                        <p>子目录数</p>
                        <div>
                            <input>
                            <unit>个</unit>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app_content_fixed">
                <div class="app_content_fixed_content">
                    <div class="app_content_fixed_content_left">
                        <div class="app_content_fixed_content_left_top">配置</div>
                        <div class="app_content_fixed_content_left_bottom">
                            <div class="app_content_fixed_content_left_bottom_cont">
                                <p>时长</p>
                                <select>
                                    <option selected>1个月</option>
                                    <option>2个月</option>
                                    <option>3个月</option>
                                    <option>4个月</option>
                                    <option>5个月</option>
                                    <option>6个月</option>
                                    <option>12个月</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="app_content_fixed_content_right">
                        <div class="app_content_fixed_content_right_left">
                            <p>配置费用</p>
                            <p class="big">￥</p>
                            <p></p>
                        </div>
                        <div class="app_content_fixed_content_right_right">
                            <button>下一步！</button>
                        </div>
                    </div>
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
            foreach ($friend as $members) {
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