<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>错误页面</title>
	<script src="__JS__/Jquery/jquery-1.11.1.min.js?t=1418584254?t=1418290131" type="text/javascript"></script>
	<script type="text/javascript">
	$(function(){
		var wait;
		wait = document.getElementById("wait"); //必须只用javascript,否则无法innerhtml看做数字
		var interval = setInterval(function(){
			var time = --wait.innerHTML;
			if(time <= 0){
				window.location.href = '/FriendCircle';
				clearInterval(interval);
			}
		}, 1000);
	})
	</script>
</head>

<body>
	<div class="error">
		<h1 id="error-prompt">404 ERROR!</h1>
		<h2 class="auto-return"><span id="wait">4</span> seconds to return.</h2>
	</div>
</body>

</html>