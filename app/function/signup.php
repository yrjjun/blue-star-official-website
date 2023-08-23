<?php
$bluestar->get_function($conn,8);
    $to=preg_replace("/[^A-Za-z0-9.@]/", "", $_REQUEST["email"]);
    $result = mysqli_query($conn,"SELECT * FROM blue_code WHERE email='$to' AND code='0'");
    if($result->num_rows ==0){
        $return = array('info'=>"请点击“发送验证码”".$id, 'code'=>401);
        echo json_encode($return);
    }else{
        while($row = mysqli_fetch_array($result)){
        if ($row["time"]+60*5<time()){
            $return = array('info'=>"当前验证码已超时", 'code'=>402);
            echo json_encode($return);
        }else{
            if($row["text"]==$_REQUEST["text"]){
                $user = mysqli_query($conn,"SELECT * FROM blue_user WHERE email='$to'");
                if($user->num_rows ==0){//不存在该账户
                    $return = array('info'=>"注册成功", 'code'=>200);
                    $password = hash("sha256", $_REQUEST["password"]);
                    $token = hash("sha256", $to.$password.time());
                    	// 检查是否使用了代理服务器
	                    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	                    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		                    $ip = $_SERVER['HTTP_CLIENT_IP'];
	                    } else {
		                    $ip = $_SERVER['REMOTE_ADDR'];
	                    }
                    $time = time();
                    $img = "";
                    //$name = preg_replace('/[^\x{4e00}-\x{9fa5}a-zA-Z0-9]/u', '', $_REQUEST["name"]);
                    $name =$_REQUEST["name"];

                    // 准备删除查询语句
                    $deleteQuery = "DELETE FROM `blue_code` WHERE email = ?";
                    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
                    mysqli_stmt_bind_param($deleteStmt, "s", $to);
                    
                    // 执行删除查询
                    mysqli_stmt_execute($deleteStmt);
                    
                    // 准备插入查询语句
                    $insertQuery = "INSERT INTO `blue_user`(`email`, `password`, `token`, `ip`, `time`, `risk`, `money`, `status`, `name`, `img` ) VALUES (?, ?, ?, ?, ?, '0', '0.00', '', ?, ?)";
                    $insertStmt = mysqli_prepare($conn, $insertQuery);
                    mysqli_stmt_bind_param($insertStmt, "sssssss", $to, $password, $token, $ip, $time, $name,$img);
                    
                    // 执行插入查询
                    mysqli_stmt_execute($insertStmt);
                    $bluestar->add_log($conn,"用户注册成功",$to,$ip,$time,"200");//插入日志
                    setcookie("user_email", $to, time()+60*60*24*7);
                    setcookie("user_password", hash("sha256", $_REQUEST["password"]), time()+60*60*24*7);
                    echo json_encode($return);
                }else{
                    $return = array('code'=>502);
                    echo json_encode($return);
                }
            }else{
                $return = array('info'=>"验证码错误", 'code'=>404);
                echo json_encode($return);
            }
        }
        }
    }
?>