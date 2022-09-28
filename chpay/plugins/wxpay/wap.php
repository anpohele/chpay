<?php
/*
 * 微信手机扫码支付
*/
if(!defined('IN_PLUGIN'))exit();

@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

$code_url = $siteurl.'pay/wxpay/jspay/'.TRADE_NO.'/';

include PAYPAGE_ROOT.'wxpay_wap.php';
?>