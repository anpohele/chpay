<?php
if(!defined('IN_PLUGIN'))exit();

@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

require(PAY_ROOT.'inc/payjs.class.php');
$pay_config = require(PAY_ROOT.'inc/config.php');

$pay = new Payjs($pay_config);
$arr = [
    'body' => $ordername,
    'out_trade_no' => TRADE_NO,
    'total_fee' => strval($order['realmoney']*100),
	'notify_url' => $conf['localurl'].'pay/payjs/notify/'.TRADE_NO.'/',
];
$result = $pay->pay($arr);

if($result['return_code'] == 1){
	$code_url = $result['code_url'];
}else{
	sysmsg('微信支付下单失败 '.$result['return_msg']);
}

include PAYPAGE_ROOT.'wxpay_qrcode.php';
?>