<?php
/*
 * 微信电脑扫码支付
*/
if(!defined('IN_PLUGIN'))exit();

@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

if(in_array('1',$channel['apptype'])){
require_once PAY_ROOT."inc/WxPay.Api.php";
require_once PAY_ROOT."inc/WxPay.NativePay.php";
$notify = new NativePay();
$input = new WxPayUnifiedOrder();
$input->SetBody($ordername);
$input->SetOut_trade_no(TRADE_NO);
$input->SetTotal_fee(strval($order['realmoney']*100));
$input->SetSpbill_create_ip($clientip);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetNotify_url($conf['localurl'].'pay/wxpaysl/notify/'.TRADE_NO.'/');
$input->SetTrade_type("NATIVE");
$input->SetProduct_id("01001");
$result = $notify->GetPayUrl($input);
if($result["result_code"]=='SUCCESS'){
	$code_url=$result['code_url'];
}elseif(isset($result["err_code"])){
	sysmsg('微信支付下单失败！['.$result["err_code"].'] '.$result["err_code_des"]);
}else{
	sysmsg('微信支付下单失败！['.$result["return_code"].'] '.$result["return_msg"]);
}
}else{
	$code_url = $siteurl.'pay/wxpaysl/jspay/'.TRADE_NO.'/';
}

include PAYPAGE_ROOT.'wxpay_qrcode.php';
?>