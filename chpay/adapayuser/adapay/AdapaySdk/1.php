<?php
include_once  "init.php";
include_once  dirname(__FILE__). "/../AdapayDemo/config.php";

$payment = new \AdaPaySdk\Payment();
$payment->payPayments->app_id = "app_7d87c043-aae3-4357-9b2c-269349a980d6";
$payment->payPayments->order_no = "2021062316133629301075";
$payment->payPayments->pay_channel = "alipay_wap";
$payment->payPayments->pay_amt = "4.01";
$payment->payPayments->currency = "cny";
$payment->payPayments->goods_title = "测试商品";
$payment->payPayments->goods_desc = "用于支付流程测试的商品";
$payment->payPayments->description = "ddd";
$payment->payPayments->deviceInfo = [
    "device_type"=>"1",
    "device_ip"=>"106.14.16.249",
];

$payment->create();