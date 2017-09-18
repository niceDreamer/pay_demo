<?php
return array(
	//支付宝配置参数
	'alipay_config'=>array(
	    'partner' =>'2088102028217511',   //这里是你在成功申请支付宝接口后获取到的PID；
	    'key'=>'aylt0pnkgoskrtq52kxvmumgdcbaeeie',//这里是你在成功申请支付宝接口后获取到的Key
	    'sign_type'=>strtoupper('MD5'),
	    'input_charset'=> strtolower('utf-8'),
	    'cacert'=> getcwd().'\\cacert.pem',
	    'transport'=> 'http',
	    'seller_email'=>'1526481415@qq.com',	
	),
);