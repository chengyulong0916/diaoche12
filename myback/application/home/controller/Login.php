<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use think\Session;

class Login extends Controller
{
	public function login()
	{
		return $this->fetch();
	}

	//验证登录信息
	public function yz_login()
	{
		$data = $_POST;
		$username = $data['username'];
		$password = MD5($data['password']);
		$captcha = $data['captcha'];

		if(!captcha_check($captcha)){
			echo 404;exit;       //验证码错误
		}else{
			$where['username'] = $username;
			$where['password'] = $password;
			$user = Db::name('admin')->where($where)->find();
			if($user){
				Session::set('username',$username);
				Session::set('password',$password);
				echo 200;exit;   //登陆成功
			}else{
				echo 401;exit;   //账号或者密码错误
			}
		}
	}
}
?>