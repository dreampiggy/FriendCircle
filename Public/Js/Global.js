    var __info__ = 'http://localhost:81/FriendCircle/Index/info/';
    var __friends__ = 'http://localhost:81/FriendCircle/Index/friends/';
    var __logout__ = 'http://localhost:81/FriendCircle/User/logout/';
    var __Friend__ = 'http://localhost:81/FriendCircle/Friend/';
    var __index__ = 'http://localhost:81/FriendCircle/';
function goAjax(method,sorce,flag)
{
    //运行ajax
	var ajaxCondition;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		ajaxCondition=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		ajaxCondition=new ActiveXObject("Microsoft.XMLHTTP");
	}
	ajaxCondition.open("POST",sorce,flag );
	ajaxCondition.send();
    return ajaxCondition.responseText;
}

function InputBar(tid,tDiv,l)
{
    var targetID=tid;

    var level=l;

    var inputBarDiv=document.createElement("div");
    var inputBox=document.createElement("input");
    var isAnonymousDiv=document.createElement("div");
    var submitButtonDiv=document.createElement("div");

    var isAnonymous=false;

    this.showInputBar=function()
    {
        //显示输入框
        $(tDiv).append(inputBarDiv);
        $(inputBarDiv).addClass("InputBar");
        inputBarDiv.style.width=inputBarDiv.offsetWidth-(level+1)*64+"px";
        $(inputBarDiv).append(inputBox);
        $(inputBox).addClass("InputField");
        if(targetID!="NewFriend")
        {
            $(inputBarDiv).append(isAnonymousDiv);
            isAnonymousDiv.innerHTML="<input type='checkbox' name='anonymous' /><font>匿名发布</font>";
        }
        inputBox.style.width=inputBarDiv.offsetWidth-32+"px";
        $(inputBarDiv).append(submitButtonDiv);
        $(submitButtonDiv).addClass("ButtonIMG");
        $(submitButtonDiv).addClass("Clickable");
        $(submitButtonDiv).click(function(){
            //点击提交之后的动作
            if(targetID=="PublishMoment")
            {
                //发布新状态
                isAnonymous=document.getElementsByName("anonymous")[0].checked;
                var isAnonymousValue = isAnonymous ? 1 : 0 ;
              //  window.location.href = '__Friend__/addMoment';
                $.post(__Friend__+'addMoment', {
                    anonymity: isAnonymousValue,
                    content: inputBox.value, 
                }, function(data){
                    alert(data.info);
                    location.reload();
                }, 'json');
            }
            else if(targetID=="NewFriend")
            {
                $.post(__Friend__+'setFriendRequest/'+inputBox.value,
                function(data){
                    alert(data.info);
                    location.reload();
                }, 'json');
            }
            else
            {
                isAnonymous=document.getElementsByName("anonymous")[0].checked;
                var isAnonymousValue = isAnonymous ? 1 : 0 ;
                var IDstr=new Array();
                IDstr=targetID.split("/");
                var toParentID=IDstr[0];
                var toRootID=IDstr[1];
                $.post(__Friend__+'addComment/'+toRootID+'/'+toParentID+'/'+inputBox.value+'/'+isAnonymousValue,
                function(data){
                    if(data.status){
                        alert('成功');
                        location.reload();
                    }
                    else{
                        alert('失败');
                        location.reload();
                    }
                }, 'json'); 
            }
        });
    }
}
