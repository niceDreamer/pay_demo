<?php
  namespace Sms;
  include('TopSdk.php');
  use TopClient;
  use AlibabaAliqinFcSmsNumSendRequest;

  /**
   *
   */
  class MsgSend{

    public function send($recNum='', $smsParam='', $smsTemplateCode='SMS_54675046', $smsFreeSignName='短信测试'){
        $c = new TopClient;
        $c->format = "json";
        $c->appkey = '23696366';
        $c->secretKey = '179e79622f75e1e9764f8d4ee34da4e5';
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        //$req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($smsFreeSignName);
        $req->setSmsParam($smsParam);
        $req->setRecNum($recNum);
        $req->setSmsTemplateCode($smsTemplateCode);
        $resp = $c->execute($req);
        return $resp;
    }
  }
