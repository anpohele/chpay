<?php

include "pay_demo.php";

/**
 * 微信公众号下单
 */
$oder = $pay->common->datetime2string(date('Y-m-d H:i:s'));
$pay->wxPublic_pay($oder);
