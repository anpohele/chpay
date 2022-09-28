<?php

include 'authen_demo.php';

/**
 * 说明 三要素(运营商)验证接口
 */
$oder = $authen->common->datetime2string(date('Y-m-d H:i:s'));
$authen->get_authen_mobile($oder);