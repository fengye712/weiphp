<?php
class BaseAction extends Action
{
	public $APPID = 'wx7c6eb63acf2a8ed6';   //公众号ID
	public $APPSCRET = '2a561c7ce9c359bb8f913eafbe43721c'; //公众号的密钥
	public $isset_userinfo;     
	public function _initialize()     //初始化用户信息
	{
		$this->isset_userinfo = session('userinfo');  //从session中取出用户的信息
 	}
	

	//curl参数
	function https_curl($url,$data='')   
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		if(!empty($data))
		{
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return 	$output;
	}
	function getInfo($name){
	$info=M()->table($name)->field('id,name')->select();
	return $info;	
	}

	
}
?>