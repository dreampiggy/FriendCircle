var v1=false;
var v2=false;
var v3=false;

$(function(){
	$("#return-login").click(function(){
		window.location.href = "/FriendCircle/User";
	});

	$("#usr").blur(function(){
		checkUsername();
	});

	$("#pwd").blur(function(){
		checkPassword();
	});

	$("#repwd").blur(function(){
		checkRepassword();
	});

	$("#phone").blur(function(){
		checkPhone();
	})

	$("#confirm-register").click(function(){
		$("#wait").text("");
		checkUsername();
		checkPassword();
		checkRepassword();
		checkPhone();
		if(v1 && v2 && v3 && v4)
		{
			$("#wait").text("正在注册请稍候");
			$.post('./insertUser', {
				username: $("#usr").val(),
				password: $("#pwd").val(),
				phone: 	  $("#phone").val(),
				sex: 	  $("#sex").val(),
			},
			function(data){
				if(data.status){
					$("#wait").text("成功注册");
				}
				else{
					$("#wait").text(data.info);
				}
			}, 'json');	
		}
	});
});

function checkUsername(){
	var usr = $("#usr").val();
	var error = $("#username-error");
	//reg = /[\dA-Za-z]{4,20}/;
	reg = /^[a-zA-Z0-9\u4e00-\u9fa5]+$/;
	if(!usr.replace('/[ ]/g', "")){
		error.text("请输入用户名");
		v1 = false;
	}
	else if(!reg.test(usr)){
		error.text("用户名格式错误");
		v1 = false;
	}
	else{
		error.text("");
		v1 = true;
	}
}

function checkPassword(){
	var pwd = $("#pwd").val();
	var error = $("#password-error");
	if(!pwd.replace('/[ ]/g', "")){
		error.text("请输入密码");
		v2 = false;
	}
	else{
		error.text("");
		v2 = true;
	}
}

function checkRepassword(){
	var repwd = $("#repwd").val();
	var error = $("#repassword-error");
	if(!repwd.replace('/[ ]/g', "")){
		error.text("请再次输入密码");
		v3 = false;
	}
	else{
		if(repwd == $("#pwd").val()){
			error.text("");
			v3 = true;
		}
		else{
			error.text("两次输入不一致");
			v3 = false;
		}
	}
}

function checkPhone(){
	var phone = $("#phone").val();
	var error = $("#phone-error");
	if(!phone.replace('/[ ]/g', "")){
		error.text("请输入手机号");
		v4 = false;
	}
	else{
		reg = /^[1][0-9]{10}$/;
		if(reg.test(phone)){
			error.text("");
			v4 = true;
		}
		else{
			error.text("手机号格式错误");
			v4 = false;
		}
	}
}
