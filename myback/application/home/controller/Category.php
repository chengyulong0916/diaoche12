<?php
namespace app\home\Controller;
use think\Controller;
use think\Db;
use app\home\controller\BaseController;

class Category extends BaseController{
	public function index()
	{
		$cate = Db::table('yl_category')->where("ismenu",1)->where("pid",0)->order("sort asc")->select();
		foreach($cate as $k=>$v){
			$pid = $v['id'];
			$in = Db::table('yl_category')->where("ismenu",1)->where("pid",$pid)->order("sort asc")->select();
			foreach($in as $kk=>$vv){
				$in[$kk]['catename'] = "-|-|".$vv['catename'];
			}

			$cate[$k]['in'] = $in;
		}
		// $nid = Db::table('yl_news')->column("id","cate_id");
		// print_r($nid);exit;
		// $this->assign('nid',$nid);
		$this->assign('cate',$cate);
		return $this->fetch();
	}

	//展示导航下的菜单  具体的文章
	public function cate_list()
	{
		$cid = $_GET['cid'];
		$cid = substr($cid,0,-5);
		//如果是父级栏目的话，则显示所有子栏目底下 的文章
		if(count($_GET)==3){

			$news_list[0] = Db::table('yl_news')->where('cate_id',$cid)->where('title','like','%'.$_GET['search_word'].'%')->select();
			$cid_arr = Db::table('yl_category')->where("pid",$cid)->column('id');
			foreach($cid_arr as $k=>$v){
				$news_list[$k+1]  = Db::table('yl_news')->where('cate_id',$v)->where('title','like','%'.$_GET['search_word'].'%')->select();
			}
			$this->assign('search_word',$_GET['search_word']);
			// print_r($_GET);exit;
		}else{
			$news_list[0] = Db::table('yl_news')->where('cate_id',$cid)->select();
			$cid_arr = Db::table('yl_category')->where("pid",$cid)->column('id');
			foreach($cid_arr as $k=>$v){
				$news_list[$k+1]  = Db::table('yl_news')->where('cate_id',$v)->select();
			}
			$this->assign('search_word','');
		}
		
		// print_r($news_list);exit;
		$this->assign('cid',$cid);
		$this->assign('news_list',$news_list);
		return $this->fetch();
	}

	public function aaa()
	{
		$aaa = $_POST;
		print_r($aaa);
	}

	public function add_cate()
	{
		$cate = Db::table('yl_category')->where("ismenu",1)->where("pid",0)->select();
		foreach($cate as $k=>$v){
			$pid = $v['id'];
			$in = Db::table('yl_category')->where("ismenu",1)->where("pid",$pid)->select();
			foreach($in as $kk=>$vv){
				$in[$kk]['catename'] = "-|-|".$in[$kk]['catename'];
			}
			$cate[$k]['in'] = $in;
		}
		$this->assign("cate",$cate);
		return $this->fetch();
	}

	//保存栏目
	public function save_cate()
	{
		
		$form = $_POST['form'];
		$cate_arr = $this->exp($form);
		unset($cate_arr['nav']);
		if($cate_arr['open']==1||$cate_arr['open']==''){
			//默认为开启
			$cate_arr['ismenu'] = 1;
		}else{
			$cate_arr['ismenu'] = 0;
		}
		unset($cate_arr['open']);
		// print_r($cate_arr);exit;
		if($cate_arr['id']){   //修改
			$res = Db::table('yl_category')->update($cate_arr);
		}else{                 //添加
			unset($cate_arr['id']);
			$res = Db::table('yl_category')->insert($cate_arr);
		}
		// print_r($cate_arr);exit;
		print_r($res);
	}

	//修改栏目
	public function edit_cate()
	{
		$cid = substr($_GET['cid'],0,-5);
		$ca = Db::table('yl_category')->where('id',$cid)->find();
		$ppid = $ca['pid'];
		$cate = Db::table('yl_category')->where("ismenu",1)->where("pid",0)->select();
		foreach($cate as $k=>$v){
			$pid = $v['id'];
			$in = Db::table('yl_category')->where("ismenu",1)->where("pid",$pid)->select();
			foreach($in as $kk=>$vv){
				$in[$kk]['catename'] = "-|-|".$in[$kk]['catename'];
				// print_r($vv['catename']);exit;
			}
			$cate[$k]['in'] = $in;
		// echo $ppid;exit;
		$this->assign('ca',$ca);}
		$this->assign('cate',$cate);
		$this->assign('ppid',$ppid);		
		return $this->fetch();
	} 

	//删除栏目
	public function del_cate()   //删除单网页栏目时  是否要删除掉底下的文章
	{
		$cid = $_POST['cid'];
		//先检查此栏目底下是否有子栏目
		$child = Db::table('yl_category')->where("pid",$cid)->select();
		if(count($child)){ //有子栏目
			echo 500;
			exit;
		}else{  //没有子栏目
			$res = Db::table('yl_category')->where("id",$cid)->delete();
			if($res){
				echo 200;exit;//删除成功
			}else{
				echo 404;exit;//删除失败
			}
		}
		print_r($child);
	}

	//修改文章内容
	public function edit_content()
	{
		// echo $_SERVER['REQUEST_URI'];exit;
		// print_r($_GET);exit;
		$cid = $_GET['cid'];
		$cid = substr($cid,0,-5);

		$nid = $_GET['nid'];
		// $nid = substr($nid,0,-5);
		// echo $cid;
		// echo $nid;exit;
		//判断是不是单网页
		$model = Db::table('yl_category')->where("id",$cid)->column('model')[0];
		if($model==0){  //单网页
			$news = Db::table('yl_news')->where("cate_id",$cid)->find();
			$this->assign('news',$news);
		}else{          //栏目页下的添加新闻
			$news = Db::table('yl_news')->where("id",$nid)->find();  
			$this->assign('news',$news);
		}
		$this->assign('nid',$nid);
		$this->assign('cid',$cid);
		return $this->fetch();
	}

	//保存添加或修改的文章内容
	public function save_content()
	{
		$data = $_POST;
		$form = $data['form'];
		$cid = $data['cid'];
		$nid = $data['nid'];
		//拆分成数组
		$con_arr = $this->exp($form);
		$content = $data['content'];
		$con_arr['content'] = $content;
		$con_arr['inputtime'] = time();
		$con_arr['cate_id'] = $cid;
		// echo $cid;exit;
		//判断是不是单网页
		$model = Db::table('yl_category')->where("id",$cid)->column('model')[0];
		if($model==0){    //单网页
			$nid_arr = Db::table("yl_news")->column('cate_id');
			//再判断是否已经有这个cate_id的数据了
			if(in_array($cid, $nid_arr)){   //有了   更新
				unset($con_arr['inputtime']);
				$con_arr['edittime'] = time();
				Db::table('yl_news')->where("cate_id",$cid)->update($con_arr);
				echo 200;
			}else{                          //没有  添加
				Db::table('yl_news')->insert($con_arr);
				echo 100;
			}
			// print_r($nid_arr);exit;
		}else{   //栏目页文章的添加或修改
			$news_id = Db::table("yl_news")->column('id');
			// 查看该篇文章是否已经存在
			if(in_array($nid, $news_id)){   //已经有了  更新
				unset($con_arr['inputtime']);
				$con_arr['edittime'] = time();
				Db::table('yl_news')->where("id",$nid)->update($con_arr);
				echo 200;
			}else{                          //没有，添加
				Db::table('yl_news')->insert($con_arr);
				echo 100;
			}
			// print_r($news_id);

		}
	}

	//删除栏目页底下的文章
	public function del_con()
	{
		$id = $_POST['id'];
		$res = Db::table('yl_news')->where('id',$id)->delete();
		if($res){
			echo 200;
		}else{
			echo 400;
		}
	}


	//拆分ajax传来的serialize字符串
	public function exp($data)
	{
		$form = explode("&",$data);
		$arr = array();
		foreach($form as $v){
			$val = explode('=',$v);
			$arr[$val[0]] = $val[1];
		}
		return $arr;
	}

	//上传缩略图
	public function uploadThumb(){
    	 // $image=I('post.img');
		 $image = $_POST['img'];
    	 $image=str_replace('&quot;','',$image);
         $image = str_replace('data:image/jpeg;base64,', '', $image);
         $image = str_replace(' ', '+', $image);
         
         $data = base64_decode($image);

         $files=uniqid();
         $year=date("Ym");

         $thumburl=$_SERVER['DOCUMENT_ROOT'].'/thumb/';
         if(!file_exists($thumburl.$year))
           {
         	 mkdir($thumburl.$year); 
           }       
         $file = $thumburl.$year.'/' . $files. '.jpeg';
          $file2 = $year.'/' . $files. '.jpeg';
         $success = file_put_contents($file, $data);
         print $success ? $file2 : '0';
    }
}





?>