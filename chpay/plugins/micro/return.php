<?php 
if(!defined('IN_PLUGIN'))exit();

require_once(PAY_ROOT."inc/micro.config.php");
require_once(PAY_ROOT."inc/micro_notify.class.php");

@header('Content-Type: text/html; charset=UTF-8');

$url=creat_callback($order);

if ($order['status']==1) {
	echo '<script>window.location.href="'.$url['return'].'";</script>';
	exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link href="//lib.baomitu.com/ionic/1.3.2/css/ionic.min.css" rel="stylesheet" />
</head>
<body>
<div class="bar bar-header bar-light" align-title="center">
	<h1 class="title">订单处理结果</h1>
</div>
<div class="has-header" style="padding: 5px;position: absolute;width: 100%;">
<div class="text-center" style="color: #a09ee5;">
<i class="icon ion-information-circled" style="font-size: 80px;"></i><br>
<span>正在检测付款结果...</span>
<script src="//lib.baomitu.com/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.bootcdn.net/ajax/libs/layer/3.1.1/layer.min.js"></script>
<script>
	$(document).on('touchmove',function(e){
		e.preventDefault();
	});
	// 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "/getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "wxpay", trade_no: "<?php echo TRADE_NO?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
                    layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.01,time: 15000});
                    window.location.href=data.backurl;
                }else{
                    setTimeout("loadmsg()", 3000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("loadmsg()", 1000);
                } else { //异常
                    setTimeout("loadmsg()", 4000);
                }
            }
        });
    }
    window.onload = loadmsg();
</script>
</div>
</div>
</body>
</html>