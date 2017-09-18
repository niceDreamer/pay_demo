<?php
namespace API\Controller;
use Think\Controller;
class CommonController extends Controller {
	public function _initialize(){
        //引入WxPayPubHelper
        vendor('WxPayPubHelper.WxPayPubHelper');
        //引入支付宝支付
        vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');
    }
	//检测订单
    public function checkorderstatus($ordsn){
	    $Ord=M('worder');
	    $ordstatus=$Ord->where("order_sn = $ordsn")->find();
	    return $ordstatus;
	} 
	//支付成功成功之后业务逻辑
	public function orderhandle($ordsn,$comment="支付宝购物商品获得"){
		$ord_info = $this->checkorderstatus($ordsn);
		$total_fee = $ord_info['pay_money'];
		$id = $ord_info['id'];
		if(!$ord_info['pay_status']){
			if($ord_info['uid']){
				//添加会员积分明细表
	            $re['uid'] = $ord_info['uid'];
	            $re['h_point'] = round($total_fee);
	            $re['creat_time'] = Date("Y-m-d H:i");
	            $re['comment'] = $comment; 
	            M('wpoint')->add($re);
	            //会员表积分
	            $point = M("wuserinfo")->where(array("id"=>$ord_info['uid']))->find();
	            $list['total_points'] = round($point['total_points']+$total_fee);
	            $list['totalpoint'] = round($point['totalpoint']+$total_fee);
	            M("wuserinfo")->where(array("id"=>$ord_info['uid']))->save($list);  
			}
			//修改支付状态
			$data['pay_status'] = 1;
			M('worder')->where("id = $id")->save($data);
			return true;   
		}else{
			return false;
		}
	}

}
