<?php
class PayAction extends BaseAction {
	
	
	protected $request;
	protected $post;
	protected $get;
	function _initialize(){
		$this->request=$_REQUEST;
		$this->post=$_POST;
		$this->get=$_GET;
		$this->session=$_SESSION;
	}
	
	/*
	检测是否是微信信息
	*/
	function isweixin(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($user_agent, 'MicroMessenger') === false){
			return 0;
		}else{
			return 1;
		}
	}
 	//先去vender下配置  Pay/Wxpay/WxPayPubHelper/Wxpay.pub.config.php
	function index(){
		//订单相关的数组
		//这里total单位是分
		$orderinfo = array(
		"totalfee"=>1500,//总费用
		"orderno"=>"wxp14as54d5ads45as",//订单号
		"id"=>1,//订单id
		"product"=>1,//商品id
		"num"=>15,//商品数目
		);
		//商品相关的数组
		$product =array("id"=>1,
		"icon"=>"http://img4q.duitang.com/uploads/item/201206/13/20120613195538_iUxSx.thumb.700_0.jpeg",
		"name"=>"测试商品1"
		);
		$isweixin = $this->isweixin();
		
		if($isweixin){
			$this->parepareweixin($orderinfo,$product);
		}else{
			
			$this->pareparealipay($orderinfo,$product);
		}
		
		$this->assign("isweixin",$isweixin);
		
		$this->assign("product",$product);
		$this->assign("order",$orderinfo);
		
		$this->display();
	}
	

	function parepareweixin($orderinfo,$product){
			
			Vendor("Pay.Wxpay.WxPayPubHelper");
			$jsApi = new \JsApi_pub();
			$orderid = $orderinfo[id];
			if (!isset( $this->get['code'])){
						
						$url1 =  \WxPayConf_pub::JS_API_CALL_URL;
						
						if(strpos($url1,"?")>0){
							$url1 .= ("&orderid=".$orderid);
						}else{
							$url1 .= ("?orderid=".$orderid);;
						}
						$url1 = urlencode($url1);
						//触发微信返回code码
						$url = $jsApi->createOauthUrlForCode($url1);
						Header("Location: $url"); 
						
			}else{
						//获取code码，以获取openid
						
						$jsApi->setCode($this->get['code']);
						$openid = $jsApi->getOpenId();
			}
			
			$unifiedOrder = new \UnifiedOrder_pub();
			$body = $product[name];
			$unifiedOrder->setParameter("openid","$openid");//商品描述
			$unifiedOrder->setParameter("body",$body);//商品描述
				
			$out_trade_no = $orderinfo[orderno];
			
			$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
			$unifiedOrder->setParameter("total_fee","".$orderinfo[totalfee]);//总金额.注意单位是分
			$unifiedOrder->setParameter("notify_url",\WxPayConf_pub::NOTIFY_URL);//通知地址 
			$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
			$unifiedOrder->setParameter("attach","orderid=$out_trade_no");//附加数据 
			$unifiedOrder->setParameter("product_id",$product[id]);//商品ID
			
			$prepay_id = $unifiedOrder->getPrepayId();
			
			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			
			$jsApiParameters = $jsApi->getParameters();
			
			//兼容前端为空出问题的情况
			
			$this->assign("jsApiParameters",$jsApiParameters);
			
			
	}
	
	
	function pareparealipay($orderinfo,$product){	
		$jsApiParameters = "{}";
		$this->assign("jsApiParameters",$jsApiParameters);
	}
	
	//
	function notify(){
		
	}
	
	
	
}