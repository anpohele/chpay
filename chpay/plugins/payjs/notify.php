<?php
if(!defined('IN_PLUGIN'))exit();

require(PAY_ROOT.'inc/payjs.class.php');
$pay_config = require(PAY_ROOT.'inc/config.php');

$pay = new Payjs($pay_config);

if($pay->checkSign($_POST)){
	
	if($_POST['return_code'] == 1){
		$out_trade_no = daddslashes($_POST['out_trade_no']);
		$payjs_order_id = daddslashes($_POST['payjs_order_id']);
		$openid = daddslashes($_POST['openid']);
		$total_fee = $_POST['total_fee'];
		if($out_trade_no == TRADE_NO && $total_fee==strval($order['realmoney']*100) && $order['status']==0){
			if($DB->exec("update `pre_order` set `status` ='1' where `trade_no`='".TRADE_NO."'")){
				$DB->exec("update `pre_order` set `api_trade_no` ='$payjs_order_id',`endtime` ='$date',`buyer` ='$openid',`date`=NOW() where `trade_no`='".TRADE_NO."'");
				processOrder($order);
			}
		}

		echo 'success';
	}else{
		echo 'fail';
	}
}else{
	echo 'fail';
}
?>