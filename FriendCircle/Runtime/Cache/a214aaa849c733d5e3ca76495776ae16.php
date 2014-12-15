<?php if (!defined('THINK_PATH')) exit();?><!-- saved from url=(0014)about:internet -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>FRIENDS</title>
	<style>
	</style>
	<link href="__CSS__/Global.css?t=1418584254?t=1418540840" rel="stylesheet" type="text/css">
    <link href="__CSS__/Friends.css?t=1418584254?t=1418540840" rel="stylesheet" type="text/css">
	<script src="__JS__/Jquery/jquery-1.11.1.min.js?t=1418584254?t=1418540840"></script>
	<script src="__JS__/Global.js?t=1418584254?t=1418540840"></script>
	<script src="__JS__/Friends.js?t=1418584254?t=1418540840"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#myinfo").click(function(){
            window.location.href = '__info__/<?php echo ($_SESSION["username"]); ?>';
        });

        $("#index").click(function(){
            window.location.href = '__index__';
        });

    });
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
                        <font id="myinfo">我的主页</font>
                    </div>
                </td>
                <td>
                    <div class="NaviButton">
                        <font>我的朋友</font>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="Frame">
        <div class="TopTitle">
            <font>MY朋友圈</font>
        </div>
        <div class="Content" id="content">
            <div class="LesserTitle">
                <font>我的朋友</font>
            </div>
            <div class="DemandBox" id="demandBox">
                <font id="prompt">暂无任何请求</font>
            </div>
        </div>
    </div>
</body>
<script>
    var friendList = new FriendList();
    $.post('../Friend/getFriendList',
        function(data){
            var friend = data.data;
            for(var i=0; i<friend.length; i++){
                friendList.addFriend(new Friend(friend[i]));
            }
            friendList.displayAll();
        } ,'json');

    var demandList = new FriendDemandList();
    $.post('../Friend/getFriendRequest', 
        function(data){
            var demand = data.data;
            for(var i=0; i<demand.length; i++){
                demandList.addDemand(new FriendDemand(demand[i]));
            }
            if(demand.length>0)
            {
                $("#prompt").text("");
            }
            demandList.displayAll();
        }, 'json');
    
</script>