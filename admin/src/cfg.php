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
$isadmin = strpos($user_info["status"], "cfg") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问cfg功能", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
?>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>官网信息</h2>
    <p>官网信息配置</p>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">网站名</label>
        <input class="mdui-textfield-input name" />
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">简介</label>
        <textarea class="mdui-textfield-input"></textarea>
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">官网链接</label>
        <input class="mdui-textfield-input url"></input>
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">官网域名</label>
        <input class="mdui-textfield-input domain"></input>
    </div>

    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">必应提交网址秘钥内容</label>
        <input class="mdui-textfield-input key"></input>
    </div>
    <div class="mdui-textfield mdui-textfield-floating-label">
        <label class="mdui-textfield-label">QQ群邀请链接</label>
        <input class="mdui-textfield-input qq"></input>
    </div>
    <button class="mdui-btn mdui-ripple mdui-btn-raised mdui-color-theme-accent mdui-m-t-4">保存更改</button>
</div>
<script>
    ! function() {
        fetch("../?type=func&target=get_config", {
            method: "POST"
        }).then(v => v.json()).then(v => {
            mdui.$("input.name").val(v.data.websitename);
            mdui.$("textarea").val(v.data.about.split("<br>").join("\n"));
            mdui.$("input.qq").val(v.data.qqgroup);
            mdui.$("input.url").val(v.data.websiteurl);
            mdui.$("input.domain").val(v.data.domain);
            mdui.$("input.key_url").val(v.data.bing_key_url);
            mdui.$("input.key").val(v.data.bing_key);
            mdui.updateTextFields()
            mdui.$("button").on("click", function() {
                mdui.$.ajax({
                    url: "../?type=func&target=set_config_about",
                    method: "POST",
                    data: {
                        value: mdui.$("textarea").val().split("\n").join("<br>")
                    },
                    success: function() {
                        mdui.$.ajax({
                            url: "../?type=func&target=set_config_websitename",
                            method: "POST",
                            data: {
                                value: mdui.$("input.name").val()
                            },
                            success: function() {
                                mdui.$.ajax({
                                    url: "../?type=func&target=set_config_qqg",
                                    method: "POST",
                                    data: {
                                        value: mdui.$("input.qq").val()
                                    },
                                    success: function() {
                                        mdui.$.ajax({
                                            url: "../?type=func&target=set_config_websiteurl",
                                            method: "POST",
                                            data: {
                                                value: mdui.$("input.url").val()
                                            },
                                            success: function() {
                                                mdui.$.ajax({
                                                url: "../?type=func&target=set_config_domain",
                                                method: "POST",
                                                data: {
                                                    value: mdui.$("input.domain").val()
                                                },
                                                success: function() {
                                                    window.has_change = null;
                                                    window.top.mdui.snackbar("保存成功", {
                                                        position: "left-bottom"
                                                    })
                                                }})
                                                mdui.$.ajax({
                                                url: "../?type=func&target=set_config_key",
                                                method: "POST",
                                                data: {
                                                    value: mdui.$("input.key").val()
                                                },
                                                success: function() {
                                                    window.has_change = null;
                                                    window.top.mdui.snackbar("保存成功", {
                                                        position: "left-bottom"
                                                    })
                                                }
                                                })
                                            }
                                        })
                                    }
                                })
                            }
                        })
                    }
                })
            })
        })
        mdui.$("input").on("input", function() {
                window.has_change = () => true;
            });
            mdui.$("textarea").on("input", function() {
                window.has_change = () => true;
            });
    }();
</script>