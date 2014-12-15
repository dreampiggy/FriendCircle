<?php
class CommentModel extends Model{

	/*构造方法方法
	//功能：构造指定userID所能操作的指定messageID动态的评论，即模拟userID用户点击到一个动态之后的动作
	//参数：userID,messageID
	*/
	public function __construct($user,$currentMessage){
		parent::__construct();
		$this->user = $user;
		$this->userID = $this->user->getID();
		$this->userName = $this->user->getName();
		$this->messageID = $currentMessage;
	}
	/*getComment方法
	//功能：返回指定当前用户看到的某个messageID的全部评论，注意返回结构，而且现在是userName了！
	//参数：null
	//返回：二维数组：array(
	array(
		'commentID'	=>	'commentID内容，评论本身ID',
		'messageID'	=>	'messageID内容，messageID为根节点，指评论树最终归属于哪个动态',
		'parentID'	=>	'parentID内容，由于可以对之前的评论做回复，所以parentID指向父节点',
		'userName'	=>	'发表评论的用户名userName',
		'content'	=>	'评论本身的内容',
		'datetime'	=>	'评论发布时间'
		)
	)
	*/
	public function getComment(){
		$allMessageResult = $this->query("SELECT commentID,messageID,parentID,userName,content,datetime,anonymity FROM comment WHERE messageID = '%s'",$this->messageID);
		if(empty($allMessageResult)){
			return null;
		}
		$user = $this->user;
		$friendList = $user->getFriendList();
		$messageArray = array();
		//过滤出好友和我以及匿名的评论
		foreach ($allMessageResult as $number => $commentArray) {
			$i = 0;
			$commentUserName = $commentArray['userName'];
			while($friendList[$i]){
				if($friendList[$i] == $commentUserName || $this->userName == $commentUserName){
					//如果是匿名用户则将useName改为"匿名用户"
					if($commentArray['anonymity'] == "0"){
						unset($commentArray['anonymity']);
						array_push($messageArray, $commentArray);
					}
					else{
						$commentArray['userName'] = "匿名用户";
						unset($commentArray['anonymity']);
						array_push($messageArray, $commentArray);
					}
					break;
				}
				else{
					$i++;
				}
			}
		}
		return $messageArray;
	}

	/*addComment方法
	//功能：添加一条新评论，当前用户对当前messageID的一条评论(用parentID表示，若为-1表示评论是对动态本身的),内容为content，匿名为"true"，不匿名为"false"，默认不匿名，注意XSS,SQL
	//参数：parentID,content,anonymity
	//返回：bool值，true代表成功，false代表失败
	*/
	public function addComment($parentID,$content,$anonymity){
		if($anonymity != 0 && $anonymity != 1){
			return false;
		}
		$currentTime = date('y-m-d h:i:s',time());
		$addCommentResult = $this->execute("INSERT INTO comment (messageID,parentID,userID,userName,content,datetime,anonymity) VALUES ('%s','%s','%s','%s','%s','%s','%d')",$this->messageID,$parentID,$this->userID,$this->userName,$content,$currentTime,$anonymity);
		return $addCommentResult;
	}
	/*deleteComment方法
	//功能：当前用户删除指定commentID的这条评论，userID必须为这条comment的发布人
	//参数：commentID
	//返回：bool值，true代表成功，false代表失败
	*/
	public function deleteComment($commentID){
		$deleteCommentResult = $this->execute("DELETE FROM comment WHERE commentID = '%s' AND userID = '%s'",$commentID,$this->userID);
		return $deleteCommentResult;
	}

	private $user;
	private $userID;
	private $userName;
	private $messageID;
}
?>