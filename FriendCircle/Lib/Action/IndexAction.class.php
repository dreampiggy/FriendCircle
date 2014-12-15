<?php
class IndexAction extends Action {

   	// public function _empty(){
    // 	$this->display("Public:404");
    // }

    public function index(){
    	if(session('username')===null)
    	{
    		$this->redirect('./User');
    	}
    	else{
    		$this->display();
    	}
    }

    public function info(){
        $username = $this->_param(2);
    	if(session('username') === null){
    		$this->redirect('../');
    	}
  		else{
            $User = new UserModel(session('username'));
            $userID = $User->getUserID($username);
            if($User->checkForbid($userID)){
                $data['username'] = '***';
                $data['sex']      = '***';
                $data['phone']    = '***';
                $data['forbid']   = 'yes';
                $this->assign($data);
                $this->display('info');  
            }
            else{
                $result = $User->getInformation( $User->getID() );
                if($result['userSex'] == 'male'){
                    $result['userSex'] = '男';
                }
                else{
                    $result['userSex'] = '女';
                }
                $data['username'] = $username;
                $data['sessionName'] = session('username');
                $data['sex']      = $result['userSex'];
                $data['phone']    = $result['userPhone'];
                $data['forbid']   = 'no';
                $this->assign($data);
                $this->display('info');     
            }
      //       $result = $User->getInformation( $User->getID() );
	    	// $data['username'] = $username;
	    	// $data['sex']	  = $result['userSex'];
      //       $data['phone']    = $result['userPhone'];
      //       $data['forbid']   = 'no';
	    	// $this->assign($data);
	    	// $this->display('info');


    	}
    }

    public function friends(){
        $username = session('username');
        if(session('username') === null){
            $this->redirect('../');
        }
        else{
            $this->display('friends');
        }
    }
}