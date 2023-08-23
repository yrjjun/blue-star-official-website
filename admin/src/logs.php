<?php
require("../../config.php");
	// 检查是否使用了代理服务器
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
$conn = mysqli_connect($host, $user, $password, $dbname);
require("../../function.php");
$config = $bluestar->get_config($conn);
$user_info = $bluestar->detection($conn);
$isadmin = strpos($user_info["status"], "admin") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问admin面板", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
?>

<body class="mdui-appbar-with-toolbar"></body>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<link rel="stylesheet" href="../../file/css/admin.css">
<script src="../../file/js/mdui.min.js"></script>
<style></style>
<div class="mdui-appbar mdui-appbar-fixed">
    <div class="mdui-toolbar mdui-color-grey-100">
        <button class="mdui-btn mdui-ripple b1">隐藏IP</button>
        <button class="mdui-btn mdui-ripple b2">显示IP</button>
        <button class="mdui-btn mdui-ripple b3">加载IP属地</button>
    </div>
</div>
<div class="mdui-typo mdui-p-a-2"></div>
<p class="load">加载中 0/100</p>
<script>
    ! function() {
        function tos(v) {
            if (!v) return ``;
            return `[ ${new Date(((v.time * 1) + (60 * 60 * 8)) * 1000).toISOString()} ] [ ${v.code} ] ${v.text} 用户：${v.user === "free" ? "未登录" : (v.user === "" ? "无记录" : v.user)} IP：<ip>${v.ip === "" ? "无记录" : v.ip}</ip>`
        };

        fetch("../?type=func&target=get_logs", {
            method: "POST"
        }).then(v => v.json()).then(v => {
            var data = v.data;
            var d = 0;
            var i = setInterval(() => {
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                var x = document.createElement("p");
                x.innerHTML = tos(data[d]);
                document.querySelector(".mdui-typo").append(x);
                d++;
                document.querySelector(".load").innerText = `加载中 ${d}/${data.length}`;
                document.body.scrollTop = document.body.scrollHeight;
                if (d >= data.length) {
                    document.querySelector(".load").innerText = ``;
                    mdui.$(".b1").on("click",function(){
                        mdui.$("style").html("ip{filter:blur(4px)}")
                    })
                    mdui.$(".b2").on("click",function(){
                        mdui.$("style").html("")
                    })
                    mdui.$(".b3").on("click", function() {
                        this.disabled = true;
                        var all = document.querySelectorAll("ip");
                        var x = [].slice.call(all).map(v => v.innerText).filter((v, i, a) => a.indexOf(v) === i)
                        var i = 0;
                        var t = false;
                        var loop = setInterval(() => {
                            this.innerText = `${i} / ${x.length}`
                            if (i >= x.length - 1) {
                                clearInterval(loop);
                                this.innerText = `IP属地加载完毕`
                            }
                            if (t) return
                            t = true;
                            var v = x[i];
                            fetch(`http://ip-api.com/json/${v}?lang=zh-CN`).then(v => v.json()).then(v3 => {
                                i++;
                                t = false;
                                [].slice.call(all).filter(v2 => v2.innerText == v).forEach(e => {
                                    e.innerText += ` (${v3.regionName})`
                                })
                            }, () => {
                                t = false;
                            })
                        }, 200)
                    })
                    clearInterval(i);
                };
            }, .1)
        })
    }()
</script>