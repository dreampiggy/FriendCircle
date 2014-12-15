function Friend(name)
{
    var friendName=name;

    this.display=function()
    {
        var friendDiv=document.createElement("div");
        $("#content").append(friendDiv);
        $(friendDiv).addClass("FriendBox");
        var friendTitle=document.createElement("font");
        $(friendDiv).append(friendTitle);
        friendTitle.innerHTML="<font>"+friendName+"<font>";
        var friendDetailDiv=document.createElement("div");
        $(friendDiv).append(friendDetailDiv);
        $(friendDetailDiv).addClass("FriendDetail");
        var twoLaneTable=document.createElement("table");
        $(friendDetailDiv).append(twoLaneTable);
        var tableTr=document.createElement("tr");
        $(twoLaneTable).append(tableTr);
        var tableTdLeft=document.createElement("td");
        $(tableTr).append(tableTdLeft);
        var tableTdRight=document.createElement("td");
        $(tableTr).append(tableTdRight);
        var sheildHimDiv=document.createElement("div");
        $(tableTdLeft).append(sheildHimDiv);
        $(sheildHimDiv).addClass("FriendDoubleLaneColumn");
        $(sheildHimDiv).addClass("LeftPart");
        sheildHimDiv.innerHTML="<font>屏蔽该用户动态</font>";
        var sheildMeDiv=document.createElement("div");
        $(tableTdRight).append(sheildMeDiv);
        $(sheildMeDiv).addClass("FriendDoubleLaneColumn");
        $(sheildMeDiv).addClass("RightPart");
        sheildMeDiv.innerHTML="<font>禁止对方访问我</font>";
        $(sheildHimDiv).addClass("Clickable");
        $(sheildHimDiv).click(function(){
          //阻止我看对方的动态
          $.post('http://localhost:81/FriendCircle/Friend/setForbidMessage/'+friendName, 
            function(data){
                if(data.info=="OK")
                {
                    alert("操作成功");
                }
                else
                {
                    alert("操作失败");
                }
            }, 'json');

        });
        $(sheildMeDiv).addClass("Clickable");
        $(sheildMeDiv).click(function(){
          //阻止对方看我
            $.post('http://localhost:81/FriendCircle/Friend/setForbidInfo/'+friendName, 
            function(data){
                if(data.info=="OK")
                {
                    alert("操作成功");
                }
                else
                {
                    alert("操作失败");
                }
            }, 'json');
        });
        $(friendTitle).addClass("Clickable");
        $(friendTitle).click(function(){
            window.location.href = 'http://localhost:81/FriendCircle/Index/info/'+friendName;
        });
    }
}

function FriendList()
{
    var friendList=new Array();

    this.addFriend=function(friend)
    {
        var l=friendList.length;
        friendList[l]=friend;
    }

    this.displayAll=function()
    {
        for(var i=0;i<friendList.length;i++)
        {
            friendList[i].display();
        }
    }
}

function FriendDemand(fName)
{
    var fromName=fName;

    this.display=function()
    {
        var demandDiv=document.createElement("div");
        $("#demandBox").append(demandDiv);
        $(demandDiv).addClass("DemandColumn");
        demandDiv.innerHTML="<font>"+fromName+" 向你发送了好友请求.</font>";
        var twoLaneTable=document.createElement("table");
        $("#demandBox").append(twoLaneTable);
        var tableTr=document.createElement("tr");
        $(twoLaneTable).append(tableTr);
        var tableTdLeft=document.createElement("td");
        $(tableTr).append(tableTdLeft);
        var tableTdRight=document.createElement("td");
        $(tableTr).append(tableTdRight);
        var acceptDiv=document.createElement("div");
        $(tableTdLeft).append(acceptDiv);
        $(acceptDiv).addClass("FriendDoubleLaneColumn");
        $(acceptDiv).addClass("LeftPart");
        acceptDiv.innerHTML="<font>接受</font>";
        var rejectDiv=document.createElement("div");
        $(tableTdRight).append(rejectDiv);
        $(rejectDiv).addClass("FriendDoubleLaneColumn");
        $(rejectDiv).addClass("RightPart");
        rejectDiv.innerHTML="<font>驳回</font>";
        $(acceptDiv).addClass("Clickable");
        $(acceptDiv).click(function(){
            $.post('http://localhost:81/FriendCircle/Friend/setFriendRespond/'+fromName+'/agree',
            function(data){
                alert(data.info);
                window.location.href = 'http://localhost:81/FriendCircle';
            }, 'json');
        });
        $(rejectDiv).addClass("Clickable");
        $(rejectDiv).click(function(){
            $.post('http://localhost:81/FriendCircle/Friend/setFriendRespond/'+fromName+'/reject',
            function(data){
                alert(data.info);
                window.location.href = 'http://localhost:81/FriendCircle';
            }, 'json');
        });
    }
}

function FriendDemandList()
{
    var demandList=new Array();

    var exsistNewFriendBar=false;

    this.addDemand=function(demand)
    {
        var l=demandList.length;
        demandList[l]=demand;
    }

    this.displayAll=function()
    {
        for(var i=0;i<demandList.length;i++)
        {
            demandList[i].display();
        }
        var newFriendButtonDiv=document.createElement("div");
        $("#content").append(newFriendButtonDiv);
        $(newFriendButtonDiv).addClass("TextAlertRow");
        newFriendButtonDiv.innerHTML="<font>添加新好友</font>";
        $(newFriendButtonDiv).addClass("Clickable");
        $(newFriendButtonDiv).click(function(){
            if(!exsistNewFriendBar)
            {
                var inputBar=new InputBar("NewFriend",$("#content"),0);
                inputBar.showInputBar();
                exsistNewFriendBar=true;
            }
        });
    }
}
