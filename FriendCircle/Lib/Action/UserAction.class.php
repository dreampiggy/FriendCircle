<?php
class UserAction extends Action {   
   	
   	public function _empty(){
    	$this->display("Public:404");
    }

    /*
    添加了登陆session判定
    使得用户登陆之后无法得到登陆接口
    目的是浏览器单一用户登陆
    */
    public function index(){
        $username = session('username');
        //echo $username;
        //$n = '<br />';
        if(session('username')===null)
    	   $this->redirect('login');
        else{
            $this->redirect('../');
        }
    }

    public function login(){
        if(session('username')===null)
           $this->display('login');
        else{
            $this->redirect('../');
        }
    }

    public function checkLogin(){
    	$username = I('param.username');
    	$password = I('param.password');

    	if(empty($username)){
    		$this->error('账号错误');
    	}
    	elseif(empty($password)){
    		$this->error('密码必须');
    	}
        elseif(!empty(session('username'))){
            $this->ajaxReturn('','账号已登录',0);
        }

    	$User = D('User');
    	$map['userName'] = $username;
    	$result = $User->where($map)->find();
    	if($result === false){
    		$this->ajaxReturn('','查询数据库错误', 0);
    	}
    	elseif($result === null){
    		$this->ajaxReturn('','数据库查询后发现账号错误',0);
    	}
    	elseif($result['userPassword'] != sha1($password)){
    		$this->ajaxReturn('','密码错误',0);
    	}
    	else{
    		//成功登陆
    		//用户是否已经登陆的状态用Session保存
    		session('username',$result['userName']);
            session('password',$result['userPassword']);
            session('sex', $result['userSex']);
            session('phone', $result['userPhone']);
    		$this->ajaxReturn('','',1);
    	}
    }

    public function register(){
        if(session('username') === null)
            $this->display('register');
        else
            $this->redirect('../');
	}

	public function insertUser(){
		$username = I('param.username');
		$password = I('param.password');
        $sex      = I('param.sex');
        $phone    = I('param.phone');

		if(empty($username))
		{
			$this->error('用户名错误');
		}
		elseif (empty($password)) {
			$this->error('密码必须');
		}

		$User = D('User');
		$map['userName'] = $username;
		$getUser = $User->where($map)->find();
		
		if($getUser === false){
			$this->ajaxReturn('',$User->getError(),0);
		}
		elseif($getUser === null){
			$data['userName']     = $username;
			$data['userPassword'] = sha1($password);
            $data['userSex']      = $sex;
            $data['userPhone']    = $phone;
			$result = $User->data($data)->add();
			if($result === false){
				$this->ajaxReturn('','系统错误',0);//可换为$this->error($User->getError())
			}
			else{
				$this->ajaxReturn('','成功注册',1);//可换为$this->success('', '')
			}
		}
		else{
			$this->ajaxReturn('','你已注册',0);
		}
	}

    public function logout()
    {
        unset($_SESSION);
        session(null);
    	session_destroy();
        $this->redirect('../');
    }
}