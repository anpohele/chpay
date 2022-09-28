<?php
/**
 * 说明 三要素验证接口
 * 请在get_authen方法填写姓名、身份证、卡号
 */

include 'authen_demo.php';
$oder = $authen->common->datetime2string(date('Y-m-d H:i:s'));
$authen->get_authen($oder);