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
    <h2>产品分类管理</h2>
    <p>管理产品的分类</p>
    <div class="mdui-toolbar mdui-m-b-2">
        <div class="mdui-toolbar-spacer"></div>
        <button class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">add</i>
        </button>
        <button class="mdui-btn mdui-btn-icon">
            <i class="mdui-icon material-icons">delete</i>
        </button>
    </div>
    <div class="mdui-table-fluid">
        <table class="mdui-table mdui-table-selectable">
            <thead>
                <tr>
                    <th class="mdui-table-col-numeric">名称</th>
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
        fetch("../?type=func&target=get_product_from", {
            method: "POST"
        }).then(v => v.json()).then(v => {
            var data = Object.values(v.data);
            data.forEach(v => {
                mdui.$("tbody").append(`<tr><td>${v}</td></tr>`);
                mdui.mutation();
                mdui.updateTables();
            });

            function arrange() {
                var x = mdui.$("tbody .mdui-table-row-selected");
                if (x.length === 0) {
                    mdui.$("div>button:nth-child(3)").attr("disabled", "");
                } else if (x.length < 2) {
                    mdui.$("div>button:nth-child(3)").removeAttr("disabled", "");
                } else {
                    mdui.$("div>button:nth-child(3)").removeAttr("disabled", "");
                }
            };
            mdui.$("div>button:nth-child(2)").on("click", function() {
                var x = window.top.mdui.prompt("输入名称", "添加", function(v) {
                    if (v) {
                        mdui.$.ajax({
                            url: "../?type=func&target=add_pf",
                            method: "POST",
                            data: {
                                name: v,
                            },
                            success: function() {
                                location.reload();
                            }
                        })
                    }
                });
            });
            mdui.$("div>button:nth-child(3)").on("click", function() {
                window.top.mdui.confirm("删除后不可恢复，确认继续？", "删除", function() {
                    var all = mdui.$(".mdui-table-row-selected>td:nth-child(2)")
                    var deleteCount = all.length;
                    all.each((id, e) => {
                        mdui.$.ajax({
                            url: "../?type=func&target=delete_pf",
                            method: "POST",
                            data: {
                                name: mdui.$(e).text()
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