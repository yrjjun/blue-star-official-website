<?php

$file_content = file_get_contents("./app/install/install.lock");
if ($file_content=="1"){//未安装
    file_put_contents("./config.php", '<?php $host="'.$_REQUEST["db_host"].'";$user="'.$_REQUEST["db_user"].'";$dbname="'.$_REQUEST["db_name"].'";$password="'.$_REQUEST["db_password"].'";');
    $host = $_REQUEST["db_host"]; // 数据库主机名
    $user = $_REQUEST["db_user"]; // 数据库用户名
    $password = $_REQUEST["db_password"]; // 数据库密码
    $dbname = $_REQUEST["db_name"]; // 数据库名
    $conn = mysqli_connect($host, $user, $password, $dbname);
    // 创建 people 表
    $sql = "CREATE TABLE blue_user (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email TEXT,
        password TEXT,
        token TEXT,
        ip TEXT,
        time INT(20),
        risk INT(3),
        money FLOAT,
        status TEXT,
        name TEXT,
        img TEXT)";
    if (mysqli_query($conn, $sql)) {
        
    } else {
       die("用户表建立失败: " . mysqli_error($conn));
    }
        // 创建 config 表
        $sql = "CREATE TABLE blue_config (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name TEXT,
            value TEXT
        )";
        if (mysqli_query($conn, $sql)) {
            
        } else {
           die("配置表建立失败: " . mysqli_error($conn));
        }
    // 创建 产品卡片 表
    $sql = "CREATE TABLE blue_product_card (
            id VARCHAR(20) PRIMARY KEY,
            name TEXT,
            about TEXT,
            img TEXT,
            url TEXT)";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("产品卡片表建立失败: " . mysqli_error($conn));
    }
    // 创建 验证码 表
    $sql = "CREATE TABLE blue_code (
            email VARCHAR(50) PRIMARY KEY,
            text TEXT,
            time INT(11),
            code INT(11))";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("邮件验证码表建立失败: " . mysqli_error($conn));
    }
    // 创建 产品 表
    $sql = "CREATE TABLE blue_product (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `from` TEXT,
        name TEXT,
        code TEXT,
        buy TEXT,
        admin TEXT,
        money FLOAT,
        money_mode TEXT,
        about TEXT,
        host TEXT
    );
    ";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("产品表建立失败: " . mysqli_error($conn));
    }
    // 创建 服务 表
    $sql = "CREATE TABLE blue_service (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(20),
            product_id TEXT,
            time TEXT,
            appid TEXT,
            token TEXT,
            `long` INT,
            admin TEXT,
            name TEXT,
            data TEXT,
            times INT
            );";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("服务表建立失败: " . mysqli_error($conn));
    }
        // 创建 nav 表
    $sql = "CREATE TABLE blue_nav (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name TEXT,
            url TEXT);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("nav表建立失败: " . mysqli_error($conn));
    }
        // 创建 友链 表
    $sql = "CREATE TABLE blue_friendurl (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name TEXT,
            image TEXT,
            about TEXT,
            status INT,
            url TEXT);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("友链表建立失败: " . mysqli_error($conn));
    }
    // 创建 产品分类 表
    $sql = "CREATE TABLE blue_product_from (
            name VARCHAR(50) PRIMARY KEY);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("产品分类表建立失败: " . mysqli_error($conn));
    }
    // 创建 虚拟主机母机配置 表
    $sql = "CREATE TABLE blue_vhost_vps (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50),
            host_url TEXT,
            user_name TEXT,
            user_key TEXT,
            buy TEXT,
            host_ip TEXT,
            money INT,
            about TEXT,
            max INT);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("虚拟主机母机配置表建立失败: " . mysqli_error($conn));
    }
    // 创建 团队开发人员 表
    $sql = "CREATE TABLE blue_team_members (
            name VARCHAR(50) PRIMARY KEY,
            about TEXT,
            img_url TEXT);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("团队开发人员表建立失败: " . mysqli_error($conn));
    }
    // 创建 操作日志 表
    $sql = "CREATE TABLE blue_log (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            text TEXT,
            user TEXT,
            ip TEXT,
            time INT,
            code INT);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("操作日志表建立失败: " . mysqli_error($conn));
    }
    // 创建 访问IP 表
    $sql = "CREATE TABLE `access_log` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `ip` varchar(15) DEFAULT NULL,
        `timestamp` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      ";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("访问IP表建立失败: " . mysqli_error($conn));
    }
    // 创建 帖子 表
    $sql = "CREATE TABLE `blue_post` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			user_id INT,
			title TEXT,
			text TEXT,
			time INT,
			ip TEXT,
			goods INT,
			views INT,
			likes INT,
			ishot INT,
			ison INT,
			isbest INT,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      ";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("访问帖子表建立失败: " . mysqli_error($conn));
    }
    // 创建 评论 表
    $sql = "CREATE TABLE `blue_talk` (
        `id` INT UNSIGNED AUTO_INCREMENT,
		user_id INT,
		text TEXT,
		time INT,
		ip TEXT,
        post_id INT,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      ";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("评论表建立失败: " . mysqli_error($conn));
    }
    // 创建 邮件验证码的专属 表
    $sql = "CREATE TABLE blue_smtp_code (
            `email` varchar(50) PRIMARY KEY,
            code INT,
            time INT);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("邮件验证码专属表建立失败: " . mysqli_error($conn));
    }
    // 创建 订单 表
    $sql = "CREATE TABLE blue_pay (
            `id` varchar(25) PRIMARY KEY,
            user_id INT,
            time INT,
            money FLOAT,
            code INT);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("订单表建立失败: " . mysqli_error($conn));
    }
    // 创建 新品 表
    $sql = "CREATE TABLE blue_new_things (
            `id` varchar(25) PRIMARY KEY,
            url TEXT,
            time TEXT,
            name TEXT,
            img TEXT,
            about TEXT);";
    if (mysqli_query($conn, $sql)) {
                    
    } else {
        die("新品表建立失败: " . mysqli_error($conn));
    }
    // 创建 issue 表
    $sql = "CREATE TABLE blue_issue (
            `id` varchar(25) PRIMARY KEY,
            name TEXT,
            text TEXT,
            members TEXT);";
    if (mysqli_query($conn, $sql)) {
    } else {
        die("issue表建立失败: " . mysqli_error($conn));
    }
    // 创建 短链 表
    $sql = "CREATE TABLE blue_urls (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        url VARCHAR(255) NOT NULL,
        shortcode VARCHAR(10) NOT NULL
    );";
    if (mysqli_query($conn, $sql)) {
    } else {
        die("短链表建立失败: " . mysqli_error($conn));
    }
    // 创建 功能开关 表
    $sql = "CREATE TABLE blue_function (
        id INT(11) PRIMARY KEY,
        `status` INT,
        name TEXT);";
    if (mysqli_query($conn, $sql)) {
    } else {
        die("功能开关表建立失败: " . mysqli_error($conn));
    }
    // 创建验证码表
    $sql = "CREATE TABLE IF NOT EXISTS blue_verify_code (
        client_id varchar(32) NOT NULL,
        code varchar(11) NOT NULL,
        create_time INT NOT NULL,
        PRIMARY KEY (id)
    )";
    mysqli_query($conn, $sql);

    // 创建验证码表
    $sql = "CREATE TABLE IF NOT EXISTS blue_works (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title TEXT,

    )";
    mysqli_query($conn, $sql);

    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('admin','".$_REQUEST["key"]."')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('websitename','".$_REQUEST["websitename"]."')");
    mysqli_query($conn, "INSERT INTO `blue_nav`(`name`,`url`) VALUES ('论坛','".$_REQUEST["shequ_url"]."')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('about','    BlueStarNet是一家专业的互联网服务提供商，由 CodeKpy 成立于2023年3月3日。自成立以来，平台已经吸引了114位用户注册，并为这些用户提供了114514次登录服务。<br>   在我们的屏蔽词API里，我们共访问了1145次，并为用户提供了更加安全可靠的网络环境。在邮件代发平台里，我们还为广大用户提供了114次发邮件服务，让用户可以更加便捷地进行邮件发送。<br>  此外，PHP助手也是我们平台中的一项重要工具，在这个工具内，我们的用户使用了114次。<br>    此外，PHP助手也是我们平台中的一项重要工具，在这个工具内，我们的用户使用了114次。<br>    在未来，我们将继续努力，为广大用户提供更加优质的互联网服务，并为他们创造更加美好的体验。')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('smtp_host','".$_REQUEST["smtp_host"]."')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('smtp_email','".$_REQUEST["smtp_email"]."')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('smtp_port','".$_REQUEST["smtp_port"]."')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('smtp_password','".$_REQUEST["smtp_password"]."')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('admin_url','".$_REQUEST["admin_url"]."')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('websiteurl','".$_REQUEST["websiteurl"]."')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('easypay_url','')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('easypay_pid','')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('bing_key_url','')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('domain','')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('bing_key','')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('easypay_key','')");
    mysqli_query($conn, "INSERT INTO `blue_config`(`name`,`value`) VALUES ('qq_group','')");
    mysqli_query($conn, "INSERT INTO `blue_product_card`(`id`,`name`,`about`,`img`,`url`) VALUES ('1','虚拟主机','超低价，快速，稳定的虚拟主机','./file/image/index.png','http://vhost.bluestarnet.top')");
    mysqli_query($conn, "INSERT INTO `blue_product_card`(`id`,`name`,`about`,`img`,`url`) VALUES ('2','二级域名分发','解析快速的分发平台','./file/image/index.png','http://domain.bluestarnet.top')");
    mysqli_query($conn, "INSERT INTO `blue_product_card`(`id`,`name`,`about`,`img`,`url`) VALUES ('3','PHP助手','高效的PHP建站助手','./file/image/index.png','http://phph.bluestarnet.top')");
    mysqli_query($conn, "INSERT INTO `blue_nav`(`name`,`url`) VALUES ('虚拟主机','http://vhost.bluestarnet.top/')");
    mysqli_query($conn, "INSERT INTO `blue_nav`(`name`,`url`) VALUES ('api接口','./?path=page&page=sell')");
    mysqli_query($conn, "INSERT INTO `blue_product_from`(`name`) VALUES ('api')");
    mysqli_query($conn, "INSERT INTO `blue_team_members`(`name`,`about`,`img_url`) VALUES ('CodeKpy','官网框架开发','https://q1.qlogo.cn/g?b=qq&nk=1942171924&s=640')");
    mysqli_query($conn, "INSERT INTO `blue_team_members`(`name`,`about`,`img_url`) VALUES ('Inventocode','贡献人员','https://q1.qlogo.cn/g?b=qq&nk=359148497&s=640')");
    mysqli_query($conn, "INSERT INTO `blue_team_members`(`name`,`about`,`img_url`) VALUES ('技术鸭','官网前端开发','https://q1.qlogo.cn/g?b=qq&nk=3260130869&s=640')");
    mysqli_query($conn, "INSERT INTO `blue_team_members`(`name`,`about`,`img_url`) VALUES ('小宏XeLa','官网前端开发','https://q1.qlogo.cn/g?b=qq&nk=3174251894&s=640')");
    mysqli_query($conn, "INSERT INTO `blue_product`(`from`,`name`,`code`,`buy`,`admin`,`money`,`money_mode`, `about`) VALUES ('api','SMTP EMAIL接口','', 'email_api.php', 'email_api', '0.005', 'times', '这是由BlueStarNet团队运营的SMTP邮件接口，已运营6个月，安全稳定，欢迎使用！')");
    $sql="  INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('1','1','短链功能');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('2','1','主页');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('3','1','邮件验证码');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('4','1','邮件代发');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('5','1','CoCo接口');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('6','1','充值接口');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('7','1','登录功能');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('8','1','注册功能');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('9','1','api开通功能');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('10','1','api续费功能');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('11','1','QQ群组跳转功能');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('12','1','社区');
            INSERT INTO `blue_function`(`id`,`status`,`name`) VALUES ('13','1','工单系统');
    ";
    mysqli_multi_query($conn, $sql);
    
    $admin_url=$_REQUEST["admin_url"];
    echo "200";
    file_put_contents("./app/install/install.lock", "已安装");
}
