<?php
/*
 * 支付宝当面付扫码支付
*/
if(!defined('IN_PLUGIN'))exit();

session_start();

@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){
	include(SYSTEM_ROOT.'pages/wxopen.php');
	exit;
}

require_once(PAY_ROOT."inc/config.php");
$getwayurl = 'http://api.zhangyishou.com/api/Order/AddOrder';
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


if($_SESSION[TRADE_NO.'_alipay']){
	$data = $_SESSION[TRADE_NO.'_alipay'];
}else{
	$data = zz_get_curl($getwayurl, json_encode($params));
	$_SESSION[TRADE_NO.'_alipay'] = $data;
}

$result = json_decode($data, true);

if($result['Code']=='1009'){
	$code_url = $result['Info'];
}else{
	sysmsg('支付宝创建订单失败！['.$result['Code'].']'.$result['Message'].':'.$result['Info']);
}

if(checkmobile()==true){
	include PAYPAGE_ROOT.'alipay_wap.php';
}else{
	include PAYPAGE_ROOT.'alipay_qrcode.php';
}
?>