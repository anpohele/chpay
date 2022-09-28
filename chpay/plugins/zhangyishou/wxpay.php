<?php
/*
 * 微信电脑扫码支付
*/
if(!defined('IN_PLUGIN'))exit();

session_start();

@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

require_once(PAY_ROOT."inc/config.php");
$getwayurl = 'http://api.zhangyishou.com/api/Order/SYWxPayYL';
$params = [
	'MerchantId' => (int)$pay_config['merchantId'],
	'DownstreamOrderNo' => TRADE_NO,
	'OrderTime' => $date,
	'PayChannelId' => (int)$pay_config['channelId'],
	'AsynPath' => $conf['localurl'].'pay/zhangyishou/notify/'.TRADE_NO.'/',
	'OrderMoney' => (float)$order['realmoney'],
	'IPPath' => $clientip,
];

$signStr = "";
foreach($params as $row){
	$signStr .= $row;
}
$signStr .= $pay_config['key'];
$params['MD5Sign'] = md5($signStr);
$params['Mproductdesc'] = $ordername;
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
	$params['ReturnUrl'] = $siteurl.'pay/zhangyishou/return/'.TRADE_NO.'/';
}

if($_SESSION[TRADE_NO.'_wxpay']){
	$data = $_SESSION[TRADE_NO.'_wxpay'];
}else{
	$data = zz_get_curl($getwayurl, json_encode($params));
	$_SESSION[TRADE_NO.'_wxpay'] = $data;
}

$result = json_decode($data, true);

if($result['Code']=='1009'){
	$code_url = $result['Info'];
}else{
	sysmsg('微信支付创建订单失败！['.$result['Code'].']'.$result['Message'].':'.$result['Info']);
}

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
	echo "<script>window.location.href='{$code_url}';</script>";
	exit;
}

if(checkmobile()==true){
	include PAYPAGE_ROOT.'wxpay_wap.php';
}else{
	include PAYPAGE_ROOT.'wxpay_qrcode.php';
}
?>