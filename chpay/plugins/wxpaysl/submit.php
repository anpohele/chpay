<?php
if(!defined('IN_PLUGIN'))exit();

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
	if(!empty($conf['localurl_wxpay']) && !strpos($conf['localurl_wxpay'],$_SERVER['HTTP_HOST'])){
		echo "<script>window.location.href='{$conf['localurl_wxpay']}pay/wxpaysl/jspay/{$trade_no}/?d=1';</script>";exit;
	}
	echo "<script>window.location.href='/pay/wxpaysl/jspay/{$trade_no}/?d=1';</script>";
}elseif(checkmobile()==true){
	if(in_array('3',$channel['apptype'])){
		if(!empty($conf['localurl_wxpay']) && !strpos($conf['localurl_wxpay'],$_SERVER['HTTP_HOST'])){
			echo "<script>window.location.href='{$conf['localurl_wxpay']}pay/wxpaysl/h5/{$trade_no}/';</script>";exit;
		}
		echo "<script>window.location.href='/pay/wxpaysl/h5/{$trade_no}/';</script>";
	}elseif(in_array('2',$channel['apptype'])){
		if(!empty($conf['localurl_wxpay']) && !strpos($conf['localurl_wxpay'],$_SERVER['HTTP_HOST'])){
			echo "<script>window.location.href='{$conf['localurl_wxpay']}pay/wxpaysl/wap/{$trade_no}/';</script>";exit;
		}
		echo "<script>window.location.href='/pay/wxpaysl/wap/{$trade_no}/?sitename={$sitename}';</script>";
	}else{
		echo "<script>window.location.href='/pay/wxpaysl/qrcode/{$trade_no}/?sitename={$sitename}';</script>";
	}
}else{
	echo "<script>window.location.href='/pay/wxpaysl/qrcode/{$trade_no}/?sitename={$sitename}';</script>";
}
