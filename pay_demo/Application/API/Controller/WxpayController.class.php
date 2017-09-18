<?php
namespace API\Controller;
use Think\Controller;
class WxpayController extends CommonController{
    //生成二维码
    public function config(){
        $config['APPID'] = "wx65b802d6efa7813e";
        //受理商ID，身份标识
        $config['MCHID'] = "1429261702";
        //商户支付密钥Key。审核通过后，在微信发送的邮件中查看
        $config['KEY'] = "wjwkgd90932017063077889910101112";
        //JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
        $config['APPSECRET'] = '';        
        //商户名称
        $config['COMPANY'] = "奇诱商城";
        $config['CURL_TIMEOUT'] = 30;
        return $config;
    }
    //判断客户端
    public function is_web(){
        $is_moblie = A("API/Ismoblie");
        $pay_type = $is_moblie->checkmobile();
        return $pay_type;
    }
    //显示页面
    public function dowechatpay($ord_sn,$ordcheurl="",$returnurl=""){ 
        $ord_info = $this->checkorderstatus($ord_sn);
        $data['total_fee'] = $ord_info['pay_money'];
        $data["out_trade_no"] = $ord_info["order_sn"];
        $data["consignee"] = $ord_info["consignee"];
        $data["tel"] = $ord_info["tel"];
        $data["address"] = $ord_info["address"];
        $data["city"] = $ord_info["city"];
        $data["district"] = $ord_info["district"];
        $data["province"] = $ord_info["province"];
        $data["ordcheurl"] = $ordcheurl;
        $data["return_url"] = $returnurl;
        if($this->is_web()){
            $this->code($ord_sn,$ordcheurl,$returnurl);
        }else{
            $this->assign("data",$data);
            $this->display("API@Index/wxpay"); 
        } 
    }
    public function code($ord_sn,$ordcheurl="",$returnurl=""){
        $ord_sn = $_GET['ord_sn']?$_GET['ord_sn']:$ord_sn;
        $ord_info = $this->checkorderstatus($ord_sn);
        $money = $ord_info['pay_money'];
        if($this->is_web()){
            $body = "奇诱商城Wap端支付[微信已安全认证]";
        }else{
            $body = "奇诱商城PC端支付[微信已安全认证]";
        } 
        // $body = "ceshi";
        $out_trade_no = $ord_sn;      //自定义订单号
        //使用统一支付接口
        $unifiedOrder = new \UnifiedOrder_pub();
        //设置必填参数
        // echo $body;die;
        $unifiedOrder->setParameter("body",$body);//商品描述        
        $unifiedOrder->setParameter("out_trade_no",$ord_sn);//商户订单号 
        $unifiedOrder->setParameter("total_fee",$money*100);//总金额
        $unifiedOrder->setParameter("notify_url",'Wxpay/notify');//通知地址 
        if($this->is_web()){
            $unifiedOrder->setParameter("trade_type","Wap");//交易类型
        }else{
            $unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
        }  
        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号 
        //$unifiedOrder->setParameter("attach","XXXX");//附加数据 
        //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
        //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
        //$unifiedOrder->setParameter("goods_tag","");//商品标记 
        //$unifiedOrder->setParameter("openid","19405");//用户标识
        // $unifiedOrder->setParameter("product_id",$goods_id);//商品ID
        // $unifiedOrder->__construct($config);
        //获取统一支付接口结果
        $unifiedOrderResult = $unifiedOrder->getResult();
        //商户根据实际情况设置相应的处理流程
        // if ($unifiedOrderResult["return_code"] == "FAIL") 
        // {
        //     //自行增加处理流程
        //     echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
        // }
        // elseif($unifiedOrderResult["result_code"] == "FAIL")
        // {
        //     //自行增加处理流程
        //     echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
        //     echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
        // }
        // elseif($unifiedOrderResult["code_url"] != NULL)
        // {
        //     //从统一支付接口获取到code_url
        //     $code_url = $unifiedOrderResult["code_url"];
        //     //自行增加处理流程
        //     //......
        // } 
        if(!$this->is_web()){
            echo qrcode($unifiedOrderResult['code_url'],6);
        }
    }
    public function notify(){
        //使用通用通知接口
        $notify = new \Notify_pub();
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        // var_dump($xml);
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();
        echo $returnXml;
         
        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
         
        //以log文件形式记录回调信息
        //         $log_ = new Log_();
        $log_name= __ROOT__."/Public/notify_url.log";//log文件路径
         
        $this->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");
         
        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【通信出错】:\n".$xml."\n");
                $this->error("1");
            }
            elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【业务出错】:\n".$xml."\n");
                $this->error("失败2");
            }
            else{
                //此处应该更新一下订单状态，商户自行增删操作
                log_result($log_name,"【支付成功】:\n".$xml."\n");
                $this->success("支付成功！");
            }
             
            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
        }
    }
    //查询订单
    public function orderQuery()
    {  
    //out_trade_no='+$('out_trade_no').value,

        //退款的订单号
        if (!isset($_POST["out_trade_no"]))
        {
            $out_trade_no = " ";
        }else{
            $out_trade_no = $_POST["out_trade_no"];
            //使用订单查询接口
            $orderQuery = new \OrderQuery_pub();
            $orderQuery->setParameter("out_trade_no",$out_trade_no);//商户订单号 
            //获取订单查询结果
            $orderQueryResult = $orderQuery->getResult();
            //交易状态
             // die;
            $data['order_sn'] = $orderQueryResult["out_trade_no"];
            if($orderQueryResult["result_code"] == "FAIL"){
                $data['state'] = $orderQueryResult["trade_state"];
                $data['msg'] = "订单获取失败";
                $data['status'] = 0;  
            }else{
                //判断交易状态
                switch ($orderQueryResult["trade_state"]){
                    case SUCCESS: 
                        $data['state'] = $orderQueryResult["trade_state"];
                        $data['msg'] = "支付成功！";
                        $data['status'] = 1;  
                        break;
                    case REFUND:
                        $data['state'] = $orderQueryResult["trade_state"];
                        $data['msg'] = "转入退款";
                        $data['status'] = 2;  
                        break;
                    case NOTPAY:
                        $data['state'] = $orderQueryResult["trade_state"];
                        $data['msg'] = "未支付";
                        $data['status'] = 3;
                        break;
                    case CLOSED:
                        $data['state'] = $orderQueryResult["trade_state"];
                        $data['msg'] = "已关闭";
                        $data['status'] = 4;
                        break;
                    case PAYERROR:
                        $data['state'] = $orderQueryResult["trade_state"];
                        $data['msg'] = "支付失败";
                        $data['status'] = 5;
                        break;
                    case  REVOKED:
                        $data['state'] = $orderQueryResult["trade_state"];
                        $data['msg'] = "已撤销（刷卡支付）";
                        $data['status'] = 6;
                        break;
                    case  USERPAYING:
                        $data['state'] = $orderQueryResult["trade_state"];
                        $data['msg'] = "用户支付中";
                        $data['status'] = 7;
                        break;
                    default:
                        $data['state'] = $orderQueryResult["trade_state"];
                        $data['msg'] = "未知原因";
                        $data['status'] = 8;
                        break;
                } 
            } 
            return $data; 
        }
    }
}
?>