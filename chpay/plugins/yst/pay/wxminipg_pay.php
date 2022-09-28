<?php

include "pay_demo.php";

/**
 * 微信小程序下单
 */
$oder = $pay->common->datetime2string(date('Y-m-d H:i:s'));
$pay->wxminipg_pay($oder);