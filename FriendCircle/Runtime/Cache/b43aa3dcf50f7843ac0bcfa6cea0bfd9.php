<?php if (!defined('THINK_PATH')) exit();?><!-- saved from url=(0014)about:internet -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>LOGIN</title>
	<link href="__CSS__/Global.css?t=1418584254" rel="stylesheet" type="text/css">
	<script src="__JS__/Jquery/jquery-1.11.1.min.js?t=1418584254"></script>
	<script src="__JS__/Jquery/jquery.form.js?t=1418584254" type="text/javascript"></script>
	<script src="__JS__/Global.js?t=1418584254"></script>
</head>
<body>
    <div class="Frame">
        <div class="TopTitle">
            <font>MY朋友圈</font>
        </div>
        <div class="Content">
            <div class="LesserTitle" >
                <font id="wait">用户登陆</font>
            </div>
            <div class="TextAlertRow">
                <font id="register-jump">新用户注册</font>
            </div>
            <div class="Form">
                <table>
                    <tr>
                        <td>
                            <div class="FormColumnLeftPart">
                                <font>姓名</font>
                            </div>
                        </td>
                        <td>
                            <div class="FormColumnRightPart">
                                <input class="FormInput" type="text" id="username"></input>
                                <span class="error-prompt" id="username-error"></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="FormColumnLeftPart">
                                <font>密码</font>
                            </div>
                        </td>
                        <td>
                            <div class="FormColumnRightPart">
                                <input class="FormInput" type="password" id="password"></input>
                                <span class="error-prompt" id="password-error"></span>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="ButtonIMG" id="confirm-login">
                </div>
            </div>
        </div>
    </div>
    <script src="__JS__/Account/login.js?t=1418584254" type="text/javascript"></script>
</body>
</html>