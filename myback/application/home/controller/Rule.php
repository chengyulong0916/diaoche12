<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\controller\BaseController;

class Rule extends BaseController{
	public function show()
	{
		$head = Db::table('yl_rule')->where("status",1)->where("pid",0)->select();
		foreach($head as $key=>$val){
			$pid = $val['id'];
			$in = Db::table('yl_rule')->where("status",1)->where("pid",$pid)->select();
			foreach($in as $k=>$v){
				$in[$k]['title'] = "----".$v['title'];
			}
			$head[$key]["in"] = $in;
		}
		// print_r($head);exit;
		$this->assign("head",$head);
		return $this->fetch();
	}

	//添加权限页面
	public function add_rule()
	{	
		$head = Db::table('yl_rule')->where("status",1)->where("pid",0)->field("id,title,pid")->select();
		foreach($head as $key=>$val){
			$pid = $val['id'];
			$in = Db::table('yl_rule')->where("status",1)->where("pid",$pid)->field("id,title,pid")->select();
			foreach($in as $k=>$v){
				$in[$k]['title'] = "----".$v['title'];
			}
			$head[$key]["in"] = $in;
		}
		// print_r($head);exit;
		$this->assign("head",$head);
		return $this->fetch();
	}

	//保存添加的权限信息
	public function save_rule()
	{
		$data = $_POST['form'];
		$data_arr = explode('&',$data);
		$rule_arr = array();
		foreach($data_arr as $val){
			$arr = explode("=",$val);
			$rule_arr[$arr[0]] = $arr[1];
		}
		unset($rule_arr['rule']);
		// print_r($rule_arr);exit;
		if($rule_arr['open']==1||$rule_arr['open']==''){
			$rule_arr['is_yanzheng'] = 1;  //验证
			
		}else{
			$rule_arr['is_yanzheng'] = 0;  //不验证
		}
		unset($rule_arr['open']);
		$rule_arr['addtime'] = time();
		// print_r($rule_arr);exit;
		$result = Db::table('yl_rule')->insert($rule_arr);
		echo $result;
	}

	//修改权限信息
	public function edit_rule()
	{
		$id = input()['id'];
		$rule = Db::table("yl_rule")->where("id",$id)->find();
		// print_r($rule);exit;
		$head = Db::table('yl_rule')->where("status",1)->where("pid",0)->select();
		foreach($head as $key=>$val){
			$pid = $val['id'];
			$in = Db::table('yl_rule')->where("status",1)->where("pid",$pid)->select();
			foreach($in as $k=>$v){
				$in[$k]['title'] = "----".$v['title'];
			}
			$head[$key]["in"] = $in;
		}
		// print_r($head);exit;
		$this->assign("rule",$rule);
		$this->assign("head",$head);
		return $this->fetch();
	}

	//保存修改的权限信息
	public function save_edit()
	{
		$data = $_POST['form'];
		$id = $_POST['id'];
		// var_dump($id);exit;
		$arr = explode("&",$data);
		$rule_arr = array();
		foreach($arr as $rule){
			$rarr = explode("=",$rule);
			$rule_arr[$rarr[0]] = $rarr[1];
		}
		if(array_key_exists('rtrue', $rule_arr)){
			$rule_arr['is_yanzheng'] = 1;  //验证
			unset($rule_arr['rtrue']);
		}else{
			$rule_arr['is_yanzheng'] = 0;  //不验证
			unset($rule_arr['rfalse']);
		}
		$rule_arr['edittime'] = time();
		// print_r($rule_arr);
		$result = Db::table('yl_rule')->where("id",$id)->update($rule_arr);
		echo $result;
	}

	//删除权限信息
	public function del_rule()
	{
		$id = $_POST['id'];
		$result = Db::table('yl_rule')->where("id",$id)->delete();
		echo $result;
		
	}
}

?>