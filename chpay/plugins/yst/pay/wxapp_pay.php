<?php

include 'pay_demo.php';

/**
 * 微信SDK下单
 */
$oder = $pay->common->datetime2string(date('Y-m-d H:i:s'));
$pay->wxapp_pay($oder);
