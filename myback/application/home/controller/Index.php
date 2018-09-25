<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\controller\BaseController;

/**
 * 
 */
class Index extends BaseController
{
	
	public function index()
	{
		$rule = Db::table('yl_rule')->where("status",1)->where("pid",0)->field("id,title,href")->select();
		foreach($rule as $key=>$val){
			$in = Db::table('yl_rule')->where("status",1)->where("pid",$val['id'])->field("id,title,href")->select();
			$rule[$key]["in"] = $in;
		}
		// print_r($rule);exit;
		$this->assign("rule",$rule);
		return $this->fetch();
	}
}

?>