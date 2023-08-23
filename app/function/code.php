<?php
header('Content-Type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin:*');
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");
header("content-type: text/json;charset:utf-8");

if (mysqli_connect_errno())
{//数据库链接失败
    $return = array('info'=>$conn->connect_error, 'code'=>100);
    echo json_encode($return);
}else{//数据库链接成功
    if($_REQUEST["mode"]=="send"){
        $result = mysqli_query($conn,"SELECT * FROM blue_code WHERE email='".preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["to"])."' AND code='0'");
        if($result->num_rows ==0){
            $text=rand(100000,999999);
            $time=time();
            $to=preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["to"]);
            $result = mysqli_query($conn,"INSERT INTO `blue_code` (`email`, `text`, `time`, `code`) VALUES ('".$to."', '$text', '$time', '0')");
           sendMail($to,"BlueStarNet邮箱验证系统","您正在注册/登录","您的验证码是:".$text."请勿告诉他人哦", $config);    
            $return = array('code'=>200);
            echo json_encode($return);
        }else{
            while($row = mysqli_fetch_array($result)){
                if($row["time"]+60<time()){//可以发送
                    $text=rand(100000,999999);
                    $time=time();
                    $to=preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["to"]);
                    mysqli_query($conn, "DELETE FROM `blue_code` WHERE email='$to'");
                    mysqli_query($conn,"INSERT INTO `blue_code` (`email`, `text`, `time`, `code`) VALUES ('$to', '$text', '$time', '0')");
                    sendMail($to,"BlueStarNet邮箱验证系统","您正在注册/登录","您的验证码是:".$text."请勿告诉他人哦", $config);        
                    $return = array('code'=>200);
                    echo json_encode($return);
                }else{
                    $return = array('code'=>500);//发送太快
                    echo json_encode($return);
                }
            }
        }
   }else{
        $to=preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);
        $result = mysqli_query($conn,"SELECT * FROM blue_code WHERE email='$to' AND code='0'");
        if($result->num_rows ==0){
            $return = array('info'=>"未找到当前验证邮箱.".$id, 'code'=>401);
            echo json_encode($return);
        }else{
            while($row = mysqli_fetch_array($result)){
            if ($row["time"]+60*5<time()){
                $return = array('info'=>"当前验证码已超时", 'code'=>402);
                echo json_encode($return);
            }else{
                if(md5($row["text"])==$_REQUEST["text"]){
                    $return = array('info'=>"登录成功", 'code'=>200);
                    mysqli_query($conn, "DELETE FROM blue_code WHERE email=$to");
                    echo json_encode($return);
                }else{
                    $return = array('info'=>"验证码错误".$id, 'code'=>404);
                    echo json_encode($return);
                }
            }
            }
        }
    }
}
?>
