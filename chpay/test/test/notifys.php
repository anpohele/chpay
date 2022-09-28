<?php

include '../demo.php';

/**
 * 说明 仅作接口异步功能测试
 */
$s = new demo();
$notify = $s->respond_notify();
echo $notify;