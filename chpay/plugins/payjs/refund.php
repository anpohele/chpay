<?php
/*
 * PAYJS退款接口
*/
if(!defined('IN_REFUND'))exit();

require(PAY_ROOT.'inc/payjs.class.php');
$pay_config = require(PAY_ROOT.'inc/config.php');

$pay = new Payjs($pay_config);

$result = $pay->refund($order['api_trade_no']);

if($result['return_code'] == 1){
	$result = ['code'=>0, 'trade_no'=>$result['payjs_order_id'], 'refund_fee'=>$order['realmoney']];
}else{
	$result = ['code'=>-1, 'msg'=>$result["return_msg"]];
}
return $result;