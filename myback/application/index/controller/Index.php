<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
	//首页
    public function index()
    {
        $nav = Db::table('yl_category')->select();
        $con = Db::table('yl_news')->where('id=11')->find();
        $obj = Db::table('yl_news')->where("cate_id=39")->limit(0,4)->select();
        $this->assign('obj',$obj);
        $this->assign('nav',$nav);
        $this->assign('con',$con);
        return $this->fetch();
    }

    //设备简介
    public function machine()
    {
        $nav = Db::table('yl_category')->select();
        $con = Db::table('yl_news')->where('id=12')->find();
        $obj = Db::table('yl_news')->where("cate_id=39")->limit(0,4)->select();
        $this->assign('obj',$obj);
        $this->assign('nav',$nav);
        $this->assign('con',$con);
    	return $this->fetch();
    }

    //工程案例
    public function object()
    {
        $nav = Db::table('yl_category')->select(); 
        $obj_list1 = Db::table('yl_news')->where('cate_id=39')->limit(0,4)->select();  
        $obj_list2 = Db::table('yl_news')->where('cate_id=39')->limit(4,8)->select();
        $obj = Db::table('yl_news')->where("cate_id=39")->limit(0,4)->select();
        $this->assign('obj',$obj); 
        $this->assign('nav',$nav);        
        $this->assign('obj_list1',$obj_list1);
        $this->assign('obj_list2',$obj_list2);
    	return $this->fetch();
    }

    //行业新闻
    public function news()
    {
        $nav = Db::table('yl_category')->select();
        //新闻列表
        $news_list = Db::table('yl_news')->where("cate_id=40")->select();
        $obj = Db::table('yl_news')->where("cate_id=39")->limit(0,4)->select();
        $this->assign('obj',$obj);
        $this->assign('news_list',$news_list);
        $this->assign('nav',$nav);
    	return $this->fetch();
    }

    //新闻详情
    public function news_content()
    {
        $nid = $_GET['nid'];
        $nav = Db::table('yl_category')->select();
        $news_detial = Db::table('yl_news')->where("id=$nid")->find();
        $obj = Db::table('yl_news')->where("cate_id=39")->limit(0,4)->select();
        $this->assign('obj',$obj);
        $this->assign('nav',$nav);
        $this->assign('news_detial',$news_detial);
    	return $this->fetch();
    }

    //联系我们
    public function connect()
    {
        $nav = Db::table('yl_category')->select();
        $obj = Db::table('yl_news')->where("cate_id=39")->limit(0,4)->select();
        $con = Db::table('yl_news')->where("cate_id=41")->find();
        $this->assign('con',$con);
        $this->assign('obj',$obj);
        $this->assign('nav',$nav);
    	return $this->fetch();
    }
}
