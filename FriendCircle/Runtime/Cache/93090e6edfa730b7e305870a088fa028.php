<?php if (!defined('THINK_PATH')) exit();?><!-- saved from url=(0014)about:internet -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>MOMENTS</title>
	<link href="__CSS__/Global.css?t=1418584254?t=1418576132" rel="stylesheet" type="text/css">
    <link href="__CSS__/Moments.css?t=1418584254?t=1418576132" rel="stylesheet" type="text/css">
	<script src="__JS__/Jquery/jquery-1.11.1.min.js?t=1418584254?t=1418576132"></script>
	<script src="__JS__/Global.js?t=1418584254?t=1418576132"></script>
    <script src="__JS__/Moments.js?t=1418584254?t=1418576132"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#myinfo").click(function(){
            window.location.href = '__info__/<?php echo ($_SESSION["username"]); ?>';
        });
        $("#friends").click(function(){
            window.location.href = '__friends__';
        });
        $("#log-out").click(function(){
            window.location.href = '__logout__';
        });
        $("#refresh").click(function(){
            window.location.href = '__index__';
        })
    })
    </script>
</head>
<body>
    <div class="TopBar">
        <table>
            <tr>
                <td>
                    <div class="NaviButton">
                        <font>朋友圈</font>
                    </div>
                </td>
                <td>
                    <div class="NaviButton">
                        <font id="myinfo">我的主页</font>
                    </div>
                </td>
                <td>
                    <div class="NaviButton">
                        <font id="friends">我的好友</font>
                    </div>
                </td>
                <td>
                    <div class="NaviButton">
                        <font id="log-out">退出</font>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="Frame">
        <div class="TopTitle">
            <font>MY朋友圈</font>
        </div>
        <div class="Content" id="mainContent">
            <div class="LesserTitle">
                <font>朋友圈</font>
            </div>
            <div class="TextAlertRow">
                <font id="refresh" style="cursor: pointer;">刷新</font>
            </div>
            <div id="MomentsList">
            </div>
        </div>
    </div>
</body>
<script>

    var url = '__index__/Friend/getMoment';
    var list=new MomentsList();
    $.post(url,function(data){
        var moments = data.data;
        var momentsList = new Array();
        for(var i=0; i<moments.length; i++)
        {
            momentsList[i] = new Moment(moments[i].messageID, moments[i].userName, moments[i].datetime, moments[i].content);
        }
        list.addMoments(momentsList);
        list.displayList();
    }, 'json');
</script>