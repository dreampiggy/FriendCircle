
var v1=false;
var v2=false;

//判断其中是否含有数字和字母以外的字符
$(function(){
	$("#username").blur(function(){
		checkUsername();
	});

	$("#password").blur(function(){
		checkPassword();
	});

	$("#confirm-login").click(function(){	
		$("#wait").text("");
		checkUsername();
		checkPassword();
		if(v1 && v2){
			$.post('./checkLogin', {
				username: $('#username').val(),
				password: $('#password').val(),
			}, 
			function(data){
				if(data.status){
					window.location.href = './';
				}
				else{
					$('#wait').text(data.info);
				}
			}, 'json');
		}
		else
		{
			$("#wait").text("请正确输入");
		}
	});

	$("#register-jump").click(function(){
		window.location.href = "./register";
	});
})

function checkUsername(){
	var username = $("#username").val();
	//reg = /[^\da-zA-Z]/;
	reg = /^[a-zA-Z0-9\u4e00-\u9fa5]+$/;
	if(!username.replace(/[ ]/g, "")){
		$("#username-error").text("请输入用户名");
		v1 = false;
	}
	else if(!reg.test(username)){
		$("#username-error").text("用户名格式错误");
		v1 = false;
	}
	else{
		$("#username-error").text("");
		v1 = true;
	}
}

function checkPassword(){
	var password = $("#password").val();
	reg = /[^\da-zA-Z]/;
	if(!password.replace(/[ ]/g, "")){
		$("#password-error").text("请输入密码");
		v2 = false;
	}
	else if(reg.test(password)){
		$("#password-error").text("密码错误");
		v2 = false;
	}
	else{
		$("#password-error").text("");
		v2 = true;
	}
}