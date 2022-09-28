<?php
include 'pay_demo.php';

/**
 * 正扫
 */
$oder = $pay->common->datetime2string(date('Y-m-d H:i:s'));
$pay->get_qrcode_pay($oder);
