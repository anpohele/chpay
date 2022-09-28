<?php
if(!defined('IN_PLUGIN'))exit();

require_once(PAY_ROOT."inc/config.php");
$json = file_get_contents("php://input");
$data = json_decode($json, true);

$signStr = $data['MerchantId'].$data['DownstreamOrderNo'].$pay_config['key'];
$sign = md5($signStr);

if($sign === $data['Signature']){
	if($data['OrderState'] == 1){
		$trade_no = daddslashes($data['OrderNo']);
		if($data['DownstreamOrderNo'] == TRADE_NO && round($data['OrderMoney'],2)==round($order['realmoney'],2) && $order['status']==0){
			if($DB->exec("update `pre_order` set `status` ='1' where `trade_no`='".TRADE_NO."'")){
				$DB->exec("update `pre_order` set `api_trade_no` ='$trade_no',`endtime` ='$date',`date` =NOW() where `trade_no`='".TRADE_NO."'");
				processOrder($order);
			}
		}
		exit("OK");
	}
}
exit("ERROR");
