<?php

if($_REQUEST["mode"]=="continue"){
    $bluestar->get_function($conn,10);
    $product_config = $product_info[$_REQUEST["product_id"]];
}else{
    $bluestar->get_function($conn,9);
    $product_config = $product_info[$_REQUEST["id"]];
}


/*
判断能否购买某个服务
*/
if($user_info!=false){
    $user_money=$user_info["money"];
}

if(floatval($product_config["money"])*$_REQUEST["number"] ==0){
    $should_pay=0;
}elseif (floatval($product_config["money"])*$_REQUEST["number"] <=0.01) {
    $should_pay=0.01*100;
}else{
    $should_pay = floatval($product_config["money"])*$_REQUEST["number"]*100;
}
if($should_pay<=$user_money){
    //够钱
    $admin = $product_config["admin"];
    require("./app/product/".$product_config["buy"]);
}else{
    die(json_encode(array("code"=>400)));
}
?>