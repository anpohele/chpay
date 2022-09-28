<?php
if(!defined('IN_PLUGIN'))exit();

@header('Content-Type: text/html; charset=UTF-8');

$code_url = $siteurl.'pay/swiftpass/wxjspay/'.TRADE_NO.'/';

include PAYPAGE_ROOT.'wxpay_wap.php';
?>