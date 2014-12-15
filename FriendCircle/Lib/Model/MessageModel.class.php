<?php
class MessageModel extends Model{

	/*构造方法方法
	//功能：构造指定user和messageID
	//参数：user对象
	*/
	public function __construct($currentUser){
		parent::__construct();
		$this->user = $currentUser;
		$this->userID = $this->user->getID();
		$this->userName = $this->user->getName();
	}

	/*getMessage方法
	//功能：返回当前用户好友(包括匿名的好友)的全部动态，是全部动态，之后的返回部分我觉得放在Action比较好，这里加缓存
	//参数：null
	//高亮！！！！！！！
	//警告注意：这个函数必须加入缓存啊啊啊啊啊啊啊！！！！！！把取出来的好友的数据加入Memcached啊啊啊啊啊啊啊！！！！！！
	//返回：
	若存在动态，返回二维数组：array(
		array(
		'messageID'	=>	'第一条messageID',
		'userID'	=>	'第一条发布用户的userID',
		'userName'	=>	'第一条发布用户的用户名userName'
		'content'	=>	'第一条content内容',
		'datetime'	=>	'第一条发布时间datetime，格式2014-12-12 12:12:00'
		)
		array(
		'messageID'	=>	'第二条messageID',
		'userID'	=>	'第二条发布用户的userID',
		'userName'	=>	'第二条发布用户的用户名userName'
		'content'	=>	'第二条发布用户的用户名content',
		'datetime'	=>	'第二条发布时间datetime，格式2014-12-12 12:12:00'
		)
		...
	)
	若为匿名用户，返回的userName为"匿名用户"，其余不变
	若无动态，返回null
	*/
	public function getMessage(){
		//开始缓存，若用户没有变则直接从缓存中读取数据
		// $mem = new CacheMemcache();
		// if($cachedResult = $mem->get($this->userID)){
		// 	//缓存成功！
		// 	return $cachedResult;
		// }
		// else{
			$unSortMessageList = array();
			$unforbidFriendList = $this->user->getUnforbidList();
			//我擦两层foreach，你们看看有机会重构一下
			foreach ($unforbidFriendList as $number => $friendUserName) {
				$friendMessageResult = $this->query("SELECT messageID,userName,content,datetime,anonymity FROM message WHERE userName = '%s'",$friendUserName);
				foreach ($friendMessageResult as $number => $messageArray) {
					//如果匿名则将userName改为"匿名用户"
					if($messageArray['anonymity'] == "0"){
						unset($messageArray['anonymity']);
						array_push($unSortMessageList, $messageArray);
					}
					else{
						$messageArray['userName'] = "匿名用户";
						unset($messageArray['anonymity']);
						array_push($unSortMessageList, $messageArray);
					}
				}
			}
			// $mem->set($this->userID,$unSortMessageList);
			return $unSortMessageList;
		// }
	}

	/*getUserMessage方法
	//功能：返回currentUser自己发表的所有动态，匿名的信息不会返回
	//参数：null
	//返回：
	若存在动态，返回二维数组：array(
		array(
		'messageID'	=>	'第一条messageID',
		'userName'	=>	'自己的名字',
		'content'	=>	'第一条内容content',
		'datetime'	=>	'第一条发布时间datetime，格式2014-12-12 12:12:00'
		)
		array(
		'messageID'	=>	'第二条messageID',
		'userName'	=>	'自己的名字',
		'content'	=>	'第二条内容content',
		'datetime'	=>	'第二条发布时间datetime，格式2014-12-12 12:12:00'
		)
		...
	)
	若无动态，返回null
	*/
	public function getUserMessage($userName){
		$userMessageResult = $this->query("SELECT messageID,userName,content,datetime FROM message WHERE userName = '%s' AND anonymity = 0",$userName);
		return $userMessageResult;
	}
	/*addMessage方法
	//功能：由currentUser发表新的动态，参数为字符串:动态内容content,字符串:匿名true为匿名，false为不匿名，默认为不匿名
	//参数：content,anonymity
	//返回：bool值，true代表发表成功，false代表发表失败
	*/
	public function addMessage($content,$anonymity){
		if($anonymity != 0 && $anonymity != 1){
			return false;
		}
		$currentTime = date('y-m-d h:i:s',time());
		$addContentResult = $this->execute("INSERT INTO message (userID,userName,content,datetime,anonymity) VALUES ('%s','%s','%s','%s','%d')",$this->userID,$this->userName,$content,$currentTime,$anonymity);
		return $addContentResult;
	}

	/*deleteMessage方法
	//功能：由currentUser根据指定messageID删除动态
	//参数：messageID
	//返回：bool值，true代表删除成功，false代表删除失败
	*/
	public function deleteMessage($messageID){
		$deleteMessageResult = $this->execute("DELETE FROM message WHERE messageID = '%s' LIMIT 1",$messageID);
		return $deleteMessageResult;
	}



	private $user;
	private $userID;
	private $userName;
	private $messageID;
}
?>