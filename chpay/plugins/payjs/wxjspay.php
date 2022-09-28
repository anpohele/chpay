<?php
if(!defined('IN_PLUGIN'))exit();

@header('Content-Type: text/html; charset=UTF-8');

require(PAY_ROOT.'inc/payjs.class.php');
$pay_config = require(PAY_ROOT.'inc/config.php');

$pay = new Payjs($pay_config);

$arr = [
    'body' => $ordername,
    'out_trade_no' => TRADE_NO,
    'total_fee' => strval($order['realmoney']*100),
	'notify_url' => $conf['localurl'].'pay/payjs/notify/'.TRADE_NO.'/',
	'auto' => '1',
];
$url = $pay->cashier($arr);
exit("<script>window.location.replace('{$url}');</script>");

?>