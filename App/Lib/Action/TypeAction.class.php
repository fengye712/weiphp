<?php
class TypeAction extends BaseAction{
	public function type(){
		$id=$_GET['id'];
		$info=M()->table('it_type')->where('typeid='.$id)->select();
		$this->assign('info',$info);
		$this->display();
	}
	public function order(){
			//向数据库添加用户的威信数据
		Vendor('Jdkjs.jssdk');
		$jssdk = new JSSDK($this->APPID, $this->APPSCRET);
		$signPackage = $jssdk->GetSignPackage();
		$f_openid =	$_GET['f_openid']? $_GET['f_openid']:$this->isset_userinfo['openid'];
		$f_nickname =   $_GET['f_nickname']? $_GET['f_nickname']:$this->isset_userinfo['nickname'];
		
		$name=array();
		$name['openid']=$f_openid;
		$name['nickname']=$f_nickname;
		
		$wx_user=M()->table('it_wx')->add($name);
	$id=$_GET['id'];
	$info=M()->table('it_type')->where('id='.$id)->select();
	$this->assign('info',$info);
	$this->display();
	}
	public function add(){	
		if (IS_POST){
			$cid=$_POST['id'];
			$model=D('User');
			if($model->create()){
				$res=array();
				$data=$model->create();
				$res['name']=$data['name'];
				$res['sex']=$data['sex'];
				$res['tel']=$data['tel'];
				$res['card']=$data['card'];
				$res['time1']=$data['time1'];
				$res['time2']=$data['time2'];
				$res['addr']=$data['addr'];
				$res['addition']=$data['addition'];
				$res['price']=$data['price'];
				$result=$model->add($res);	
				if ($result){
				$id=$result;

					$res1=M()->table('it_type')->where('id='.$cid)->find();
					$newhold=$res1['hold']-1;
					$res1['hold']=$newhold;
					//$resu=M()->table('it_type')->field('hold')->where('id='.$id)->save($res);
					$sql="update it_type set hold={$res1['hold']} where id={$cid} ";
					$resd=D();
					$resd->execute($sql);
					$p=$res['price'];
					header('Location:http://hao.wx21.cn/WxpayAPI_php_v3/example/jsapi.php??id='.$result.'&m='.$p.'');
					//$this->redirect("http://hao.wx21.cn/WxpayAPI_php_v3/example/jsapi.php?id=".$result."");
					//$this->success('订单添加成功！',__GROUP__.'/Pay/index',3);//最终订单添加成功后要跳到支付的界面
					//$this->success('订单添加成功！',"http://hao.wx21.cn/WxpayAPI_php_v3/example/jsapi.php?id={$id}",3);
				}else{
					$this->error('订单添加失败！');
				     }
			}else{
				$this->error($model->getError());
			     }
		}
	}

}