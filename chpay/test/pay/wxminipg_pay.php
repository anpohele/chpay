<?php

include "pay_demo.php";

/**
 * ΢��С�����µ�
 */
$oder = $pay->common->datetime2string(date('Y-m-d H:i:s'));
$pay->wxminipg_pay($oder);