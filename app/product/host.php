<?php
require("./app/product/function.php");

if($user_info!=false){
    $user_id=$user_info["id"];
    $user_token=$user_info["token"];
}


if($_REQUEST["mode"]=="continue"){//续费

}else{

    $product_id=$product_config["id"];

    $service_id = preg_replace('/[^0-9]/', '', $_REQUEST["id"]);
    
    $params=json_decode($product_config["host"],true);
    $api=json_decode($product_config["host"],true)["api"];
    
    $times = $_REQUEST["number"]*30;//天数
    
    
    $info= NewHost_CreateAccount($params,$api);
    if(mysqli_query($conn, "INSERT INTO `blue_service`(`user_id`, `product_id`, `time`, `appid`, `long`, `token`, `admin`,`times`,`name`) VALUES ('$user_id', '$product_id', '".time()."', '', '0', '$user_token', '$admin','".preg_replace('/[^0-9]/', '', $_REQUEST["number"])."','美国1号')")){
        $money = floatval($user_info["money"])-$should_pay;
        mysqli_query($conn, "UPDATE `blue_user` SET `money`='$money' WHERE `id`='$user_id'");
        $return = array('code'=>200,"info"=>$info);
        die(json_encode($return));
    }else{
        $return = array('code'=>500);
        die(json_encode($return));
    }
}
