<?php
/*
 * 微信电脑扫码支付
*/
if(!defined('IN_PLUGIN'))exit();

session_start();

@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

require_once(PAY_ROOT."inc/config.php");

if($pay_config['type_id'] == '101')$typename = 'wx_pub';
elseif($pay_config['type_id'] == '103')$typename = 'ls_wxpay';
elseif($pay_config['type_id'] == '104'){
    $t = rand(1,2);
    if ($t == 1) {
        $typename = 'eb_xxwx';
    }else{
        $typename = 'eb_xswx';
    }
}
else $typename = 'wxpay';

$data = [
	'version' => '1.0',
	'price' => number_format($order['realmoney'],2,".",""),
	'name' => $ordername,
	'body' => $ordername,
	'payment' => [
		'type' => $typename,
		'id' => $pay_config['type_id']
	],
	'server' => [
		'return' => $conf['localurl'].'pay/imirpay/notify/'.TRADE_NO.'/',
		'notify' => $conf['localurl'].'pay/imirpay/notify/'.TRADE_NO.'/',
		'url' => $_SERVER['HTTP_HOST']
	],
	'orderid' => TRADE_NO
];
$data = json_encode($data);

if (!openssl_public_encrypt($data, $encrypted, $pay_config['publickey'])) {
	sysmsg('数据加密失败，请检查公钥是否填写正确');
}

$param = [
	'cert_id' => $pay_config['cert_id'],
	'pay_data' => base64_encode($encrypted)
];

if($_SESSION[TRADE_NO.'_wxpay']){
	$data = $_SESSION[TRADE_NO.'_wxpay'];
}else{
	$data = get_curl($pay_config['apiurl'], $param);
	$_SESSION[TRADE_NO.'_wxpay'] = $data;
}
$result = json_decode($data, true);
//print_r($result);

if($result["status"]=='success'){
	$code_url = $result['pay_data'];
}else{
	sysmsg('微信支付下单失败！'.$result["error_msg"]);
}

if(checkmobile()==true){
	include PAYPAGE_ROOT.'wxpay_wap.php';
}else{
	include PAYPAGE_ROOT.'wxpay_qrcode.php';
}
?>