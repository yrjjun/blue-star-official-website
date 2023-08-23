<?php
function request_post($url, $data = array()) {

}
function NewHost_ConfigOptions() {
    return [
        '空间大小' => ['Type' => 'text','Size' => '5','Description' => 'MB'],
        '数据库大小' => ['Type' => 'text','Size' => '5','Description' => 'MB'],
        '绑定域名数' => ['Type' => 'text','Size' => '5','Description' => '个，-1为无限'],
        '绑定子目录数' => ['Type' => 'text','Size' => '5','Description' => '个，0为无限'],
        '流量限制' => ['Type' => 'text','Size' => '5','Description' => 'GB/月'],
        '产品类型(宝塔主机无效)' => ['Type' => 'text','Size' => '5','Description' => '0为虚拟主机，1为CDN'],
        '端口(可留空)' => ['Type' => 'text','Size' => '5','Description' => '多个端口由,分开,ssl端口请加s，如80,443s'],
        'Web空间备份数(宝塔主机有效)' => ['Type' => 'text','Size' => '5','Description' => '个'],
        'Sql空间备份数(宝塔主机有效)' => ['Type' => 'text','Size' => '5','Description' => '个'],
        '是否允许绑定子目录' => ['Type' => 'text','Size' => '5','Description' => '1为允许，0为禁止']
    ];
}
function NewHost_AdminLink($params) {
    $url = isset($params['product_id']) ? $params['serverdomain'] : $params['serverhostname'].'Index/checkapi.html';
    $postdata = [
        'username' => $params['serverusername'],
        'userkey' => $params['serverpassword'],
        'apiip' => $params['serverip']
    ];
    $resultdata = request_post($url, $postdata);
    $resultdata = json_decode($resultdata, TRUE);
    $str = '<span class="btn btn-success btn-xs">' . $resultdata['info'] . '</span>';
    if ($resultdata['status'] != 1)$str = '<span class="btn btn-danger btn-xs">' . $resultdata['info'] . '</span>';

    return $str;
}
function NewHost_CheckName($params) {
    if (!preg_match('/^[a-z0-9][a-z0-9_]{3,16}$/', $params['username']))return 'failed';
    return 'success';
}
function NewHost_ClientArea($params) {
    $url = isset($params['product_id']) ? $params['serverdomain'] : $params['serverhostname'].'Index/get_kzurl.html';
    $postdata = [
        'username' => $params['serverusername'],
        "userkey" => $params['serverpassword'],
        'apiip' => $params['serverip'],
    ];
    $resultdata = request_post($url, $postdata);
    $resultdata = json_decode($resultdata, TRUE);
    if (isset($params['configoption25'])) {
        $str1 = '<ui><form action="'.$resultdata['data'].'?c=session&a=login" method="post" target="_blank"><input type="hidden" name="username" value="' . $params['username'] . '" /><input type="hidden" name="passwd" value="' . $params['password'] . '" /><input type="submit" class="btn btn-success btn-block" value="直接登录(自定义密码无效)"/></form>';
        $str2 = '<a href="'.$resultdata['data'].'?c=session&a=login" target="_blank" class="btn btn-primary btn-block">打开登录地址</a>';
        return [$str1, $str2];
    } else {
        $str = '<body onLoad="document.NewHost.submit()"><form action="'.$resultdata['data'].'?c=session&a=login" method="POST" name="NewHost"><input name="username" value="' . $params['username'] . '" hidden><input name="passwd" value="' . $params['password'] . '" hidden></form></body>';
        exit($str);
    }
}
function NewHost_CreateAccount($params,$url) {
    if (!$params['configoption6'] && ($params['configoption1'] == NULL || $params['configoption2'] == NULL)) return '容量设置错误';

    // 初始化cURL
    $ch = curl_init();

    // 设置请求的URL
    curl_setopt($ch, CURLOPT_URL, $url);

    // 设置请求为POST方式
    curl_setopt($ch, CURLOPT_POST, 1);
    
    // 设置POST数据
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    
    // 执行请求并获取响应数据
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    
    // 关闭cURL资源
    curl_close($ch);
    
    $resultdata = $response;
    $resultdata = json_decode($resultdata, TRUE);

    $str = 'success';
    if (isset($params['configoption25']))$str = '成功';
    if ($resultdata['status'] != 1)$str = $resultdata['info'];

    return $str;
}
function NewHost_changepassword($params) {
    $url = isset($params['product_id']) ? $params['serverdomain'] : $params['serverhostname'].'Index/updatepassword.html';
    $postdata = [
        'username' => $params['serverusername'],
        "userkey" => $params['serverpassword'],
        'apiip' => $params['serverip'],
        'hostname' => $params['username'],
        'hostpwd' => $params['password']

    ];
    $resultdata = request_post($url, $postdata);
    $resultdata = json_decode($resultdata, TRUE);

    $str = 'success';
    if (isset($params['configoption25']))$str = '成功';
    if ($resultdata['status'] != 1)$str = $resultdata['info'];

    return $str;
}
function NewHost_TerminateAccount($params) {
    $url = isset($params['product_id']) ? $params['serverdomain'] : $params['serverhostname'].'Index/delhost.html';
    $postdata = [
        'username' => $params['serverusername'],
        "userkey" => $params['serverpassword'],
        'apiip' => $params['serverip'],
        'hostname' => $params['username']
    ];
    $resultdata = request_post($url, $postdata);
    $resultdata = json_decode($resultdata, TRUE);

    $str = 'success';
    if (isset($params['configoption25']))$str = '成功';
    if ($resultdata['status'] != 1)$str = $resultdata['info'];

    return $str;
}
function NewHost_SuspendAccount($params) {
    $url = isset($params['product_id']) ? $params['serverdomain'] : $params['serverhostname'].'Index/updatestatus.html';
    $postdata = [
        'username' => $params['serverusername'],
        "userkey" => $params['serverpassword'],
        'apiip' => $params['serverip'],
        'hostname' => $params['username'],
        'status' => 1
    ];
    $resultdata = request_post($url, $postdata);
    $resultdata = json_decode($resultdata, TRUE);

    $str = 'success';
    if (isset($params['configoption25']))$str = '成功';
    if ($resultdata['status'] != 1)$str = $resultdata['info'];

    return $str;
}
function NewHost_UnsuspendAccount($params) {
    $url = isset($params['product_id']) ? $params['serverdomain'] : $params['serverhostname'].'Index/updatestatus.html';
    $postdata = [
        'username' => $params['serverusername'],
        "userkey" => $params['serverpassword'],
        'apiip' => $params['serverip'],
        'hostname' => $params['username'],
        'status' => 0
    ];
    $resultdata = request_post($url, $postdata);
    $resultdata = json_decode($resultdata, TRUE);

    $str = 'success';
    if (isset($params['configoption25']))$str = '成功';
    if ($resultdata['status'] != 1)$str = $resultdata['info'];

    return $str;
}
function request_post($url = '', $post_data = array()) {
    if (empty($url) || empty($post_data)) {
        return false;
    }
    $o = "";
    foreach ($post_data as $k => $v) {
        $o.= "$k=" . urlencode($v). "&" ;
    }
    $post_data = substr($o,0,-1);
    $postUrl = $url;
    $curlPost = $post_data;
    $ch = curl_init();
    //初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);
    //抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);
    //post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);
    //运行curl
    curl_close($ch);
    return $data;
}
?>