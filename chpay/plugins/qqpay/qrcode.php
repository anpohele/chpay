<?php
/*
 * QQ钱包电脑扫码支付
*/
if(!defined('IN_PLUGIN'))exit();

@header('Content-Type: text/html; charset=UTF-8');

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
//print_r($result);

if($result['return_code']=='SUCCESS' && $result['result_code']=='SUCCESS'){
	$code_url = $result['code_url'];
}elseif(isset($result["err_code"])){
	sysmsg('QQ钱包支付下单失败！['.$result["err_code"].'] '.$result["err_code_des"]);
}else{
	sysmsg('QQ钱包支付下单失败！['.$result["return_code"].'] '.$result["return_msg"]);
}

include PAYPAGE_ROOT.'qqpay_qrcode.php';
?>