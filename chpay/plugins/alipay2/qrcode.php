<?php
/*
 * 支付宝当面付扫码支付
*/
if(!defined('IN_PLUGIN'))exit();

@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
	include(SYSTEM_ROOT.'pages/wxopen.php');
	exit;
}

require_once(PAY_ROOT."inc/model/builder/AlipayTradePrecreateContentBuilder.php");
require_once(PAY_ROOT."inc/AlipayTradeService.php");

// 创建请求builder，设置请求参数
$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
$qrPayRequestBuilder->setOutTradeNo(TRADE_NO);
$qrPayRequestBuilder->setTotalAmount($order['realmoney']);
$qrPayRequestBuilder->setSubject($ordername);

// 调用qrPay方法获取当面付应答
$qrPay = new AlipayTradeService($config);
$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);

//	根据状态值进行业务处理
$status = $qrPayResult->getTradeStatus();
$response = $qrPayResult->getResponse();
if($status == 'SUCCESS'){
	$code_url = $response->qr_code;
}elseif($status == 'FAILED'){
	sysmsg('支付宝创建订单二维码失败！['.$response->sub_code.']'.$response->sub_msg);
}else{
	print_r($response);
	sysmsg('系统异常，状态未知！');
}

if(checkmobile()==true){
	include PAYPAGE_ROOT.'alipay_wap.php';
}else{
	include PAYPAGE_ROOT.'alipay_qrcode.php';
}
?>