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
	����Ƿ���΢����Ϣ
	*/
	function isweixin(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($user_agent, 'MicroMessenger') === false){
			return 0;
		}else{
			return 1;
		}
	}
 	//��ȥvender������  Pay/Wxpay/WxPayPubHelper/Wxpay.pub.config.php
	function index(){
		//������ص�����
		//����total��λ�Ƿ�
		$orderinfo = array(
		"totalfee"=>1500,//�ܷ���
		"orderno"=>"wxp14as54d5ads45as",//������
		"id"=>1,//����id
		"product"=>1,//��Ʒid
		"num"=>15,//��Ʒ��Ŀ
		);
		//��Ʒ��ص�����
		$product =array("id"=>1,
		"icon"=>"http://img4q.duitang.com/uploads/item/201206/13/20120613195538_iUxSx.thumb.700_0.jpeg",
		"name"=>"������Ʒ1"
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
						//����΢�ŷ���code��
						$url = $jsApi->createOauthUrlForCode($url1);
						Header("Location: $url"); 
						
			}else{
						//��ȡcode�룬�Ի�ȡopenid
						
						$jsApi->setCode($this->get['code']);
						$openid = $jsApi->getOpenId();
			}
			
			$unifiedOrder = new \UnifiedOrder_pub();
			$body = $product[name];
			$unifiedOrder->setParameter("openid","$openid");//��Ʒ����
			$unifiedOrder->setParameter("body",$body);//��Ʒ����
				
			$out_trade_no = $orderinfo[orderno];
			
			$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//�̻������� 
			$unifiedOrder->setParameter("total_fee","".$orderinfo[totalfee]);//�ܽ��.ע�ⵥλ�Ƿ�
			$unifiedOrder->setParameter("notify_url",\WxPayConf_pub::NOTIFY_URL);//֪ͨ��ַ 
			$unifiedOrder->setParameter("trade_type","JSAPI");//��������
			$unifiedOrder->setParameter("attach","orderid=$out_trade_no");//�������� 
			$unifiedOrder->setParameter("product_id",$product[id]);//��ƷID
			
			$prepay_id = $unifiedOrder->getPrepayId();
			
			//=========����3��ʹ��jsapi����֧��============
			$jsApi->setPrepayId($prepay_id);
			
			$jsApiParameters = $jsApi->getParameters();
			
			//����ǰ��Ϊ�ճ���������
			
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