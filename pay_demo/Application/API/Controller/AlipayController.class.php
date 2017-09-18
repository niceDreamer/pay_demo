<?php
namespace API\Controller;
use Think\Controller;
class AlipayController extends CommonController{
    public function config(){
        $alipay_config=array(
            'partner' =>'2088102028217511',   //这里是你在成功申请支付宝接口后获取到的PID；
            'key'=>'aylt0pnkgoskrtq52kxvmumgdcbaeeie',//这里是你在成功申请支付宝接口后获取到的Key
            'sign_type'=> strtoupper('MD5'),
            'input_charset'=> strtolower('utf-8'),
            'cacert'=> getcwd().'\\cacert.pem',
            'transport'=> 'http',
            'seller_email'=>'1526481415@qq.com',  
            'callurl'=>"http://new.qiyoumall.cn/API/Payback/" 
        ); 
        return $alipay_config;
    }
    public function doalipay($data){
        $is_moblie = A("API/Ismoblie");
        $pay_type = $is_moblie->checkmobile();
        $alipay_config = $this->config();

        /**************************请求参数**************************/
        $payment_type = "1"; //支付类型 //必填，不能修改
        $seller_email = $alipay_config['seller_email'];//卖家支付宝帐户必填
        $notify_url = $alipay_config['callurl'].$data['notify_url']; //服务器异步通知页面路径
        $return_url = $alipay_config['callurl'].$data['return_url']; //页面跳转同步通知页面路径 
        // print_r($return_url);die;
        $out_trade_no = $data["out_trade_no"];//商户订单号 通过支付页面的表单进行传递，注意要唯一！
        $total_fee = $data['total_fee'];   //付款金额  //必填 通过支付页面的表单进行传递
        if(!$pay_type){$khd = "PC";}else{$khd = "WAP";}
        $subject = "奇诱商城".$khd."端支付流水号".$data['create_time']."[支付宝已安全认证]";  //订单名称 //必填 通过支付页面的表单进行传递
        $body = '';  //订单描述 通过支付页面的表单进行传递
        $show_url = "";  //商品展示地址 通过支付页面的表单进行传递
        $anti_phishing_key = "";//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
        $exter_invoke_ip = get_client_ip(); //客户端的IP地址 
        /************************************************************/ 
        $parameter = array(
            "partner" =>    $alipay_config['partner'],
            "payment_type"    => $payment_type,
            "notify_url"    => $notify_url,
            "return_url"    => $return_url,
            "seller_email"    => $seller_email,
            "out_trade_no"    => $out_trade_no,
            "subject"    => $subject,
            "total_fee"    => $total_fee,
            "body"         => $body,
            "show_url"    => $show_url,
            "anti_phishing_key"    => $anti_phishing_key,
            "exter_invoke_ip"    => $exter_invoke_ip,
            "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );
        if($pay_type){
            $parameter["seller_id"] = $alipay_config['partner'];
            $parameter["service"] = "alipay.wap.create.direct.pay.by.user";
        }else{
            $parameter["service"] = "create_direct_pay_by_user";
        }
        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;
    }
    public function notifyurl(){
        $alipay_config = $this->config();
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            $list = $_POST;
            if($_POST['trade_status'] == 'TRADE_FINISHED') {

            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $list['msg'] = "支付成功";
                $list['status'] = 1;
            }
            $isSignStr = 'true'; 
        }else {
            $isSignStr = 'false';
            $list['msg'] = "验证失败";
            $list['status'] = 0;
        } 
        $log_text = "responseTxt=".$responseTxt."\n return_url_log:isSign=".$isSignStr.",";
        $log_text = $log_text.createLinkString($_GET);
        logResult($log_text);
        return $list; 
    }
    public function returnurl(){
        $alipay_config = $this->config();
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            $data = $_GET; 
            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') { 
                $data['msg'] = "支付成功";
                $data['status'] = 1;   
            }else {
                $data['msg'] = "支付失败";
                $data['status'] = 0;  
            }
        }else {
            $data['msg'] = "验证失败";
            $data['status'] = 2; 
        }
        return $data;     
    }
}
?>