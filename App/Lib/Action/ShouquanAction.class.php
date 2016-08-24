<?php
class ShouquanAction extends BaseAction 
{
  	public function callback()
	{
		$APPID = $this->APPID;
		$secret = $this->APPSCRET;
		$code = $_GET["code"]; 
 		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$APPID.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';	 
		$res = $this->https_curl($url);
		$json_obj = json_decode($res,true); 
		$access_token = $json_obj['access_token']; 
		$openid = $json_obj['openid']; 
		$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN'; 
		$res = $this->https_curl($get_user_info_url);
		$user_obj = json_decode($res,true);
		$yanzhen_shouquan = 'https://api.weixin.qq.com/sns/auth?access_token='.$access_token.'&openid='.$openid.'';
		$res = $this->https_curl($yanzhen_shouquan);
		$shouquan_obj = json_decode($res,true);
 		if($shouquan_obj['errmsg'] =='ok')
		{
			session('userinfo',$user_obj);
			if($_GET['url'])
			{
				header("Location:".base64_decode($_GET['url'])); 	
			}
			else
			{	
				
				$info=$this->getInfo('it_shop');
				VAR_DUMP($info);
				$this->assign('info',$info);
 				$this->display('Shouquan:index');
			}
		}
		else
		{
			$this->error('验证授权失效！');	
		}
	}
	
	
 	public function two()
	{
 		$list = M('product')->order('px asc')->select();
		$str = '';
		$i = 1;
		foreach($list as $key => $val)
		{
			$str .= '<div class="a'.$i.'"><div class="one_img" id='.$val['id'].'><div class="one_price">原价：'.$val['y_price'].'元</div><img src="'.$val['img_url'].'"/></div><div class="one_title">'.$val['f_title'].'</div><div class="one_dprice">低价：'.$val['d_price'].'元</div></div>'; 
			$i++;	
		}
		$this->assign('str',$str);
		$this->display();	
	}
	
	public function fcours()
	{
		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
		if($this->isset_userinfo['openid'])
		{ 
			Vendor('Jdkjs.jssdk');
			$jssdk = new JSSDK($this->APPID, $this->APPSCRET);
			$signPackage = $jssdk->GetSignPackage();
			$f_openid =	$_GET['f_openid']?$_GET['f_openid']:$this->isset_userinfo['openid'];
			$f_nickname =	$_GET['f_nickname']?$_GET['f_nickname']:$this->isset_userinfo['nickname'];
			$id = $_GET['id'];
			$p_article = M('product')->where('id = '.$id.'')->find();
			$this->assign('id',$id);
			$this->assign('f_openid',$f_openid);
			$this->assign('f_nickname',$f_nickname);
			$this->assign('userinfo',$this->isset_userinfo);
			$this->assign('p_article',$p_article);
			$this->assign('signPackage',$signPackage);
			$this->display();	
		}
		else
		{
			 
			$i_url = 'http://hao.wx21.cn/index.php/Index/index/?url='.base64_encode($url).'';
			header("Location:".$i_url);	
		}
	}
	
	public function insertkanjia()
	{
		$result = M('kanjia_product')->where('openid = "'.$this->isset_userinfo['openid'].'" and p_id = '.$_POST['p_id'].'')->count();
		if($result > 0)
		{
			$data['result'] = '您已经砍过此产品，请去对其它商品进行砍价,谢谢合作!';	
		}
		else
		{
			$product_ar = M('product')->where('id = '.$_POST['p_id'].'')->find();
			if($product_ar['k_price'] == 0)
			{
				$data['result'] = '此产品已经被砍完，请去对其它商品进行砍价,谢谢合作!';	
			}
			else
			{
				//$id = $_GET['p_id'];
				$rand = rand(0,8);
				$data['p_id'] = $_POST['p_id'];
				$data['rand'] = $rand;
				$data['openid'] = $this->isset_userinfo['openid'];
				$data['f_openid'] = $_POST['f_openid'];
				$data['f_nickname'] = $_POST['f_nickname'];
				$data['nickname'] = $this->isset_userinfo['nickname'];
				$data['sex'] = $this->isset_userinfo['sex'];
				$data['city'] = $this->isset_userinfo['city'];
				$data['province'] = $this->isset_userinfo['province'];
				$data['add_time'] = date('Y-m-d H:i:s');
				M('kanjia_product')->add($data);
				$v['k_price'] = array('exp','k_price - '.$rand.'');  //价格的修改的值
				M('product')->where('id = '.$_POST['p_id'].'')->save($v); //修改
			}
		}
			echo json_encode($data); 
 	}
	public function get_tongzhi()
	{
		$list = M('kanjia_product')->where('f_openid = "'.$this->isset_userinfo['openid'].'" and p_id = '.$_POST['id'].'')->select();
 		$str = '';
		foreach($list as $key => $val)
		{
			$str .= '<li>';
			$str .= '用户"'.$val['nickname'].'"为你砍了'.$val['rand'].'元';	
			$str .='</li>';
		}
		
		echo $str;
	}
	public function type(){
		$id=$_GET['id'];
		$info=M()->table('it_type')->where('typeid='.$id)->select();
		$this->assign('info',$info);
		$this->display();
	}
}