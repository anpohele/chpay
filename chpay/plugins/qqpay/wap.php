<?php
/*
 * QQ钱包手机跳转支付
*/
if(!defined('IN_PLUGIN'))exit();

@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

require_once (PAY_ROOT.'inc/qpayMchAPI.class.php');

//入参
$params = array();
$params["out_trade_no"] = TRADE_NO;
$params["body"] = $ordername;
$params["fee_type"] = "CNY";
$params["notify_url"] = $conf['localurl'].'pay/qqpay/notify/'.TRADE_NO.'/';
$params["spbill_create_ip"] = $clientip;
$params["total_fee"] = strval($order['realmoney']*100);
$params["trade_type"] = "NATIVE";

//api调用
$qpayApi = new QpayMchAPI('https://qpay.qq.com/cgi-bin/pay/qpay_unified_order.cgi', null, 10);
$ret = $qpayApi->reqQpay($params);
$result = QpayMchUtil::xmlToArray($ret);
//print_r($arr);

if($result['return_code']=='SUCCESS' && $result['result_code']=='SUCCESS'){
	$code_url = 'https://myun.tenpay.com/mqq/pay/qrcode.html?_wv=1027&_bid=2183&t='.$result['prepay_id'];
}elseif(isset($result["err_code"])){
	sysmsg('QQ钱包支付下单失败！['.$result["err_code"].'] '.$result["err_code_des"]);
}else{
	sysmsg('QQ钱包支付下单失败！['.$result["return_code"].'] '.$result["return_msg"]);
}
if(strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/')!==false){
	exit("<script>window.location.href='{$code_url}';</script>");
}

include PAYPAGE_ROOT.'qqpay_wap.php';
?>