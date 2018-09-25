<?php
namespace app\home\controller;
use think\Controller;
use think\Session;
/**
 * 
 */
class BaseController extends Controller
{
	
	public function _initialize()
	{
		if(!Session::get('username') || !Session::get('password')){
			return $this->redirect('/home/Login/login');
		}
		$this->_base();
	}

	public function _base()
	{

	}

}


?>
