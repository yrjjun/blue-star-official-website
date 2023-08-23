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
$isadmin = strpos($user_info["status"], "user") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问user功能", "", $ip, time(), "200");
    header('Location: ../');
    exit;
}
?>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<link rel="stylesheet" href="../../file/css/admin.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>用户管理</h2>
    <p>管理平台上的用户</p>
    <div class="mdui-toolbar mdui-m-b-2">
        <div class="mdui-toolbar-spacer"></div>
        <button class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">create</i>
        </button>
        <button class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">delete</i>
        </button>
    </div>
    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-selectable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="mdui-table-col-numeric">邮箱</th>
                    <th class="mdui-table-col-numeric">密码sha256</th>
                    <th class="mdui-table-col-numeric">用户认证</th>
                    <th class="mdui-table-col-numeric">注册ip</th>
                    <th class="mdui-table-col-numeric">注册时间</th>
                    <th class="mdui-table-col-numeric">风险值</th>
                    <th class="mdui-table-col-numeric">金钱</th>
                    <th class="mdui-table-col-numeric">权限</th>
                    <th class="mdui-table-col-numeric">用户名</th>
                    <th class="mdui-table-col-numeric">头像</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div id="loadMoreBtn" class="mdui-p-t-4 mdui-center">
        <button class="mdui-btn mdui-color-theme-accent mdui-ripple">加载更多</button>
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
        fetch("../?type=func&target=get_all_user", {
            method: "POST"
        }).then(v => v.json()).then(v => {
            var offset = 0;
            var loading = false;
            var data = Object.values(v.data);

            function load() {
                var nowdata = data.filter((v, i) => i <= offset + (25) && i >= offset)
                nowdata.forEach((v, i) => {
                    offset++
                    mdui.$("tbody").append(`<tr>${Object.values(v).map(v => `<td>${v}</td>`).join("")}</tr>`);
                    if (i === nowdata.length - 1) {
                        mdui.mutation();
                        mdui.updateTables();
                        mdui.$(".mdui-table-cell-checkbox").on("change", function() {
                            arrange();
                        });
                    }
                });
                if (offset >= data.length) {
                    // 当数据已经全部加载完毕时，隐藏加载按钮
                    document.getElementById("loadMoreBtn").style.display = "none";
                } else {
                    // 当还有更多数据可加载时，显示加载按钮
                    document.getElementById("loadMoreBtn").style.display = "block";
                }

            }

            load();
            // 将 setTimeout 函数的回调函数改为箭头函数的形式，确保方法内的 `this` 指向正确
            setTimeout(() => {
                document.body.onwheel = function() {
                    var off = this.scrollHeight - this.clientHeight - this.scrollTop;
                    if (off <= 25 && loading === false) {
                        loading = true;
                        load();
                        setTimeout(() => {
                            loading = false;
                        }, 1000)
                    }
                }
                mdui.$("#loadMoreBtn").on("click", function() {
                load();
            });
            }, 1000);


            function arrange() {
                var x = mdui.$("tbody .mdui-table-row-selected");
                if (x.length === 0) {
                    mdui.$("div>button:nth-child(2)").attr("disabled", "");
                    mdui.$("div>button:nth-child(3)").attr("disabled", "");
                } else if (x.length < 2) {
                    mdui.$("div>button:nth-child(2)").removeAttr("disabled");
                    mdui.$("div>button:nth-child(3)").removeAttr("disabled", "");
                } else {
                    mdui.$("div>button:nth-child(2)").attr("disabled", "");
                    mdui.$("div>button:nth-child(3)").removeAttr("disabled", "");
                }
            };
            mdui.$("div>button:nth-child(2)").on("click", function() {
                window.top.writeHtml(
                    `<div class="mdui-dialog"><div class="mdui-dialog-title">编辑</div><div class="mdui-dialog-content">
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">邮箱</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">风险值</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">钱</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">权限</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">用户名</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">头像</label>
    <input class="mdui-textfield-input" />
</div>
</div><div class="mdui-dialog-actions"><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">关闭</a><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">保存</a></div></div>`,
                    function(p) {
                        var id = mdui.$(".mdui-table-row-selected>td:nth-child(2)").text()
                        var email = mdui.$(".mdui-table-row-selected>td:nth-child(3)").text()
                        var risk = mdui.$(".mdui-table-row-selected>td:nth-child(8)").text()
                        var money = mdui.$(".mdui-table-row-selected>td:nth-child(9)").text()
                        var quanxian = mdui.$(".mdui-table-row-selected>td:nth-child(10)").text()
                        var name = mdui.$(".mdui-table-row-selected>td:nth-child(11)").text()
                        var img = mdui.$(".mdui-table-row-selected>td:nth-child(12)").text()
                        var is_click = false;
                        var x = new p.w.mdui.Dialog(p.jq[0]);
                        var input = p.jq.find("input")
                        x.open();
                        input[0].value = email;
                        input[1].value = risk;
                        input[2].value = money;
                        input[3].value = quanxian;
                        input[4].value = name;
                        input[5].value = img;
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
                                            url: "../?type=func&target=change_user",
                                            method: "POST",
                                            data: {
                                                id,
                                                email: input[0].value,
                                                risk: input[1].value,
                                                money: input[2].value,
                                                status: input[3].value,
                                                name: input[4].value,
                                                img:input[5].value,
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
            mdui.$("div>button:nth-child(3)").on("click", function() {
                window.top.mdui.confirm("删除后不可恢复，确认继续？", "删除", function() {
                    var all = mdui.$(".mdui-table-row-selected>td:nth-child(2)")
                    var deleteCount = all.length;
                    all.each((id, e) => {
                        mdui.$.ajax({
                            url: "../?type=func&target=delete_user",
                            method: "POST",
                            data: {
                                id: mdui.$(e).text()
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