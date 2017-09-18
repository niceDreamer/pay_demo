<?php
$config=array(
	//'配置项'=>'配置值'
  	'MODULE_ALLOW_LIST'    => array('Home','Admin','Wap','Ad','API'),//设置可访问目录
	'TMPL_TEMPLATE_SUFFIX' => '.tpl',                 //设置模版后缀
	'DEFAULT_THEME'        => 'default',              //设置默认主题目录
	'DB_TYPE'              => 'mysql',               //数据库类型
	'DB_HOST'              => '127.0.0.1',            //服务器地址
	'DB_NAME'              => 'qyshop',            //数据库名
	'DB_USER'              => 'root',                 //用户名
	'DB_PWD'               => 'root',                     //密码
	'DB_PORT'              => 3306,                   //端口
	'DB_PREFIX'            => '',                  //数据库表前缀
	'DB_CHARSET'           => 'utf8',                 //字符集
	'SHOW_PAGE_TRACE'      => true,                   //debug
	'TMPL_CACHE_ON'        => false,                  // 是否开启模板编译缓存,设为false则每次都会重新编译
  'DEFAULT_FILTER'       => 'addslashes,htmlspecialchars,trim',           //默认过滤方法 //stripslashes
	'UPLOAD_PATH'          => './Upload/',   //图片上传路径
	"DEFAULT_MODLE"       => "Home",
	//url访问模式为rewrite模式
	'URL_MODEL'=>'2',
	//开启伪静态
	'URL_HTML_SUFFIX' =>'.html',
  'AUTH_CONFIG'=>array(
      'AUTH_ON' => true, //认证开关
      'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
      'AUTH_GROUP' => 'wauthgroup', //用户组数据表名
      'AUTH_GROUP_ACCESS' => 'wauthgroupaccess', //用户组明细表
      'AUTH_RULE' => 'wauthrule', //权限规则表
      'AUTH_USER' => 'wadmin'//用户信息表
  ),

  /*支付宝配置参数*/
  'alipay_config' => array(
  	'partner' 			=> '2088102028217511', // 这里是你在成功申请支付宝接口后获取到的PID；
  	'key' 				=> 'aylt0pnkgoskrtq52kxvmumgdcbaeeie', // 这里是你在成功申请支付宝接口后获取到的Key
  	'sign_type'			=> strtoupper('MD5'), 
  	'cacert'  			=> getcwd().'\\cacert.pem',
	'transport'			=> 'http',
  ),

  'alipay'=>array(
	 //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
		'seller_email'=>'1526481415@qq.com',
		//这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
		'notify_url'=>'http://127.0.0.1/qy/Pay/notifyurl', 
		//这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
		'return_url'=>'http://127.0.0.1/qy/Pay/returnurl',
		//支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
		'successpage'=>'User/myorder?ordtype=payed',   
		//支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
		'errorpage'=>'User/myorder?ordtype=unpay', 
  ),
);

return $config;
