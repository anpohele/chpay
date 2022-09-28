<?php
if(!defined('IN_PLUGIN'))exit();

if($order['typename']=='alipay'){
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false && !$submit2){
		echo "<script>window.location.href='/submit2.php?typeid={$order['type']}&trade_no={$trade_no}';</script>";exit;
	}
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
		include(SYSTEM_ROOT.'pages/wxopen.php');
		exit;
	}
}

if($order['typename']=='alipay'){
	echo "<script>window.location.href='/pay/payjs/alipay/{$trade_no}/';</script>";
}elseif($order['typename']=='wxpay'){
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
		echo "<script>window.location.href='/pay/payjs/wxjspay/{$trade_no}/?d=1';</script>";
	}elseif(checkmobile()==true){
		echo "<script>window.location.href='/pay/payjs/wxwappay/{$trade_no}/';</script>";
	}else{
		echo "<script>window.location.href='/pay/payjs/wxpay/{$trade_no}/';</script>";
	}
}