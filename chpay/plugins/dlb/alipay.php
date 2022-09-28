<?php
if (!defined('IN_PLUGIN')) exit();
require PAY_ROOT . '/inc/App.php';
$sub = App::config(include PAY_ROOT . 'inc/config.php')->submit($order['trade_no'] , $order['realmoney']);
if ($sub === false) {
	sysmsg('下单失败！');
} else if (strtolower($sub['result']) === 'success' && array_key_exists('data' , $sub)) {
	$code_url = $sub['data']['url'];
} else {
	sysmsg('支付下单失败！['.$sub['error']['errorCode'].']'.$sub['error']['errorMsg']);
}

include PAYPAGE_ROOT.'alipay_qrcode.php';
?>