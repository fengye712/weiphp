<?php
/**
* 	配置账号信息
*/

class WxPayConf_pub
{	
	const APPID = 'wx7c6eb63acf2a8ed6';
	const MCHID = '1259777001';
	const KEY = 'e10adc3949ba59abbe56e057f20f883e';
	const APPSECRET = '2a561c7ce9c359bb8f913eafbe43721c';
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	//const APPID = 'wx7c6eb63acf2a8ed6';
	//受理商ID，身份标识
	//const MCHID = '1259777001';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	//const KEY = '{商户支付密钥Key}';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	//const APPSECRET = '{APPSECRET}';
	const JS_API_CALL_URL = 'http://$_SERVER[HTTP_HOST]/paytest/index' ;
	
	
	
	const SSLCERT_PATH = '{$path}/cacert/apiclient_cert.pem';
	const SSLKEY_PATH = '{$path}/cacert/apiclient_key.pem';

	const NOTIFY_URL = 'http://$_SERVER[HTTP_HOST]/paytest/notify';
	const CURL_TIMEOUT = 60;
}

	
?>