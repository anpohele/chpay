<?php
if(!defined('IN_PLUGIN')) exit();
require_once PAY_ROOT . 'inc/App.php';
if (checkMobile() == true /*&& in_array(2 , $order['apptype'])*/) {
	echo "<script>window.location.href='/pay/dlb/{$order['typename']}wap/{$trade_no}/?sitename={$sitename}';</script>";
} else {
	echo "<script>window.location.href='/pay/dlb/{$order['typename']}/{$trade_no}/?sitename={$sitename}';</script>";
}
