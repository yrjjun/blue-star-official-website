<?php
if($user_info!=false){
    $user_id=$user_info["id"];
    $user_token=$user_info["token"];
}
$product_id=$product_config["id"];
if($_REQUEST["mode"]=="continue"){//续费
    $old_times = $service[$_REQUEST["id"]]["times"];
    $new_times = $old_times+preg_replace('/[^0-9]/', '', $_REQUEST["number"]);
    if(mysqli_query($conn, "UPDATE `blue_service` SET `times`='$new_times' WHERE `id`='".preg_replace('/[^0-9]/', '', $_REQUEST["id"])."'")){
        $money = floatval($user_info["money"])-$should_pay;
        mysqli_query($conn, "UPDATE `blue_user` SET `money`='$money' WHERE `id`='$user_id'");
        $return = array('code'=>200);
        die(json_encode($return));
    }else{
        $return = array('code'=>500);
        die(json_encode($return));
    }
}else{//购买
    if(mysqli_query($conn, "INSERT INTO `blue_service`(`user_id`, `product_id`, `time`, `appid`, `long`, `token`, `admin`,`times`,`name`) VALUES ('$user_id', '$product_id', '".time()."', '".hash("sha256",time().$user_id)."', '0', '$user_token', '$admin','".preg_replace('/[^0-9]/', '', $_REQUEST["number"])."','email')")){
        $money = floatval($user_info["money"])-$should_pay;
        mysqli_query($conn, "UPDATE `blue_user` SET `money`='$money' WHERE `id`='$user_id'");
        $return = array('code'=>200);
        die(json_encode($return));
    }else{
        $return = array('code'=>500);
        die(json_encode($return));
    }
     
}


?>