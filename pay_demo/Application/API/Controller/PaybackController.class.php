<?php
namespace API\Controller;
use Think\Controller;
class PaybackController extends CommonController{
	//专题支付宝支付异步（购买商品流程）
	public function notifyurl(){
		$callback = A("API/Alipay");
    	$notify = $callback->notifyurl();
    	$ordsn = $notify['out_trade_no'];
    	$total_fee = $notify['total_fee'];
        $show_url = explode("_", $ordsn);
    	if($notify['status']==1){        //支付成功
    		$this->orderhandle($show_url[1],$comment="支付宝购物商品获得");
            echo "success";       
    	}else{
            echo "fail";
        }
	}
	//专题支付完成返回页面
	public function ztreturn(){
		$callback = A("API/Alipay");
    	$return = $callback->returnurl();
    	$ordsn = $return['out_trade_no'];
        $show_url = explode("_", $ordsn);
    	if($return['status']==1){
    		//支付成功
    		$this->redirect("Ad/".$show_url['0']."/ordid/".$show_url['1']."/1");
    	}
    	if($return['status']==0){
    		//支付失败
    		$this->redirect("Ad/".$show_url['0']."/ordid/".$show_url['1']."/0");
    	}
    	if($return['status']==2){
    		//签名验证失败
    		$this->redirect("Ad/".$show_url['0']."/ordid/".$show_url['1']."/2");
    	}	
	}
    //wap支付宝支付异步（购买商品流程）
    public function wnotifyurl(){
        $callback = A("API/Alipay");
        $notify = $callback->notifyurl();
        $ordsn = $notify['out_trade_no'];
        $total_fee = $notify['total_fee'];
        if($notify['status']==1){        //支付成功
            $this->orderhandle($ordsn,$comment="支付宝购物商品获得");
            echo "success";       
        }else{
            echo "fail";
        }
    }
    //wap支付宝支付完返回操作
    public function wtreturn(){
        $callback = A("API/Alipay");
        $return = $callback->returnurl();
        $ordsn = $return['out_trade_no'];
        if($return['status']==1){
            //支付成功
            $this->redirect("Wap/cart/order_show",array("ord_sn"=>$ordsn));
        }
        if($return['status']==0){
            //支付失败
            $this->redirect("Wap/cart/order_show",array("ord_sn"=>$ordsn));
        }
        if($return['status']==2){
            //签名验证失败
            $this->redirect("Wap/cart/order_show",array("ord_sn"=>$ordsn));
        }   
    }
}