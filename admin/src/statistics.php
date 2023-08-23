<?php
require("../../config.php");
$conn = mysqli_connect($host, $user, $password, $dbname);
require("../../function.php");
$user_info = $bluestar->detection($conn);
$isadmin = strpos($user_info["status"], "admin") !== false;
if (!$isadmin) {
    $bluestar->add_log($conn, "无权限访问admin面板", "", $_SERVER['REMOTE_ADDR'], time(), "200");
    header('Location: ../');
    exit;
}

?><canvas>
    <div style="width:100%;height:100%;text-align:center;color:red">
        <p>您的浏览器不支持 Canvas2D</p>
    </div>
</canvas>
<div class="control">
    <button>打印</button>
    <button>下载</button>
    <button>复制</button>
</div>
<div class="statistics">
    <h3></h3>
    <p></p>
</div>
<style>
    * {
        overflow: hidden;
    }

    .statistics {
        position: fixed;
        top: 0px;
        left: 0px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 0 30px -10px #aaa;
        border-radius: 5px;
        border: 1px solid #ccc;
        display: none;
    }

    .statistics h3 {
        margin: 0px;
    }

    .statistics p {
        margin: 10px 0px 0px;
        font-size: 14px;
    }

    .control {
        position: fixed;
        right: 0;
        top: 0;
        margin: 10px 10px 0px 0px
    }
</style>
<?php
function getTime1()
{
    $date = new DateTime();
    $year = $date->format('Y');
    $month = $date->format('m');
    $day = $date->format('d');

    $timestamp = mktime(0, 0, 0, $month, $day, $year);
    return $timestamp;
}
function getTime2()
{
    $date = new DateTime();
    $year = $date->format('Y');
    $month = $date->format('m');
    $day = $date->format('d');
    $hours = $date->format('H');

    $timestamp = mktime($hours, 0, 0, $month, $day, $year);
    return $timestamp;
}
function getTime()
{
    if ($_REQUEST["a"] == "0") {
        return array(getTime1(), 86400);
    } else {
        return array(getTime2(), 3600);
    }
}
$array = array();
for ($i = 0; $i < intval($_REQUEST["b"]); $i++) {
    $num = 0;
    $t = getTime();
    $time = $t[0] - ($i * $t[1]);
    $time1 = $t[0] - (($i - 1) * $t[1]);
    $timestamp = $time;
    $date = date($_REQUEST["d"], $timestamp);
    $select = mysqli_query($conn, "SELECT * FROM `blue_log` WHERE `time`>'$time' AND `time`<='$time1' AND `text`='访问'");
    while ($row = mysqli_fetch_assoc($select)) {
        $num += 1;
    }
    array_push($array, array($date, $num));
}
$data = array_reverse($array);
?>
<script>
    /**
     * Copyright (c) 2023 by xiaohong2022
     * 未经允许，不能以任何方式复制该代码的任何一部分，违者必究
     */
try{
    ! function() {
        const list = <?php echo json_encode($data) ?>;
        const canvas = document.querySelector("canvas");
        const statistics = document.querySelector(".statistics");
        const canvas2d = canvas.getContext("2d");
        const canvas_father = canvas.parentElement;
        var width = canvas_father.offsetWidth;
        const height = <?php echo $_REQUEST["c"] ?>;
        const padding = 60;

        const max = (~~(list.map(v => v[1]).sort((a, b) => b - a)[0] / 10) + 1) * 10;

        var lineNow = -1;

        canvas.width = width;
        canvas.height = height;

        function init() {
            width = canvas_father.offsetWidth;
            canvas.width = width;
            canvas.height = height;
        };

        function restart() {
            canvas2d.clearRect(0,0,width,height);
        };

        function drawNo0() {
            canvas2d.beginPath();
            canvas2d.fillStyle = "#fff";
            canvas2d.fillRect(0, 0, width, height);
            canvas2d.closePath();
        };

        function drawNo1() {
            canvas2d.beginPath();
            canvas2d.lineWidth = 2
            canvas2d.strokeStyle = "#000";
            canvas2d.moveTo(padding, height - padding);
            canvas2d.lineTo(width - padding, height - padding);
            canvas2d.stroke();
            canvas2d.closePath();
        };

        function drawNo2() {
            var w = width - (padding * 2);
            var h = height - (padding * 2);

            canvas2d.beginPath();
            canvas2d.fillStyle = "#000";
            canvas2d.font = '15px serif';
            canvas2d.textAlign = 'start'

            var text = String(0);
            canvas2d.fillText(text, padding - canvas2d.measureText(text).width - 10, padding + h + 5);
            var text = String(max);
            canvas2d.fillText(text, padding - canvas2d.measureText(text).width - 10, padding + 5);

            canvas2d.closePath();


            canvas2d.beginPath();
            canvas2d.lineWidth = .5
            canvas2d.strokeStyle = "#555";
            canvas2d.moveTo(padding, padding);
            canvas2d.lineTo(width - padding, padding);
            canvas2d.stroke();
            canvas2d.closePath();


            canvas2d.beginPath();

            canvas2d.lineWidth = .5
            canvas2d.strokeStyle = "#555";
            canvas2d.fillStyle = "#000";
            canvas2d.font = '15px serif';
            canvas2d.textAlign = 'start'

            canvas2d.moveTo(padding, padding + (h / 2));
            canvas2d.lineTo(width - padding, padding + (h / 2));
            var text = String(~~(max / 2));
            canvas2d.fillText(text, padding - canvas2d.measureText(text).width - 10, padding + (h / 2) + 5);

            canvas2d.moveTo(padding, padding + (h / 4));
            canvas2d.lineTo(width - padding, padding + (h / 4));
            var text = String(~~(max / 4 * 3));
            canvas2d.fillText(text, padding - canvas2d.measureText(text).width - 10, padding + (h / 4) + 5);

            canvas2d.moveTo(padding, padding + (h / 4 * 3));
            canvas2d.lineTo(width - padding, padding + (h / 4 * 3));
            var text = String(~~(max / 4));
            canvas2d.fillText(text, padding - canvas2d.measureText(text).width - 10, padding + (h / 4 * 3) + 5);

            canvas2d.stroke();
            canvas2d.closePath();
        };

        function drawNo3() {
            var w = width - (padding * 2);
            var h = height - (padding * 2);
            var jw = w / (list.length - 1);
            var jh = h / max;

            canvas2d.beginPath();

            canvas2d.lineCap = 'round';
            canvas2d.lineJoin = 'round';
            canvas2d.lineWidth = 2;
            canvas2d.strokeStyle = "#5383F1";
            canvas2d.fillStyle = "#5383F1";
            canvas2d.font = '20px serif';
            canvas2d.textAlign = 'start'

            canvas2d.moveTo(padding, padding + h - jh * list[0][1]);
            for (let index in list) {
                canvas2d.lineTo(padding + jw * index, padding + h - jh * list[index][1]);

                let text = String(list[index][1]);
                canvas2d.fillText(text, padding + jw * index - canvas2d.measureText(text).width / 2, padding + h - jh * list[index][1] - 10);
            };

            canvas2d.stroke();
            canvas2d.closePath();
        };

        function drawNo4() {
            var w = width - (padding * 2);
            var h = height - (padding * 2);
            var jw = w / (list.length - 1);
            var jh = h / max;

            canvas2d.beginPath();
            canvas2d.fillStyle = "#000";
            canvas2d.font = '17px serif';
            canvas2d.textAlign = 'start';

            for (let index in list) {
                let text = String(list[index][0]);
                canvas2d.fillText(text, padding + jw * index - canvas2d.measureText(text).width / 2, padding + h + 30);
            };

            canvas2d.closePath()
        }

        function drawNo5() {
            var w = width - (padding * 2);
            var h = height - (padding * 2);
            var jw = w / (list.length - 1);
            var jh = h / max;
            if (lineNow !== -1) {
                canvas2d.beginPath();

                canvas2d.lineWidth = 1;
                canvas2d.strokeStyle = "#000";

                canvas2d.moveTo(padding + jw * lineNow, padding);
                canvas2d.lineTo(padding + jw * lineNow, height - padding);

                canvas2d.stroke();
                canvas2d.closePath();
            }
            for (let index in list) {
                if (lineNow == index) {
                    canvas2d.beginPath();
                    canvas2d.lineWidth = 2;
                    canvas2d.strokeStyle = "#5383F1";
                    canvas2d.fillStyle = "#fff";
                    canvas2d.arc(padding + jw * index, padding + h - jh * list[index][1], 4, 0, [(Math.PI) / 180] * 360);
                    canvas2d.fill();
                    canvas2d.stroke();
                    canvas2d.closePath();
                }
            };
        }

        function drawAll() {
            restart();
            init();
            drawNo0();
            drawNo1();
            drawNo2();
            drawNo3();
            drawNo4();
            drawNo5();
        }
        window.addEventListener("resize", function() {
            drawAll();
        });
        drawAll();
        canvas.addEventListener("mouseleave", function() {
            statistics.style.display = "none";
        });
        canvas.addEventListener("mousemove", function(e) {
            var w = width - (padding * 2);
            var jw = w / (list.length - 1);

            lineNow = -1;
            if (e.offsetX > padding && e.offsetX < width - padding) {
                if (e.offsetY > padding && e.offsetY < height - padding) {
                    statistics.style.display = "block";
                    if (e.x > width / 2) {
                        statistics.style.left = (e.x - statistics.offsetWidth - 10) + "px";
                    } else {
                        statistics.style.left = (e.x + 10) + "px";
                    }
                    if (e.y > height / 2) {
                        statistics.style.top = (e.y - statistics.offsetHeight - 10) + "px";
                    } else {
                        statistics.style.top = (e.y + 10) + "px";
                    }
                    lineNow = ~~((e.offsetX - padding / 4) / jw);
                    statistics.children[0].innerText = list[lineNow][0];
                    statistics.children[1].innerText = "共访问" + list[lineNow][1] + "次";
                } else {
                    statistics.style.display = "none";
                }
            } else {
                statistics.style.display = "none";
            }
            drawAll();
        });
        document.querySelectorAll('button')[0].addEventListener("click", function() {
            document.querySelector('.control').style.display = "none";
            window.print();
            setTimeout(() => {
                document.querySelector('.control').style.display = "";
            }, 100)
        });
        document.querySelectorAll('button')[2].addEventListener("click", function() {
            canvas.toBlob(function(blob) {
                if (blob) {
                    if (navigator.clipboard) {
                        try {
                            const clipboardItem = new ClipboardItem({
                                "image/png": blob,
                            });
                            navigator.clipboard.write([clipboardItem]).then(() => {
                                window.top.mdui.alert("复制成功");
                            }, () => {
                                window.top.mdui.alert("无法复制，可以尝试右击统计图进行复制");
                            })
                        } catch (e) {
                            window.top.mdui.alert("无法复制，可以尝试右击统计图进行复制");
                        }
                    } else {
                        window.top.mdui.alert("无法复制，可以尝试右击统计图进行复制");
                    }
                } else {
                    window.top.mdui.alert("无法复制，可以尝试右击统计图进行复制");
                }
            })
        });
        document.querySelectorAll('button')[1].addEventListener("click", function() {
            canvas.toBlob(function(blob) {
                if (blob) {
                    try {
                        var urlObject = window.URL || window.webkitURL || window;
                        var save_link = document.createElement("a");
                        save_link.href = urlObject.createObjectURL(blob);
                        save_link.download = "下载.png";
                        save_link.click();
                    } catch (e) {
                        window.top.mdui.alert("无法下载，可以尝试右击统计图进行下载");
                    }
                } else {
                    window.top.mdui.alert("无法下载，可以尝试右击统计图进行下载");
                }
            })
        });
    }();}catch(e){let x =document.createElement("div");x.innerText=e;document.body.append(x)}
</script>