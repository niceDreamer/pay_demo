<?php
namespace API\Controller;
use Think\Controller;
class WxbackController extends CommonController{
    //专题商品支付完成流程
    public function orderQuery(){  
        $data = A("Wxpay")->orderQuery();
        //支付成功
        if($data['status']==1){
            $this->orderhandle($data['order_sn'],$comment="微信购物商品获得");
            // $pay = M("worder")->where(array("order_sn"=>$data['order_sn']))->save(array("pay_status"=>1));
        }
        $res['status'] = $data['status'];
        $this->ajaxReturn($res);
    }
}
?>