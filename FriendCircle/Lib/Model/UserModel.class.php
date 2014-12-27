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

	/*用来重设userID
	*/
	public function setNewUser($newUserName){
		$this->userName = $newUserName;
		$this->userID = $this->getUserID($newUserName);
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
	由于user数据库中暂时没有sex和birth，该方法暂时小黑屋
	功能：获取当前userID的用户的信息
	参数：userName
	返回：数组：array(
	    'userName'     =>  '用户昵称',
	    'userSex'   =>  '用户性别',
	    'userPhone'    =>  '用户手机号',
	)
	状态：基本完成
	*/
	public function getInformation($userID2){
		if($userID2 == $this->userID){
			$userResult = $this->query("SELECT userName,userSex,userPhone FROM user WHERE userID = '%s'",$this->userID);
			return $userResult[0];
		}
		$userID = $this->userID;
		$forbitUserResult1 = $this->execute("SELECT relationID FROM relation 
			WHERE userID1 = '%s' AND userID2 = '%s' AND state = 'friend' AND forbidInfo = '2to1' OR forbidInfo = 'all'",$userID, $userID2);
		$forbitUserResult2 = $this->execute("SELECT relationID FROM relation 
			WHERE userID1 = '%s' AND userID2 = '%s' AND state = 'friend' AND forbidInfo = '1to2' OR forbidInfo = 'all'",$userID2, $userID);
		if($forbitUserResult1 || $forbitUserResult2){
			return array(
				'userName'=>"***",
				'userSex'=>"***",
				'userPhone'=>"***"
				);
		}
		else{
			$userResult = $this->query("SELECT userName,userSex,userPhone FROM user WHERE userID = '%s'",$userID2);
			return $userResult[0];
		}
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

	public function checkForbid($userID2){
		$userID = $this->userID;
		$forbitUserResult1 = $this->execute("SELECT relationID FROM relation 
			WHERE userID1 = '%s' AND userID2 = '%s' AND state = 'friend' AND forbidInfo = '2to1' OR forbidInfo = 'all'",$userID, $userID2);
		$forbitUserResult2 = $this->execute("SELECT relationID FROM relation 
			WHERE userID1 = '%s' AND userID2 = '%s' AND state = 'friend' AND forbidInfo = '1to2' OR forbidInfo = 'all'",$userID2, $userID);
		if($forbitUserResult1 || $forbitUserResult2){
			return true;
		}
		else{
			return false;
		}
	}
	/*getFriendRespond方法
	//功能：获取当前userID的收到的好友请求，返回为用户名而不是ID
	//参数：null
	//返回：数组：array(
		'0'		=>		好友的userName'
		'1'		=>		好友的userName',
		...
	)
	//状态：基本完成
	*/
	public function getFriendRespond(){
		$userFriendRequest = $this->query("SELECT userID1 FROM relation 
			WHERE userID2 = '%s' AND state = 'send'", $this->userID);
		$friendStateArray = array();
		foreach ($userFriendRequest as $friendNum => $stateList) {
			array_push($friendStateArray, $this->getUserName($stateList['userID1']));
		}
		return $friendStateArray;
	}

	/*setFriendRespond方法
	//功能：由当前userID回复userID2发来的好友请求，建立新的好友关系，接受为userID！
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
	//功能：由当前userID1向userID2发送好友申请，接受为userID！
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
		if($userID1 == $userID2){
			return false;
		}
		$checkFriendState = $this->query("SELECT userID1,userID2,state FROM relation
			WHERE (userID1 = '%s' AND userID2 = '%s') OR (userID2 = '%s' AND userID1 = '%s') LIMIT 1",
			$userID1, $userID2, $userID1, $userID2);

		if(empty($checkFriendState)){
			$userFriendSet = $this->execute("INSERT INTO relation (userID1,userID2,state) VALUES ('%s','%s','%s')", $userID1, $userID2, 'send');
			return $userFriendSet;
		}
		else if($checkFriendState[0]['state'] == 'send'){
			if($checkFriendState[0]['userID1']==$userID1){
				return false;
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
	屏蔽传入userID2参数好友的动态，自己看不到他。
	*/
	public function setForbidMessage($userID2){
		if($this->userID == $userID2){
			return false;
		}
		$forbidUserResult1 = $this->query("SELECT forbidMessage FROM relation WHERE userID1 = '%s' AND userID2 = '%s' AND state ='friend'",$this->userID,$userID2);
		$forbidUserResult2 = $this->query("SELECT forbidMessage FROM relation WHERE userID1 = '%s' AND userID2 = '%s' AND state ='friend'",$userID2,$this->userID);
		if($forbidUserResult1[0]['forbidMessage'] == "1to2" || $forbidUserResult2[0]['forbidMessage'] == "2to1"){
			$forbidResult1 = $this->execute("UPDATE relation SET forbidMessage = 'all' WHERE userID1 = '%s' AND userID2 = '%s' AND state ='friend'",$this->userID,$userID2);
	 		$forbidResult2 = $this->execute("UPDATE relation SET forbidMessage = 'all' WHERE userID1 = '%s' AND userID2 = '%s' AND state ='friend'",$userID2,$this->userID);
		}
		else{
			$forbidResult1 = $this->execute("UPDATE relation SET forbidMessage = '2to1' WHERE userID1 = '%s' AND userID2 = '%s' AND state ='friend'",$this->userID,$userID2);
	 		$forbidResult2 = $this->execute("UPDATE relation SET forbidMessage = '1to2' WHERE userID1 = '%s' AND userID2 = '%s' AND state ='friend'",$userID2,$this->userID);	
		}
 		if($forbidResult1 || $forbidResult2){
 			return true;
 		}
 		else{
 			return false;
 		}
	}

	/*
	禁止传入userID2的用户来访问自己的个人信息。
	*/
	public function setForbidInfo($userID2){
		if($this->userID == $userID2){
			return false;
		}
		$forbitUserResult1 = $this->query("SELECT forbidInfo FROM relation 
			WHERE userID1 = '%s' AND userID2 = '%s' AND state = 'friend' AND forbidInfo != 'none'",$this->userID, $userID2);
		$forbitUserResult2 = $this->query("SELECT forbidInfo FROM relation 
			WHERE userID1 = '%s' AND userID2 = '%s' AND state = 'friend' AND forbidInfo != 'none'",$userID2, $this->userID);
		if($forbitUserResult1[0]['forbidInfo'] == "2to1" || $forbitUserResult2[0]['forbidInfo'] == "1to2"){
			$forbidResult1 = $this->execute("UPDATE relation SET forbidInfo = 'all' WHERE userID1 = '%s' AND userID2 = '%s'",$this->userID,$userID2);
 			$forbidResult2 = $this->execute("UPDATE relation SET forbidInfo = 'all' WHERE userID1 = '%s' AND userID2 = '%s'",$userID2,$this->userID);
		}
		else{
			$forbidResult1 = $this->execute("UPDATE relation SET forbidInfo = '1to2' WHERE userID1 = '%s' AND userID2 = '%s'",$this->userID,$userID2);
			$forbidResult2 = $this->execute("UPDATE relation SET forbidInfo = '2to1' WHERE userID1 = '%s' AND userID2 = '%s'",$userID2,$this->userID);
		}
 		if($forbidResult1 || $forbidResult2){
 			return true;
 		}
 		else{
 			return false;
 		}
	}

	/*
	获取已经屏蔽的userName好友列表
	*/
	public function getForbidList(){
		$userID = $this->userID;
		$userFriendList = $this->query("SELECT userID1,userID2,forbidMessage FROM relation 
			WHERE (userID1 = '%s' OR userID2 = '%s') AND state = 'friend' AND forbidMessage != 'none'",$userID, $userID);

		$friendArray = array();
		//foreach($array as key => [userID1, userID2])
		foreach ($userFriendList as $friendNum => $userIDList) {
			if($userIDList['userID1'] == $userID && $userIDList['forbidMessage'] == "2to1" || $userIDList['forbidMessage'] == "all"){
				array_push($friendArray, $this->getUserName($userIDList['userID2']));
			}
			else if($userIDList['userID2'] == $userID && $userIDList['forbidMessage'] == "1to2" || $userIDList['forbidMessage'] == "all"){
				array_push($friendArray, $this->getUserName($userIDList['userID1']));
			}
		}
		return $friendArray;
	}

	/*
	获取没有被屏蔽的userName好友列表
	*/
	public function getUnforbidList(){
		$userID = $this->userID;
		$userFriendList = $this->query("SELECT userID1,userID2 FROM relation 
			WHERE (userID1 = '%s' OR userID2 = '%s') AND state = 'friend' AND forbidMessage = 'none'",$userID, $userID);
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

	private $userID;
	private $userName;
}
?>