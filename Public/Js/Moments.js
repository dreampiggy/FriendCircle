    var __info__ = 'http://localhost:81/FriendCircle/Index/info/';
    var __friends__ = 'http://localhost:81/FriendCircle/Index/friends/';
    var __logout__ = 'http://localhost:81/FriendCircle/User/logout/';
    var __Friend__ = 'http://localhost:81/FriendCircle/Friend/';
    var __index__ = 'http://localhost:81/FriendCircle/';
function Comment(id,aName,time,content,pid,rid)
{
    var commentID=id;
    var authorName=aName;
    var commentTime=time;
    var commentContent=content;
    var parentID=pid;
    var rootID=rid;
    
    var isSubComment=false;
    
    var exsistCommentBar=false;
    
    this.getID=function()
    {
        return commentID;
    }
    
    this.getTime=function()
    {
        return commentTime;
    }
    
    this.getContent=function()
    {
        return commentContent;
    }
    
    this.getParentID=function()
    {
        return parentID;
    }
    
    this.getRootID=function()
    {
        return rootID;
    }
    
    this.setAsSubComment=function()
    {
        isSubComment=true;
    }
    
    this.displayComment=function(momentDiv)
    {
        var commentDiv=document.createElement("div");
        $(momentDiv).append(commentDiv);
        $(commentDiv).addClass("Comment");
        if(isSubComment)
        {
            $(commentDiv).addClass("SubComment");
        }
        var authorField=document.createElement("font");
        $(commentDiv).append(authorField);
        authorField.innerHTML=authorName+": ";
        var contentField=document.createElement("font");
        $(commentDiv).append(contentField);
        contentField.innerHTML=commentContent;
        $(authorField).addClass("ClickAble");
        $(authorField).click(function(){
            if(authorName != "匿名用户")
            {
                window.location.href = __info__+authorName;
            }
        });
        $(contentField).addClass("ClickAble");
        $(contentField).click(function(){
            if(!exsistCommentBar)
            {
                var level=1;
                if(isSubComment)
                {
                    level=2;
                }
                var inputBar=new InputBar(commentID+"/"+rootID,commentDiv,level);
                inputBar.showInputBar();
                exsistCommentBar=true;
            }
        });
    }
}

function Moment(id,aName,time,content)
{
    var momentID=id;
    var authorName=aName;
    var momentTime=time;
    var momentContent=content;
    
    var momentDiv=document.createElement("div");
    
    var commentsList=new Array();
    
    var exsistCommentBar=false;
    
    var onScreen=false;

    var commentIsShown=false;
    
    this.getID=function()
    {
        return momentID;
    }
    
    this.getTime=function()
    {
        return momentTime;
    }
    
    this.getContent=function()
    {
        return momentContent;
    }
    
    function addComment(comment)
    {
        var l=commentsList.length;
        commentsList[l]=comment;
    }
    
    function sort()
    {
        var sortedComments=new Array();
        var tempList=commentsList.slice(0);
        var iRoot=0;
        for(var i=0;i<commentsList.length;i++)
        {
            if(commentsList[i].getParentID()==momentID)
            {
                sortedComments[iRoot]=commentsList[i];
                tempList.splice(i-iRoot, 1);
                iRoot++;
            }
        }
        var operated=true;
        while((operated)&&(tempList.length>0))
        {
            operated=false;
            for(var i=0;i<tempList.length;i++)
            {
                var parent=tempList[i].getParentID();
                for(var j=0;j<sortedComments.length;j++)
                {
                    if(parent==sortedComments[j].getID())
                    {
                        sortedComments.splice(j+1,0,tempList[i]);
                        sortedComments[j+1].setAsSubComment();
                        tempList.splice(i,1);
                        operated=true;
                        break;
                    }
                }
                if(operated)
                {
                    break;
                }
            }
        }
        commentsList=sortedComments;
    }

    function displayComment()
    {
        sort();
        for(var i=0;i<commentsList.length;i++)
        {
            commentsList[i].displayComment(momentDiv);
        }
    }

    this.displayMoment=function()
    {
        if(!onScreen)
        {
            $("#MomentsList").append(momentDiv);
            $(momentDiv).addClass("Moment");
            var timeMarkDiv=document.createElement("div");
            $(momentDiv).append(timeMarkDiv);
            $(timeMarkDiv).addClass("LesserMark");
            timeMarkDiv.innerHTML="<font>"+momentTime+"</font>";
            var momentMainDiv=document.createElement("div");
            $(momentDiv).append(momentMainDiv);
            $(momentMainDiv).addClass("MomentMain");
            var authorField=document.createElement("font");
            $(momentMainDiv).append(authorField);
            authorField.innerHTML=authorName+": ";
            var contentField=document.createElement("font");
            $(momentMainDiv).append(contentField);
            contentField.innerHTML=momentContent;
            var loadCommentDiv=document.createElement("div");
            $(momentDiv).append(loadCommentDiv);
            $(loadCommentDiv).addClass("LesserMark");
            loadCommentDiv.innerHTML="<font>载入评论</font>";
            $(authorField).addClass("ClickAble");
            $(authorField).click(function(){
                if(authorName != "匿名用户")
                {
                    window.location.href = __info__+authorName;
                }
            });
            $(contentField).addClass("ClickAble");
            $(contentField).click(function(){
                if(!exsistCommentBar)
                {
                    var inputBar=new InputBar(momentID+"/"+momentID,momentDiv,0);
                    inputBar.showInputBar();
                    exsistCommentBar=true;
                }
            });
            $(loadCommentDiv).addClass("ClickAble");
            $(loadCommentDiv).click(function(){
                if(!commentIsShown)
                {
                    $.post(__Friend__+'getComment/'+momentID,
                    function(data){
                        var result = data.data;
                        if(result!=null)
                        {
                            for(var i=0; i<result.length; i++)
                            {
                                var comment = new Comment(result[i].commentID, result[i].userName, result[i].datetime, result[i].content, result[i].parentID, result[i].messageID);
                                addComment(comment);
                            }
                            displayComment();
                        }
                        commentIsShown = true;
                    }, 'json'); 
                }
            });
            onScreen=true;
        }
    }
}

function MomentsList()
{
    var momentsList=new Array();
    
    var publishDiv=document.createElement("div");
    
    var exsistPublishBar=false;
    
    this.addMoments=function(moments)
    {
        var l=momentsList.length;
        momentsList[l]=moments;
    }
    
    function mergeMoment(initListLeft,initListRight)
    {
        var i1=0;
        var i2=0;
        var iResult=0;
        var mergedList=new Array();
        for(i1=0,iResult=0,i2=0 ;i1<initListLeft.length&&i2<initListRight.length;iResult++)
        {
            if(initListLeft[i1].getTime()>=initListRight[i2].getTime())
            {
                mergedList[iResult]=initListLeft[i1];
                i1++;
            }
            else
            {
                mergedList[iResult]=initListRight[i2];
                i2++;
            }
        }
        if(i1>=initListLeft.length)
        {
            for(var t=i2;t<initListRight.length;t++)
            {
                mergedList[iResult+t-i2]=initListRight[t];
            }
        }
        if(i2>=initListRight.length)
        {
            for(var t=i1;t<initListLeft.length;t++)
            {
                mergedList[iResult+t-i1]=initListLeft[t];
            }
        }
        return mergedList;
    }

    function insert(element,list,i)
    {
        while((i>=0)&&(element.getTime()>list[i].getTime()))
        {
            list[i+1]=list[i];
            i--;
        }
        list[i+1]=element;
    }

    function insertSortMoment(list)
    {
        var resultList=list;
        for(var j=1;j<list.length;j++)
        {
            insert(list[j],list,j-1);
        }
        return resultList;
    }

    function momentsListMergeSort(list)
    {
        var resultList=insertSortMoment(list[0]);
        for(var i=1;i<list.length;i++)
        {
            resultList=mergeMoment(resultList,insertSortMoment(list[i]));
        }
        return resultList;
    }
    
    this.displayList=function()
    {
        momentsList=momentsListMergeSort(momentsList);
        var i=0;
        for(i=0;i<momentsList.length;i++)
        {
            momentsList[i].displayMoment();
        }
        $("#mainContent").append(publishDiv);
        $(publishDiv).addClass("TextAlertRow");
        $(publishDiv).addClass("ClickAble");
        publishDiv.innerHTML="<font>发布新状态</font>";
        $(publishDiv).click(function(){
            if(!exsistPublishBar)
            {
                var inputBar=new InputBar("PublishMoment",$("#mainContent"),0);
                inputBar.showInputBar();
                exsistPublishBar=true;
            }
        });
    }
    
    this.selectById=function(id)
    {
        for(i=0;i<momentsList.length;i++)
        {
            var a=momentsList[i].getID();
            if(momentsList[i].getID()==id)
            {
                return momentsList[i];
            }
        }
    }
}