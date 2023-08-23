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
$isadmin = strpos($user_info["status"], "developer") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问developer功能", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
?>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<link rel="stylesheet" href="../../file/css/admin.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>开发人员管理</h2>
    <p>管理团队的开发人员</p>
    <div class="mdui-toolbar mdui-m-b-2">
        <div class="mdui-toolbar-spacer"></div>
        <button class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">add</i>
        </button>
        <button class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">create</i>
        </button>
        <button class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">delete</i>
        </button>
    </div>
</div>
<script>
    ! function() {
        setInterval(() => {
            if (document.body.scrollTop > 166) {
                document.body.classList.add("fixed")
            } else {
                document.body.classList.remove("fixed")
            }
        });
        fetch("../?type=func&target=get_developer", {
            method: "POST"
        }).then(v => v.json()).then(v => {
            var data = Object.values(v.data);
            data.forEach(v => {
                var s = mdui.$(`<div class="mdui-card">
        <div class="mdui-card-media">
            <div class="mdui-card-menu"><label class="mdui-checkbox"><input type="checkbox" /><i class="mdui-checkbox-icon"></i></label>
            </div>
        </div>
        <div class="mdui-card-primary">
            <img class="mdui-card-header-avatar" style="width:55px;height:55px;margin-right:12px;" src="${v.img}" />
            <div class="mdui-card-primary-title">${v.name}</div>
            <div class="mdui-card-primary-subtitle">${v.about}</div>
        </div>
    </div>`);
                s[0]._id = v.id;
                s[0]._url = v.url;
                s[0]._show = v.status;
                s.appendTo(mdui.$(".mdui-p-a-5")[0])
                mdui.mutation();
                mdui.$(".mdui-checkbox input").on("change", function() {
                    arrange();
                });
            });

            function arrange() {
                var x = mdui.$(".mdui-checkbox input:checked");
                if (x.length === 0) {
                    mdui.$("div>button:nth-child(3)").attr("disabled", "");
                    mdui.$("div>button:nth-child(4)").attr("disabled", "");
                } else if (x.length < 2) {
                    mdui.$("div>button:nth-child(3)").removeAttr("disabled");
                    mdui.$("div>button:nth-child(4)").removeAttr("disabled", "");
                } else {
                    mdui.$("div>button:nth-child(3)").attr("disabled", "");
                    mdui.$("div>button:nth-child(4)").removeAttr("disabled", "");
                }
            };
            mdui.$("div>button:nth-child(2)").on("click", function() {
                window.top.writeHtml(
                    `<div class="mdui-dialog">
    <div class="mdui-dialog-title">添加</div>
    <div class="mdui-dialog-content">
        <div class="mdui-textfield mdui-textfield-floating-label"><label class="mdui-textfield-label">名称</label><input class="mdui-textfield-input" /></div>
        <div class="mdui-textfield mdui-textfield-floating-label"><label class="mdui-textfield-label">描述</label><input class="mdui-textfield-input" /></div>
        <div class="mdui-textfield mdui-textfield-floating-label"><label class="mdui-textfield-label">头像</label><input class="mdui-textfield-input" /></div>
    </div>
    <div class="mdui-dialog-actions"><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">关闭</a><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">添加</a>
    </div>
</div>`,
                    function(p) {
                        var is_click = false;
                        var x = new p.w.mdui.Dialog(p.jq[0]);
                        var input = p.jq.find("input")
                        x.open();
                        mdui.$(p.jq.find(".mdui-dialog-actions>*")[1]).on("click", function() {
                            is_click = true;
                            x.close();
                        });
                        mdui.$(p.jq.find(".mdui-dialog-actions>*")[0]).on("click", function() {
                            x.close();
                        });
                        var s = setInterval(() => {
                            if (x.state == "closed") {
                                p.jq.remove();
                                if (is_click) {
                                    if (input[0].value) {
                                        mdui.$.ajax({
                                            url: "../?type=func&target=add_friend_url",
                                            method: "POST",
                                            data: {
                                                name: input[0].value,
                                                about: input[1].value,
                                                img: input[2].value,
                                                url: input[3].value,
                                                show: 1
                                            },
                                            success: function() {
                                                location.reload();
                                            }
                                        })
                                    }
                                }
                                clearInterval(s);
                            }
                        });
                    }
                )
            });
            mdui.$("div>button:nth-child(3)").on("click", function() {
                window.top.writeHtml(
                    `<div class="mdui-dialog">
    <div class="mdui-dialog-title">编辑</div>
    <div class="mdui-dialog-content">
        <div class="mdui-textfield mdui-textfield-floating-label"><label class="mdui-textfield-label">名称</label><input class="mdui-textfield-input" /></div>
        <div class="mdui-textfield mdui-textfield-floating-label"><label class="mdui-textfield-label">描述</label><input class="mdui-textfield-input" /></div>
        <div class="mdui-textfield mdui-textfield-floating-label"><label class="mdui-textfield-label">头像</label><input class="mdui-textfield-input" /></div>
        <div class="mdui-textfield mdui-textfield-floating-label"><label class="mdui-textfield-label">链接</label><input class="mdui-textfield-input" /></div>
        <label class="mdui-checkbox">
  <input type="checkbox"/>
  <i class="mdui-checkbox-icon"></i>
  展示到主页
</label>
    </div>
    <div class="mdui-dialog-actions"><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">关闭</a><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">保存</a>
    </div>
</div>`,
                    function(p) {
                        var main = mdui.$(mdui.$(".mdui-checkbox input:checked")[0].parentElement.parentElement.parentElement.parentElement)
                        var id = main[0]._id;
                        var name = main.find(".mdui-card-primary-title").text()
                        var about = main.find(".mdui-card-primary-subtitle").text();
                        var img = main.find(".mdui-card-header-avatar").attr("src");
                        var url = main[0]._url;
                        var status = main[0]._show;
                        var is_click = false;
                        var x = new p.w.mdui.Dialog(p.jq[0]);
                        var input = p.jq.find("input")
                        x.open();
                        input[0].value = name;
                        input[1].value = about;
                        input[2].value = img;
                        input[3].value = url;
                        input[4].checked = status == 1;
                        p.w.mdui.updateTextFields()
                        mdui.$(p.jq.find(".mdui-dialog-actions>*")[1]).on("click", function() {
                            is_click = true;
                            x.close();
                        });
                        mdui.$(p.jq.find(".mdui-dialog-actions>*")[0]).on("click", function() {
                            x.close();
                        });
                        var s = setInterval(() => {
                            if (x.state == "closed") {
                                p.jq.remove();
                                if (is_click) {
                                    if (input[0].value) {
                                        mdui.$.ajax({
                                            url: "../?type=func&target=change_friend_url",
                                            method: "POST",
                                            data: {
                                                id,
                                                name: input[0].value,
                                                about: input[1].value,
                                                img: input[2].value,
                                                url: input[3].value,
                                                show: input[4].checked + 0,
                                            },
                                            success: function() {
                                                location.reload();
                                            }
                                        })
                                    }
                                }
                                clearInterval(s);
                            }
                        });
                    }
                )
            })
            mdui.$("div>button:nth-child(4)").on("click", function() {
                window.top.mdui.confirm("删除后不可恢复，确认继续？", "删除", function() {
                    var all = mdui.$(".mdui-checkbox input:checked").map((i, v) => mdui.$(v.parentElement.parentElement.parentElement.parentElement));
                    var deleteCount = all.length;
                    all.each((id, e) => {
                        mdui.$.ajax({
                            url: "../?type=func&target=delete_friend_url",
                            method: "POST",
                            data: {
                                id: e[0]._id
                            },
                            success: function() {
                                deleteCount--;
                            }
                        })
                    });
                    var x = setInterval(() => {
                        if (deleteCount <= 0) {
                            location.reload();
                            clearInterval(x)
                        };
                    });
                });
            });
            arrange();
        });
    }();
</script>