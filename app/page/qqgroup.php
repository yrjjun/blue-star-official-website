<?php
$bluestar->get_function($conn,11);
if (!isset($config["qqgroup"])) {
    die("<h1>站长未设置QQ群邀请链接</h1>");
}
?>
<h1>请稍后</h1>
<iframe src="<?php echo $config["qqgroup"] ?>" style="width:0px;height:0px;border:none;margin:0;padding:0;visibility: hidden;pointer-events: none;"></iframe>