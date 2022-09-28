<?php
include 'pay_demo.php';

/**
 * 正扫
 */
// echo '<pre>';
// print_r($order);die;
// $oder = $pay->common->datetime2string(date('Y-m-d H:i:s'));
// print($oder);die;
$pay->get_qrcode_pay($order);
