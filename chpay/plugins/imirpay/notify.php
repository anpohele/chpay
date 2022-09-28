<?php
if(!defined('IN_PLUGIN'))exit();

require_once(PAY_ROOT."inc/config.php");

$encrypted = base64_decode($_POST['notify']);

$ssl = @openssl_private_decrypt($encrypted, $decrypted, $pay_config['privatekey']);

if ($ssl) {
	$data = json_decode($decrypted,true);
	$out_trade_no = daddslashes($data['order']['orderid']);
	$trade_no = daddslashes($data['order']['order_no']);
	$money = daddslashes($data['price']);

    if ($out_trade_no == TRADE_NO && $data['status'] == 1 && $order['status']==0 && round($order['realmoney'],2)==round($money,2)) {
		if($DB->exec("update `pre_order` set `status` ='1' where `trade_no`='".TRADE_NO."'")){
			$DB->exec("update `pre_order` set `api_trade_no` ='$trade_no',`endtime` ='$date',`date` =NOW() where `trade_no`='".TRADE_NO."'");
			processOrder($order);
		}
    }
	echo 'OK';
} else {
    echo 'ERROR';
}
