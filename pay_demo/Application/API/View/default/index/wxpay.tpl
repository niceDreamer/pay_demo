<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <load href="__PUBLIC__/wechat/qrcode.js" />
    <load href="__PUBLIC__/wechat/Base.js" />
    <load href="__PUBLIC__/wechat/prototype.js" />
    <load href="__PUBLIC__/wechat/mootools.js" />
    <load href="__PUBLIC__/wechat/Ajax/ThinkAjax.js" />
    <style type="text/css">
    	body{background: #f2f2f2}
    	p{margin: 0}
    	#weixinzhifu{width: 950px;margin: 0 auto;font-size: 16px;background: #fff}
    	.ord_info{font-size: 13px;font-style:normal;font-weight: normal;color: #333;line-height: 16px}
    	em{font-style:normal;font-weight: normal;color: #666;font-size:13px;}
    	.gray{width: 30%;}
    </style>
</head>
<body>
	<!-- <div class="oheader" style="width: 950px;margin:40px auto;">
        <a href="javascript:;"><img src="__PUBLIC__/Home/images/logo.png" alt=""></a>
    </div> -->
    <div class="bgwd" id="weixinzhifu">
        <table width="100%" border="0" height="">
	        <tbody>
		        <tr>
			        <td width="60" valign="top"><p class="okts"></p></td>
			        <td style="background:url(http://s.dddua.com/themes/v4/css/ft/weipay.png) 400px 90px no-repeat;">
			        	<h3 class="c3 lh30 f16">奇诱提醒：恭喜您订单提交成功！请使用微信扫一扫，扫描二维码支付，我们马上给你安排发货！</h3>
				        <div style="border:1px solid #ddd;width:280px;margin-top:50px;padding-top:10px;">
					        <div id="qrcode" class="alC" style="height:230px;text-align:center"><img src="{:U('API/Wxpay/code',array('ord_sn'=>$data['out_trade_no']))}" id="code"><p style="margin-top:-12px;" id="msg"></p></div>        
					        <table style="background-color:#eee;padding:10px;" class="ord_info">
						        <tr><td class="gray">订单编号：</td><td><b>{$data.out_trade_no}</b></td></tr>
						        <tr><td class="gray">收&nbsp;货&nbsp;人&nbsp;：</td><td>{$data["consignee"]}</td></tr>
						        <tr><td class="gray">联系电话：</td><td>{$data["tel"]}</td></tr>
						        <tr><td class="gray">收货地址：</td><td>{$data["province"]}-{$data["city"]}-{$data["district"]}-{$data["address"]}</td></tr>
						        <tr><td class="gray">订单总价:</td><td><b style="color: #c40000">¥{$data.total_fee}</b></td></tr>
					        </table>
				        </div>
				        <p class="lh18"><br>
				          <img src="__PUBLIC__/home/images/16012.png"> </p>
				        <p style="color:#666;font-size:12px">扫描上面微信二维码</p>
				        <p style="color: #c40000;font-size:12px">私密客服一对一提供售后服务</p>
				        <p style="margin:20px auto;"><a href="__ROOT__/Ad/{:I('get.file')}" style="width:40%;height:54px;text-align:center;display:block;line-height:54px;background:#cc4026;color:#fff;font-size:20px;margin:0 auto;text-decoration:none;font-size:normal;border-radius: 4px">返&nbsp;回&nbsp;首&nbsp;页</a></p>
				    </td>
			        <td width="60" valign="top"><p class="okts"></p></td>
		        </tr>
	        </tbody>
	    </table>
    </div>
</body>
    <script>
        function Check()
        {
            var out_trade_no = <?php echo $data['out_trade_no'];?>; 
            ThinkAjax.send("__ROOT__/API/Wxback/<?php echo $data['ordcheurl'];?>",'ajax=1&out_trade_no='+out_trade_no,goto);
        }
        var t1 = setInterval(Check,500);
        function returnurl(){
            window.location.href="<?php echo $data['return_url'].'/1'?>"
        }
        function goto(data,status){
            if (status==1)       //支付成功
            {   
                var oDiv = document.createElement('p');
                oDiv.innerHTML = "<span style='text-align:center;color:#70b839;margin:0'>√ 支付成功</span>";
                document.getElementById("msg").appendChild(oDiv);
                window.clearInterval(t1);
                setTimeout(function(){returnurl()},2000);  
            }
        }
    </script>   
</html>