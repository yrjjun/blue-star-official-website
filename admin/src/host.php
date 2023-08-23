<?php
require("../../config.php");
$conn = mysqli_connect($host, $user, $password, $dbname);
require("../../function.php");
$config = $bluestar->get_config($conn);
$user_info = $bluestar->detection($conn);
$isadmin = strpos($user_info["status"], "admin") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问admin面板", "", $_SERVER[''], time(), "200");
    header('Location: ../');
    exit;
}
?>
<link rel="stylesheet" href="../../file/css/mdui.min.css">
<link rel="stylesheet" href="../../file/css/admin.css">
<script src="../../file/js/mdui.min.js"></script>
<div class="mdui-p-a-5 mdui-typo">
    <h2>虚拟主机母机管理</h2>
    <p>管理平台上的母机</p>
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
                    <th>ID</th>
                    <th class="mdui-table-col-numeric">母机名</th>
                    <th class="mdui-table-col-numeric">ep分销站点链接</th>
                    <th class="mdui-table-col-numeric">ep分销站点用户名</th>
                    <th class="mdui-table-col-numeric">ep分销站点安全码</th>
                    <th class="mdui-table-col-numeric">购买程序的路径</th>
                    <th class="mdui-table-col-numeric">母机IP</th>
                    <th class="mdui-table-col-numeric">机房售价</th>
                    <th class="mdui-table-col-numeric">介绍</th>
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
        fetch("../?type=func&target=get_host", {
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
    <label class="mdui-textfield-label">母机名</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">ep分销站点链接</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">ep分销站点用户名</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">ep分销站点安全码</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">购买程序的路径</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">母机IP</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">机房售价</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">介绍</label>
    <input class="mdui-textfield-input" />
</div>
                    </div>
                    <div class="mdui-dialog-actions"><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">关闭</a><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">添加</a></div></div>`,
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
                                            url: "../?type=func&target=add_host",
                                            method: "POST",
                                            data: {
                                                name: input[0].value,
                                                host_url: input[1].value,
                                                user_name: input[2].value,
                                                user_key: input[3].value,
                                                buy: input[4].value,
                                                host_ip: input[5].value,
                                                money: input[6].value,
                                                about: input[7].value,
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
    <label class="mdui-textfield-label">母机名</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">ep分销站点链接</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">ep分销站点用户名</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">ep分销站点安全码</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">购买程序的路径</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">母机IP</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">机房售价</label>
    <input class="mdui-textfield-input" />
</div>
<div class="mdui-textfield mdui-textfield-floating-label">
    <label class="mdui-textfield-label">介绍</label>
    <input class="mdui-textfield-input" />
</div>
</div><div class="mdui-dialog-actions"><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">关闭</a><a href="javascript:void(0)" class="mdui-btn mdui-ripple mdui-text-color-primary">保存</a></div></div>`,
                    function(p) {
                        var id = mdui.$(".mdui-table-row-selected>td:nth-child(2)").text();
                        var is_click = false;
                        var x = new p.w.mdui.Dialog(p.jq[0]);
                        var input = p.jq.find("input")
                        x.open();
                        input[0].value = mdui.$(".mdui-table-row-selected>td:nth-child(3)").text();
                        input[1].value = mdui.$(".mdui-table-row-selected>td:nth-child(4)").text();
                        input[2].value = mdui.$(".mdui-table-row-selected>td:nth-child(5)").text();
                        input[3].value = mdui.$(".mdui-table-row-selected>td:nth-child(6)").text();
                        input[4].value = mdui.$(".mdui-table-row-selected>td:nth-child(7)").text();
                        input[5].value = mdui.$(".mdui-table-row-selected>td:nth-child(8)").text();
                        input[6].value = mdui.$(".mdui-table-row-selected>td:nth-child(9)").text();
                        input[7].value = mdui.$(".mdui-table-row-selected>td:nth-child(10)").text();
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
                                            url: "../?type=func&target=change_host",
                                            method: "POST",
                                            data: {
                                                id,
                                                name: input[0].value,
                                                host_url: input[1].value,
                                                user_name: input[2].value,
                                                user_key: input[3].value,
                                                buy: input[4].value,
                                                host_ip: input[5].value,
                                                money: input[6].value,
                                                about: input[7].value,
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
                            url: "../?type=func&target=delete_host",
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