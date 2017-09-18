<?php
namespace API\Controller;
use Think\Controller;
/**用法介绍：
*微信参数介绍：
*第一个参数表示订单号。
*第二个参数是检测订单状态的方法名，在wxback控制器下。
*第三个参数是支付成功之后的所要跳转的地址
*/
class IndexController extends CommonController {
    //支付宝
    public function Index(){
    	$data["out_trade_no"]= time();
        $data['subject']=123456;  
        $data['total_fee']=0.01; 
        $data['notify_url'] = "notifyurl";
        $data['return_url'] = "returnurl";    
        $content = A("Alipay")->doalipay($data);
    }
    //微信
    public function wxpay(){  
        $data = "2017090997235111"; 
        $content = A("Wxpay")->dowechatpay($data,"orderQuery","returnurl");   
    }
}
