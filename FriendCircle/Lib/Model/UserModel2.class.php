 <?php
class UserModel extends Model{
	/*构造方法方法
	//功能：构造指定userName的User类
	//参数：userID, userName
	*/
	public function __construct($currentUserName){
		parent::__construct();
		$this->userName = $currentUserName;
		$this->userID = $this->getUserID($currentUserName);
	}
	/*get private 变量
	//功能：返回该user对象的userID, userName
	//参数：
	//返回：userID, userName
	//状态：绝壁完成
	*/
	public function getID(){
		return $this->userID;
	}
	public function getName(){
		return $this->userName;
	}

	/*下面的两个get方法在数据库查询错误时都不会返回任何值*/
	/*getUserName方法
	//功能：返回指定userID的userName
	//参数：userID
	//返回：userName,字符串，
	//状态：绝壁完成
	*/
	public function getUserName($userID){
		$userNameResult = $this->query("SELECT userName FROM user WHERE userID = '%s' LIMIT 1",$userID);
		return $userNameResult[0]['userName'];
	}
	/*getUserID方法
	//功能：返回指定userName的userID
	//参数：userName
	//返回：userID,字符串
	//状态：绝壁完成
	*/
	public function getUserID($userName){
		$userIDResult = $this->query("SELECT userID FROM user WHERE userName = '%s' LIMIT 1",$userName);
		return $userIDResult[0]['userID'];
	}

	/*getInformation方法
	//功能：获取当前userID的用户的信息
	//参数：null
	//返回：数组：array(
	    'userID'        =>  '用户账号',
	    'userName'     =>  '用户昵称',
	    'userSex'   =>  '用户性别',
	    'userBirth'    =>  '用户生日',
	)
	//状态：基本完成
	*/
	public function getInformation(){
		$userResult = $this->query("SELECT userID,userName,userSex,userBirth FROM user WHERE userID = '%s'",$this->userID);
		return $userResult[0];
	}

/*
以下是针对friend的操作：
	若user1对user2发送一条好友申请
	则在关系relation表里建立 user1 -> user2 的一条边
	并且将状态status设为send表示pending
	即：userID1 = user1, userID2 = user2, status = send
	注意：status 无默认值，非空，enum(send, friend)
*/
#######################################################


	/*getFriendList方法
	//功能：获取当前userID的好友列表
	//参数：
	//返回：数组：array(
		'0'	=>	'好友的userID',
		'1'	=>	'好友的userID',
		...
	)
	//状态：基本完成
	*/
	public function getFriendList(){
		$userID = $this->userID;
		$userFriendList = $this->query("SELECT userID1,userID2 FROM relation 
			WHERE (userID1 = '%s' OR userID2 = '%s') AND state = 'friend'",$userID, $userID);

		$friendArray = array();
		//foreach($array as key => [userID1, userID2])
		foreach ($userFriendList as $friendNum => $userIDList) {
			if($userIDList['userID1'] == $userID){
				array_push($friendArray, $this->getUserName($userIDList['userID2']));
			}
			else{
				array_push($friendArray, $this->getUserName($userIDList['userID1']));
			}
		}
		return $friendArray;
	}

	/*checkFriend方法
	//方法：查找 userID1 的好友列表，判断 userID2 是否在好友列表之中
	//功能：检查指定userID2的用户是否和当前userID为好友关系
	//参数：userID2
	//返回：bool值，true代表是好友，false代表不是
	//状态：绝壁完成
	*/
	public function checkFriend($userID2){
		$userFriendList = $this->getFriendList();
		foreach ($userFriendList as $friendID) {
			if($friendID == $userID2){
				return true;	
			}
		}
		return false;
	}

	/*getFriendRequest方法
	//功能：获取当前userID的收到的好友请求
	//参数：null
	//返回：数组：array(
		'0'		=>		好友的userName'
		'1'		=>		好友的userName',
		...
	)
	//状态：基本完成
	//注意：relation 表中总是 userID1 对 userID2 申请好友
	*/
	public function getFriendRequest(){
		$userFriendRequest = $this->query("SELECT userID1 FROM relation 
			WHERE userID2 = '%s' AND state = 'send'", $this->userID);
		$friendStateArray = array();
		foreach ($userFriendRequest as $friendNum => $stateList) {
			$name = $this->getUserName($stateList['userID1']);
			array_push($friendStateArray, $name);
		}
		return $friendStateArray;
	}

	/*setFriendRespond方法
	//功能：由当前userID回复userID2发来的好友请求，建立新的好友关系
	//参数：userID2,relation(包含两种，agree接受好友，reject拒绝好友)
	//返回：bool值
		返回false表示错误，原因是参数错误或数据库查询错误
		若userID与userID2已经是好友，返回false
		返回true表示操作正确
	agree状态下：
	只有在正确状态下：userID2 -> userID1 的 status 设为 friend
	reject状态下：
	只有在正确的状态：userID接受到userID2的好友请求，但是拒绝，返回true，其余返回false;
	//状态：基本完成
	*/
	public function setFriendRespond($userID2, $relation){
		$userID1 = $this->userID;
		//由于user2发给user1请求，故userID1 = user2，userID2 = user1
		$checkFriendState = $this->query("SELECT state FROM relation WHERE userID1 = '%s' AND userID2 = '%s' LIMIT 1", $userID2, $userID1);
		if($checkFriendState[0]['state'] == 'send'){
			if($relation == "agree"){
				$userFriendSet = $this->execute("UPDATE relation SET state = 'friend' WHERE userID1 = '%s' AND userID2 = '%s'",$userID2 ,$userID1);
				return true;
			}
			else if($relation == "reject"){
				$userFriendSet = $this->execute("DELETE FROM relation WHERE userID1 = '%s' AND userID2 = '%s'",$userID2 ,$userID1);
				return true;
			}
		}
		return false;
	}

	/*setFriendRequest
	//功能：由当前userID1向userID2发送好友申请
	//参数：userID2
	//返回：bool值
	//状态：基本完成
	//注意：
		在好友申请前要判断有没有 (user1, user2) 或 (user2, user1) 这样的 relation。
		如果有的话，有这几种情况
		1：之前已经创建了(user1, user2, send)，则返回true
		2：之前已经有了(user2, user1, send)，则说明两者都想建立关系，则直接将status改为friend (个人觉得这个设计是有问题的)
		3：之前两者已建立了friend关系，则返回false表示传入参数错误
		4：其他状况（包括状况3）都返回false
	*/
	public function setFriendRequest($userID2){
		$userID1 = $this->userID;

		$checkFriendState = $this->query("SELECT state FROM relation
			WHERE (userID1 = '%s' AND userID2 = '%s') OR (userID2 = '%s' AND userID1 = '%s') LIMIT 1",
			$userID1, $userID2, $userID1, $userID2);

		if(empty($checkFriendState)){
			$userFriendSet = $this->execute("INSERT INTO relation (userID1,userID2,state) VALUES ('%s','%s','%s')", $userID1, $userID2, 'send');
			return $userFriendSet;
		}
		else if($checkFriendState[0]['state'] == 'send'){
			if($checkFriendState[0]['userID1']==$userID1){
				return true;
			}
			else{
				$userFriendSet = $this->execute("UPDATE relation SET state = 'friend' 
					WHERE userID1 = '%s' AND userID2 = '%s'",$userID1,$userID2);
				return true;
			}
		}
		//else if($checkFriendState[0]['state'] == 'friend')
		return false;
	}

	/*
		
	*/
	private $userID;
	private $userName;
}
?>