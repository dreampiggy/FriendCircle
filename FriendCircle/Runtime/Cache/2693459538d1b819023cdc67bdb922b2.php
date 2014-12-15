<?php if (!defined('THINK_PATH')) exit();?><!-- saved from url=(0014)about:internet -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>INFO</title>
	<link href="__CSS__/Global.css?t=1418584254" rel="stylesheet" type="text/css">
    <link href="__CSS__/Info.css?t=1418584254" rel="stylesheet" type="text/css">
    <link href="__CSS__/Moments.css?t=1418584254" rel="stylesheet" type="text/css">
	<script src="__JS__/Jquery/jquery-1.11.1.min.js?t=1418584254"></script>
	<script src="__JS__/Global.js?t=1418584254"></script>
    <script src="__JS__/Moments.js?t=1418584254"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#index").click(function(){
            window.location.href = '__index__';
        });
        $("#friends").click(function(){
            window.location.href = '__friends__';
        });
    })
    </script>
</head>
<body>
    <div class="TopBar">
        <table>
            <tr>
                <td>
                    <div class="NaviButton">
                         <font id="index">朋友圈</font>
                    </div>
                </td>
                <td>
                    <div class="NaviButton">
                        <font>我的主页</font>
                    </div>
                </td>
                <td>
                    <div class="NaviButton">
                        <font id="friends">我的好友</font>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="Frame">
        <div class="TopTitle">
            <font>MY朋友圈</font>
        </div>
        <div class="Content">
            <div class="LesserTitle">
                <font>个人主页</font>
            </div>
            <div class="InfoBox">
                <div class="InfoTitle">
                    <font>姓名</font>
                </div>
                <div class="InfoContent">
                    <font id="userName"><?php echo ($username); ?></font>
                </div>
            </div>
            <div class="InfoBox">
                <div id = "forbid" value='<?php echo ($forbid); ?>'  style="display:none"></div>
                <div class="InfoTitle">
                    <font>性别</font>
                </div>
                <div class="InfoContent">
                    <font id="userSex"><?php echo ($sex); ?></font>
                </div>
            </div>
            <div class="InfoBox">
                <div class="InfoTitle">
                    <font>电话</font>
                </div>
                <div class="InfoContent">
                    <font id="phone"><?php echo ($phone); ?></font>
                </div>
            </div>
            <div id="MomentsList">
            </div>
        </div>
    </div>
</body>
<script>
var u = document.getElementById("forbid").getAttribute('value');
if(u == 'no'){

    var url = 'http://localhost:81/FriendCircle/Friend/getUserMoment/';
    var list = new MomentsList();
    var userNameURI = "<?php echo ($username); ?>";
    url += userNameURI;
    var userSession = "<?php echo ($sessionName); ?>";
    $.post(url,{
        // username: userNameURI,
    },
    function(data){
        var moments = data.data;
        var momentsList = new Array();
        for(var i=0; i<moments.length; i++)
        {
            momentsList[i] = new Moment(moments[i].messageID, moments[i].userName, moments[i].datetime, moments[i].content);
        }
        list.addMoments(momentsList);
        list.displayList();
    }, 'json');
}
</script>