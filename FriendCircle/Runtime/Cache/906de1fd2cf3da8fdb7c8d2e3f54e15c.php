<?php if (!defined('THINK_PATH')) exit();?><!-- saved from url=(0014)about:internet -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>REGISTER</title>
	<style>
	</style>
	<link href="__CSS__/Global.css?t=1418584254" rel="stylesheet" type="text/css">
	<script src="__JS__/Jquery/jquery-1.11.1.min.js?t=1418584254"></script>
	<script src="__JS__/Global.js?t=1418584254">
	</script>
</head>
<body>
    <div class="Frame">
        <div class="TopTitle">
            <font>MY朋友圈</font>
        </div>
        <div class="Content">
            <div class="LesserTitle" id="wait">
                <font>用户注册</font>
            </div>
            <div class="TextAlertRow">
                <font id="return-login">老用户登录</font>
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
                                <input class="FormInput" type="text" id="usr"></input>
                                <font><span class="error-prompt" id="username-error"></span></font>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="FormColumnLeftPart">
                                <font>性别</font>
                            </div>
                        </td>
                        <td>
                            <div class="FormColumnRightPart">
                                <select name="sexSelect" class="FormInput" id="sex">
                                    <option value="Male">男</option>
                                    <option value="Female">女</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="FormColumnLeftPart">
                                <font>电话</font>
                            </div>
                        </td>
                        <td>
                            <div class="FormColumnRightPart">
                                <input class="FormInput" type="text" id="phone"></input>
                                <font><span class="error-prompt" id="phone-error"></span></font>
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
                                <input class="FormInput" type="password" id="pwd"></input>
                                <font><span class="error-prompt" id="password-error"></span></font>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="FormColumnLeftPart">
                                <font>重复密码</font>
                            </div>
                        </td>
                        <td>
                            <div class="FormColumnRightPart">
                                <input class="FormInput" type="password" id="repwd"></input>
                                <font><span class="error-prompt" id="repassword-error"></span></font>
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="ButtonIMG" id="confirm-register">
                </div>
            </div>
        </div>
    </div>
    <script src="__JS__/Account/register.js?t=1418584254" type="text/javascript"></script>
</body>