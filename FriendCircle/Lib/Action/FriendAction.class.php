<?php
class FriendAction extends Action {   
   	
   	public function _initialize(){
   		header("Content-type: text/html; charset=utf-8"); 
   		if(session('username') == null){
   			$this->ajaxReturn("fuckyou","Error",0);
   		}
   		else{
   			$this->user = new UserModel(session('username'));
   		}
   		$this->message = new MessageModel($this->user);
   	}


   	public function _empty(){
    	$this->display("Public:404");
    }
    
    /*获取好友请求*/
    public function getFriendRequest(){
        $this->ajaxReturn($this->user->getFriendRespond(), '成功', 1);
    }

    public function getFriendList(){
    	$this->ajaxReturn($this->user->getFriendList(),'成功',1);
    }

    public function setFriendRequest(){
    	$username = $this->_param(2);
    	$userID =  $this->user->getUserID($username);
        $this->ajaxReturn($this->user->setFriendRequest($userID), '成功', 1);
    }

    public function setFriendRespond(){
    	$username = $this->_param(2);
    	$relation = $this->_param(3);
    	$userID = $this->user->getUserID($username);

    	$res = $this->user->setFriendRespond($userID, $relation);
    	if($res === true ){
    		$this->ajaxReturn('','成功',1);
    	}
    	else{
    		$this->ajaxReturn('','失败',0);
    	}
    }

    /*
    作用:获取个人动态或者好友动态
    参数:
    [0]	=>	用户名，与session相同返回用户自己的动态，不相同或者没有返回好友的动态
    返回示例:
    1、用户自己的动态
	{
	  "status" : 1,
	  "data" : [
	    {
	      "messageID" : "7",
	      "content" : "内容1",
	      "datetime" : "2014-12-12 10:45:21"
	    },
	    {
	      "messageID" : "30",
	      "content" : "内容2",
	      "datetime" : "2014-00-00 00:00:00"
	    },
	    {
	      "messageID" : "123",
	      "content" : "内容3",
	      "datetime" : "2014-12-13 10:44:47"
	    }
	  ],
	  "info" : "UserMoment"
	}
	2、好友的动态
	{
	  "status" : 1,
	  "data" : [
	    {
	      "userName" : "匿名用户",
	      "content" : "内容1",
	      "userID" : "11111112",
	      "datetime" : "2014-12-12 10:53:32",
	      "messageID" : "22"
	    },
	    {
	      "userName" : "匿名用户",
	      "content" : "内容2",
	      "userID" : "11111113",
	      "datetime" : "2014-12-12 10:45:20",
	      "messageID" : "4"
	    }
	  ],
	  "info" : "Moment"
	}
	*/
    public function getMoment(){
    	$this->ajaxReturn($this->message->getMessage(),"Moment",1);
    }

    public function getUserMoment(){
    	$userName = $this->_param(2);
    	$currentUserName = session('username');
    	if($userName == $currentUserName){
    		$userMessageResult = $this->user->query("SELECT messageID,userName,content,datetime FROM message WHERE userName = '%s'",$userName);
    		$this->ajaxReturn($userMessageResult,"UserMoment",1);
    	}
    	else{
    		$this->ajaxReturn($this->message->getUserMessage($userName),"UserMoment",1);
    	}
    }

    /*
    作用:发布一条动态
    参数:
    [0]	=>	动态内容
    [1]	=>	是否匿名，0为匿名，1为不匿名，默认为不匿名，其他为非法操作
    返回示例:
    1、成功添加
	{
	  "status" : 1,
	  "data" : "addOK",
	  "info" : "OK"
	}
	2、添加失败
	{
	  "status" : 0,
	  "data" : "addWrong",
	  "info" : "Wrong"
	}
    */
    public function addMoment(){
    	$momentContent = I('param.content');
    	$momentAnonymity = I('param.anonymity');
    	if($this->message->addMessage($momentContent,$momentAnonymity)){
    		$this->ajaxReturn("addOK","OK",1);
    	}
    	else{
    		$this->ajaxReturn("addWrong","Wrong",0);
    	}
    }

	/*
	作用:根据动态ID删除一条动态
	参数:
	[0]	=>	动态ID(MomentID)
	返回示例:
	1、删除成功
	{
	  "status" : 1,
	  "data" : "deleteOK",
	  "info" : "OK"
	}
	2、删除失败
	{
	  "status" : 0,
	  "data" : "deleteWrong",
	  "info" : "Wrong"
	}
	*/
    public function deleteMoment(){
    	$momentID = $this->_param(2);
    	if($this->message->deleteMessage($momentID)){
    		$this->ajaxReturn("deleteOK","OK",1);
    	}
    	else{
    		$this->ajaxReturn("deleteWrong","Wrong",0);
    	}
    }

	/*
	作用:返回当前动态的可见评论
	参数:
	[0]	=>	动态ID(MomentID)
	返回示例:
	1、成功
	{
	  "status" : 1,
	  "data" : [
	    {
	      "commentID" : "77",
	      "userID" : "11111111",
	      "userName" : "lizhuoli",
	      "messageID" : "4",
	      "content" : "老子是对动态本身的评论，你们怕不怕",
	      "parentID" : "-1",
	      "datetime" : "2014-12-12 23:46:00"
	    },
	    {
	      "commentID" : "133",
	      "userID" : "11111112",
	      "userName" : "wutao",
	      "messageID" : "4",
	      "content" : "老子和11111111是朋友",
	      "parentID" : "4",
	      "datetime" : "2014-12-13 01:08:50"
	    },
	    {
	      "commentID" : "134",
	      "userID" : "11111113",
	      "userName" : "jiaxing",
	      "messageID" : "4",
	      "content" : "老子和11111111是仇敌，你看不见我",
	      "parentID" : "-1",
	      "datetime" : "2014-12-13 01:08:53"
	    }
	  ],
	  "info" : "getOK"
	}
	*/
    public function getComment(){
    	$momentID = $this->_param(2);
    	//缓存压力
    	$message = new CommentModel($this->user,$momentID);
    	$this->ajaxReturn($message->getComment(),"getOK",1);
    }
	/*
	作用:添加一条评论
	参数:
	[0]	=>	messageID评论本身ID
	[1]	=>	parentID评论的父节点ID
	[2]	=>	评论的内容
	[3]	=>	是否匿名，0为不匿名，1为匿名，默认不匿名，其他非法
	返回示例:
	1、添加评论成功
	{
	  "status" : 1,
	  "data" : "addOK",
	  "info" : "OK"
	}
	2、添加评论失败
	{
	  "status" : 0,
	  "data" : "addWrong",
	  "info" : "Wrong"
	}
	*/
    public function addComment(){
    	$momentID = $this->_param(2);
    	$parentID = $this->_param(3);
    	$content = $this->_param(4);
    	$anonymity = $this->_param(5);
    	$message = new CommentModel($this->user,$momentID);
    	$addResult;
    	if($addResult = $message->addComment($parentID,$content,$anonymity)){
    		$this->ajaxReturn("addOK","OK",1);
    	}
    	else{
    		$this->ajaxReturn("addWrong","Wrong",0);
    	}
    }

	/*
	作用:删除一条评论
	参数:
	[0]	=>	评论ID(commentID)
	返回示例:
	1、删除成功
	{
	  "status" : 1,
	  "data" : "deleteOK",
	  "info" : "OK"
	}
	2、删除失败
	{
	  "status" : 1,
	  "data" : "deleteWrong",
	  "info" : "Wrong"
	}
	*/
    public function deleteComment(){
    	$commentID = $this->_param(2);
    	$message = new CommentModel($this->user,$commentID);
    	if($message->deleteComment($commentID)){
    		$this->ajaxReturn("deleteOK","OK",1);
    	}
    	else{
    		$this->ajaxReturn("deleteWrong","Wrong",1);
    	}
    }

    public function setForbidInfo(){
    	$userName = $this->_param(2);
    	$userID2 = $this->user->getUserID($userName); 

		if($this->user->setForbidInfo($userID2)){
			$this->ajaxReturn("setOK","OK",1);
		}
		else{
			$this->ajaxReturn("setWrong","Wrong",0);
		}
    }

    public function setForbidMessage(){
    	$userName = $this->_param(2);
    	$userID2 = $this->user->getUserID($userName); 

		if($this->user->setForbidMessage($userID2)){
			$this->ajaxReturn("setOK","OK",1);
		}
		else{
			$this->ajaxReturn("setWrong","Wrong",0);
		}
    }


    public function getInformation(){
    	$userName = $this->_param(2);
    	$userID = $this->user->getUserID($userName);
    	if($userID == $this->user->getID()){
    		$this->ajaxReturn($this->user->getInformation($userID),"yourInformation",1);
    	}
    	else{
    		$this->ajaxReturn($this->user->getInformation($userID),"othersInformation",1);
    	}
    }
    private $user;
}
?>
