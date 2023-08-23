<!DOCTYPE html>
<html>

<head>
    <title>致谢名单</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
    .quote {
      font-style: italic;
      color: #777777;
      margin-top: 20px;
      margin-bottom: 30px;
    }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #449EFF;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #3269E8;
            font-weight: bold;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }

        p {
            color: #555555;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
            margin-left: 20px;
        }

        li {
            margin: 10px 0;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }

        .tech-icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            background-color: #449EFF;
            border-radius: 50%;
            margin-right: 5px;
        }

        .fade-in {
            animation: fade-in 1.5s ease;
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .team-section {
            margin-top: 40px;
            margin-bottom: 60px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .team-member {
            width: 260px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .team-member img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .team-member h3 {
            font-size: 18px;
            color: #333333;
            margin-bottom: 5px;
        }

        .team-member p {
            color: #777777;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .support-section {
            margin-top: 40px;
        }

        .support-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .support-item {
            width: 200px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>致谢名单</h1>

        <h2>开源项目</h2>
        <p>我们团队开发的开源项目可以在以下链接访问：</p>
        <a href="https://gitee.com/codekpy/blue-star-official-website">https://gitee.com/codekpy/blue-star-official-website</a>

        <!-- 使用的库 -->
        <h2 style="color: #3573E8;">使用的库</h2>
        <ul>
            <li>
                <span class="tech-icon"></span><a href="https://github.com/PHPMailer/PHPMailer" target="_blank">phpmailer</a> - 用于发送电子邮件的强大工具。
            </li>
            <li>
                <span class="tech-icon"></span><a href="https://www.mdui.org/" target="_blank">mdui</a> - 轻量级、响应式的前端框架，提供丰富的UI组件和样式。
            </li>
            <li>
                <span class="tech-icon"></span><a href="https://jquery.com/" target="_blank">jQuery</a> - 快速、小巧、功能丰富的JavaScript库，简化了HTML文档遍历、事件处理、动画等操作。
            </li>
        </ul>

        <!-- 代码托管 -->
        <h2 style="color: #406CE8;" class="fade-in">代码托管</h2>
        <p>我们使用Gitee+Git进行代码托管，并采用持续集成和持续交付（CI/CD）流程。</p>

        <!-- 代码研发 -->
        <h2 style="color: #2F63DD;" class="fade-in">代码研发</h2>
        <p>我们使用VScode和Git工具进行代码研发和版本控制。</p>

        <!-- 开发团队 -->
        <h2 style="color: #3969E7;" class="fade-in">开发团队</h2>
        <div class="team-section">
            <div class="team-member">
                <img src="https://q1.qlogo.cn/g?b=qq&nk=1942171924&s=640" alt="Team Member 1">
                <h3>CodeKpy</h3>
                <p>项目领导者，负责项目架构和主要功能开发。</p>
            </div>
            <div class="team-member">
                <img src="https://q1.qlogo.cn/g?b=qq&nk=3174251894&s=640" alt="Team Member 2">
                <h3>小宏</h3>
                <p>前端开发者，负责用户界面设计和前端开发。</p>
            </div>
            <div class="team-member">
                <img src="https://q1.qlogo.cn/g?b=qq&nk=3260130869&s=640" alt="Team Member 3">
                <h3>技术鸭</h3>
                <p>前端开发者，负责前端开发和界面美化。偶尔提点建议</p>
            </div>
        </div>

        <!-- 支持人员 -->
        <h2 style="color: #2A5EDD;" class="fade-in">支持人员</h2>
        <div class="support-section">
            <div class="support-list">
                <div class="support-item">
                    <h3>Alcex</h3>
                    <p>感谢您的支持和帮助。</p>
                </div>
                <div class="support-item">
                    <h3>牛牛之家</h3>
                    <p>特别感谢您的支持和帮助。</p>
                </div>

            </div>
        </div>
        <p>以上名单仅列出了部分贡献者，感谢所有给予支持和帮助的人。</p>
    <div class="quote">
      <p>"团队合作是我们取得成功的关键。感谢所有参与项目的人员，你们的努力和贡献无可替代。"</p>
      <p>- CodeKpy</p>
    </div>
    </div>
</body>

</html>