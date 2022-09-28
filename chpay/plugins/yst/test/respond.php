<?php

include '../demo.php';

/**
 * 说明 仅作接口同步回调功能测试
 */
$s = new demo();
$respond = $s->respond();
echo $respond;