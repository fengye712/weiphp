<?php
class IndexAction extends BaseAction 
{
	 public function index()
	 {
		 $url = $_GET['url'];
		 $str = '';
		 if($url)
		 {
			$str = $url;	 
		 }
		 else
		 {
			$str = '';	 
		 }
			$APPID = $this->APPID;
			$REDIRECT_URI='http://hao.wx21.cn/index.php/Shouquan/callback/?url='.$str.'';
			$state = time();
			$scope='snsapi_userinfo';//需要授权模式
			$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
			header("Location:".$url);	 
	 }
}