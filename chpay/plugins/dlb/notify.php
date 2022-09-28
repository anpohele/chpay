<?php
if(!defined('IN_PLUGIN')) exit();
require PAY_ROOT . 'inc/App.php';
@file_put_contents('./query.txt' , json_encode($_REQUEST));
if (App::config(include PAY_ROOT . 'inc/config.php')->verifyNotify()) {
	$trade_no = daddslashes($_REQUEST['requestNum']); //流水号
	$orderAmount = $_REQUEST['orderAmount']; //订单金额
	$orderStatus = strtolower($_REQUEST['status']);
	$completeTime = $_REQUEST['completeTime']; //订单完成时间
	if ($orderStatus === 'success') {
		if ($order['money'] == $orderAmount && $order['status'] == 0) {
			if($DB->exec("update `pre_order` set `status` ='1' where `trade_no`='{$trade_no}'")) {
				$DB->exec("update `pre_order` set `api_trade_no` ='{$trade_no}',`endtime` ='{$date}',`date` =NOW() where `trade_no`='{$trade_no}'");
			}
		}
		echo 'success';
	}
} else {
	echo 'fail';
}