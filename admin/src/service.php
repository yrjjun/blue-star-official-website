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
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<link rel="stylesheet" href="../../file/css/admin.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>用户API服务管理</h2>
    <p>用户API服务管理，可查看用户购买的产品</p>
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
    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-selectable">
            <thead>
                <tr>
                    <th>id</th>
                    <th class="mdui-table-col-numeric">用户ID</th>
                    <th class="mdui-table-col-numeric">产品ID</th>
                    <th class="mdui-table-col-numeric">购买时间</th>
                    <th class="mdui-table-col-numeric">APPID</th>
                    <th class="mdui-table-col-numeric">TOKEN</th>
                    <th class="mdui-table-col-numeric">购买时长类型</th>
                    <th class="mdui-table-col-numeric">用户管理服务的面板</th>
                    <th class="mdui-table-col-numeric">产品名</th>
                    <th class="mdui-table-col-numeric">剩余使用次数</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
        fetch("../?type=func&target=get_service", {
            method: "POST"
        }).then(v => v.json()).then(v => {
            var data = Object.values(v.data);
            data.forEach(v => {
                mdui.$("tbody").append(`<tr>${Object.values(v).map(v => `<td>${v}</td>`).join("")}</tr>`);
                mdui.mutation();
                mdui.updateTables();
            });

            function arrange() {
                var x = mdui.$("tbody .mdui-table-row-selected");
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
                    `<div class="mdui-dialog"><div class="mdui-dialog-title">添加</div><div class="mdui-dialog-content">
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">用户ID</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">产品ID</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">APPID</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">TOKEN</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">购买时长类型(0:times,1:months)</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">用户管理服务的面板</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">产品名</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">剩余使用次数</label>
    <input class="mdui-textfield-input" />
</div></div><div class="mdui-dialog-actions"><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">关闭</a><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">添加</a></div></div>`,
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
                                            url: "../?type=func&target=add_service",
                                            method: "POST",
                                            data: {
                                                user_id: input[0].value,
                                                product_id: input[1].value,
                                                appid: input[2].value,
                                                token: input[3].value,
                                                long: input[4].value,
                                                admin: input[5].value,
                                                name: input[6].value,
                                                times: input[7].value,
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
                    `<div class="mdui-dialog"><div class="mdui-dialog-title">编辑</div><div class="mdui-dialog-content">
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">用户ID</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">产品ID</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">APPID</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">TOKEN</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">购买时长类型(0:times,1:months)</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">用户管理服务的面板</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">产品名</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">剩余使用次数</label>
    <input class="mdui-textfield-input" />
</div>
</div><div class="mdui-dialog-actions"><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">关闭</a><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">保存</a></div></div>`,
                    function(p) {
                        var id = mdui.$(".mdui-table-row-selected>td:nth-child(2)").text()
                        var p1 = mdui.$(".mdui-table-row-selected>td:nth-child(3)").text()
                        var p4 = mdui.$(".mdui-table-row-selected>td:nth-child(4)").text()
                        var p5 = mdui.$(".mdui-table-row-selected>td:nth-child(6)").text()
                        var p6 = mdui.$(".mdui-table-row-selected>td:nth-child(7)").text()
                        var p7 = mdui.$(".mdui-table-row-selected>td:nth-child(8)").text()
                        var p8 = mdui.$(".mdui-table-row-selected>td:nth-child(9)").text()
                        var p9 = mdui.$(".mdui-table-row-selected>td:nth-child(10)").text()
                        var p10 = mdui.$(".mdui-table-row-selected>td:nth-child(11)").text()
                        var is_click = false;
                        var x = new p.w.mdui.Dialog(p.jq[0]);
                        var input = p.jq.find("input")
                        x.open();
                        input[0].value = p1;
                        input[1].value = p4;
                        input[2].value = p5;
                        input[3].value = p6;
                        input[4].value = p7;
                        input[5].value = p8;
                        input[6].value = p9;
                        input[7].value = p10;
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
                                            url: "../?type=func&target=change_service",
                                            method: "POST",
                                            data: {
                                                id,
                                                user_id: input[0].value,
                                                product_id: input[1].value,
                                                appid: input[2].value,
                                                token: input[3].value,
                                                long: input[4].value,
                                                admin: input[5].value,
                                                name: input[6].value,
                                                times: input[7].value,
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
                    var all = mdui.$(".mdui-table-row-selected>td:nth-child(2)")
                    var deleteCount = all.length;
                    all.each((id, e) => {
                        mdui.$.ajax({
                            url: "../?type=func&target=delete_service",
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
            mdui.$(".mdui-table-cell-checkbox").on("change", function() {
                arrange();
            });
        });
    }();
</script>