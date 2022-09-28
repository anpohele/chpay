<?php

include 'pay_demo.php';

/**
 * 说明 仅作接口调用功能测试 Wap收银台
 */
$oder = $pay->common->datetime2string(date('Y-m-d H:i:s'));
echo $pay->h5_pay($oder);






